<?php

use Carbon\Carbon;
use App\Models\DokumenKelas;
use App\Models\DokumenMatkul;
use App\Models\Kelas;
use App\Models\User;

function NamaPeran($peran){
    switch($peran){
        case 'kaprodi':
            return 'Koordinator Program Studi';
        break;
        case 'gkmp':
            return 'Gugus Kendali Mutu Prodi';
        break;
        case 'dosen':
            return 'Dosen Pengampu';
        break;
        case 'admin':
            return 'Administrator Prodi';
        break;
        default:
            return 'Tidak diketahui';
        break;
    }
}

function isPraktikum($data) {
    if($data == 1) {
        return 'Ya';
    }
    return 'Tidak';
}

function dikumpul($data) {
    if($data == 1) {
        return 'Multiple Dokumen';
    }
    return 'Single Dokumen';
}

function createTenggat($date, $default) {
    if($default > 0) {
        $default=$default-1;
    }
    $tenggat=Carbon::createFromLocaleIsoFormat('D MMMM YYYY', 'id', $date, 'Asia/Jakarta')->addWeek($default)->format('Y-m-d');
    $dt=Carbon::parse($tenggat);
    
    return $dt->endOfWeek()->addDay(-2);
}

function showWaktu($date) {
    if(is_null($date)) {
        return '-';
    }
    $waktu=Carbon::parse($date)->locale('id')->isoFormat('LLLL');
    
    return $waktu;
}

function backgroundStatus($tenggat, $waktu_pengumpulan) {
    $tenggat=Carbon::parse($tenggat);
    
    if(is_null($waktu_pengumpulan)) {
        if(Carbon::now()->isBefore($tenggat)) {
            return 'bg-warning-light text-warning';
        }return 'bg-danger-light text-danger';
    } else {
        $waktu_pengumpulan=Carbon::parse($waktu_pengumpulan);
        if($waktu_pengumpulan->isBefore($tenggat)) {
            return 'bg-success-light text-success';
        }
        return 'bg-danger-light text-danger';
    }
}

function statusPengumpulan($tenggat, $waktu_pengumpulan) {
    $tenggat=Carbon::parse($tenggat);
    
    if(is_null($waktu_pengumpulan)) {
        if(Carbon::now()->isBefore($tenggat)) {
            return 'Belum Dikumpulkan';
        }return 'Melewati Tenggat Waktu';
    } else {
        $waktu_pengumpulan=Carbon::parse($waktu_pengumpulan);
        if($waktu_pengumpulan->isBefore($tenggat)) {
            return 'Dikumpulkan';
        }
        return 'Terlambat Dikumpulkan';
    }
}

function isKelasDiampu($kode_kelas) {
    $kelas=Kelas::where('kode_kelas', $kode_kelas)->kelasDiampu()->first();
    
    if($kelas) {
        return true;
    }
    return false;
}

function kelasSummary($classes) {
    $daftar_kelas=[];
    $isDownloadable=false;
    foreach ($classes as $class) {
        $terlewat=0;
        $telat=0;
        $terkumpul=0;
        $ditugaskan=0;
    
        foreach($class->dokumen_kelas as $dokumen) {
            $tenggat=Carbon::parse($dokumen->dokumen_ditugaskan->tenggat_waktu);
            $waktu_pengumpulan=Carbon::parse($dokumen->waktu_pengumpulan);
            
            if($waktu_pengumpulan->isAfter($tenggat) && !is_null($dokumen->file_dokumen)) {
                $telat++;
            }
    
            if(Carbon::now()->isAfter($tenggat) && is_null($dokumen->file_dokumen)) {
                $terlewat++;
            }
    
            if(!is_null($dokumen->file_dokumen)) {
                $terkumpul++;
            }
            
            $ditugaskan++;
        }
    
        foreach($class->kelas_dokumen_matkul as $dokumen) {
            $tenggat=Carbon::parse($dokumen->dokumen_ditugaskan->tenggat_waktu);
            $waktu_pengumpulan=Carbon::parse($dokumen->waktu_pengumpulan);
            
            if($waktu_pengumpulan->isAfter($tenggat) && !is_null($dokumen->file_dokumen)) {
                $telat++;
            }
    
            if(Carbon::now()->isAfter($tenggat) && is_null($dokumen->file_dokumen)) {
                $terlewat++;
            }
    
            if(!is_null($dokumen->file_dokumen)) {
                $terkumpul++;
            }
            
            $ditugaskan++;
        }

        try {
            $persentase_dikumpul=round(($terkumpul/$ditugaskan)*100, 1);
        } catch (\Throwable $th) {
            $persentase_dikumpul=0;
        }

        if($isDownloadable == false && $terkumpul > 0) {
            $isDownloadable=true;
        }

        $daftar_kelas[] = (object) [
            'kode_kelas'          => $class->kode_kelas,
            'nama_kelas'          => $class->matkul->nama_matkul.' '.$class->nama_kelas,
            'terlewat'            => $terlewat,
            'telat'               => $telat,
            'terkumpul'           => $terkumpul,
            'ditugaskan'          => $ditugaskan,
            'persentase_dikumpul' => $persentase_dikumpul,
        ];

    }
    return (object) [
        'daftar_kelas' => $daftar_kelas,
        'isDownloadable' => $isDownloadable,
    ];
}

function dokumenSummary($dokumen_ditugaskans) {
    $daftar_dokumen=[];
    $isDownloadable=false;
    foreach ($dokumen_ditugaskans as $key => $dokumen_ditugaskan) {
        
        $terlewat=0;
        $telat=0;
        $terkumpul=0;
        $ditugaskan=0;
    
        if($dokumen_ditugaskan->dikumpulkan_per==0){
            $dokumen_dikumpul=$dokumen_ditugaskan->dokumen_matkul;
        }else{
            $dokumen_dikumpul=$dokumen_ditugaskan->dokumen_kelas;
        }
    
        foreach($dokumen_dikumpul as $dokumen) {
            $tenggat=Carbon::parse($dokumen_ditugaskan->tenggat_waktu);
            $waktu_pengumpulan=Carbon::parse($dokumen->waktu_pengumpulan);
            
            if($waktu_pengumpulan->isAfter($tenggat) && !is_null($dokumen->file_dokumen)) {
                $telat++;
            }
    
            if(Carbon::now()->isAfter($tenggat) && is_null($dokumen->file_dokumen)) {
                $terlewat++;
            }
    
            if(!is_null($dokumen->file_dokumen)) {
                $terkumpul++;
            }
            
            $ditugaskan++;
        }
        
        try {
            $persentase_dikumpul=round(($terkumpul/$ditugaskan)*100, 1);
        } catch (\Throwable $th) {
            $persentase_dikumpul=0;
        }

        if($isDownloadable == false && $terkumpul > 0) {
            $isDownloadable=true;
        }

        $daftar_dokumen[]= (object) [
            'id_dokumen_ditugaskan'  => $dokumen_ditugaskan->id_dokumen_ditugaskan,
            'nama_dokumen'           => $dokumen_ditugaskan->nama_dokumen,
            'terlewat'               => $terlewat,
            'telat'                  => $telat,
            'terkumpul'              => $terkumpul,
            'ditugaskan'             => $ditugaskan,
            'persentase_dikumpul'    => $persentase_dikumpul,
        ];
    }
    return (object) [
        'daftar_dokumen' => $daftar_dokumen,
        'isDownloadable' => $isDownloadable,
    ];
}

function isMatkul($type) {
    if($type == 0) {
        return true;
    }
    return false;
}

function pathDokumen($tahun_ajaran, $isMatkul, $matkul, $kelas=null) {
    if($isMatkul) {
        return 'app/dokumen-perkuliahan/'.str_replace('/','-',$tahun_ajaran).'/'.$matkul.'/dokumen-matkul';
    }
    return 'app/dokumen-perkuliahan/'.str_replace('/','-',$tahun_ajaran).'/'.$matkul.'/'.$kelas;
}

function pathDirectory($tahun_ajaran, $isKelas, $matkul, $kelas=null) {
    if($isKelas) {
        return 'app/dokumen-perkuliahan/'.str_replace('/','-',$tahun_ajaran).'/'.$matkul.'/'.$kelas;
    }
    return 'app/dokumen-perkuliahan/'.str_replace('/','-',$tahun_ajaran).'/'.$matkul;
}

function like_match($pattern, $subject) {
    $pattern = str_replace('%', '.*', preg_quote($pattern, '/'));
    
    return (bool) preg_match("/^{$pattern}$/i", $subject);
}

function showReport($dokumen_ditugaskan, $kelas) {
    $total_dikumpul=$total_belum_dikumpul=$total_tepat_waktu=$total_terlambat=$total_ditugaskan=$total_mendekati_dedline=$total_terlewat=0;
    $report=(object) [
        'kelas' => [],
    ];
    foreach($kelas as $key => $kls) {
        $submit=$late=$ontime=$empty=$mendekati_dedline=$terlewat=0;
        $report->kelas[$key] = (object) [
            'nama_matkul' => $kls->matkul->nama_matkul,
            'nama_kelas'  => $kls->nama_kelas,
        ];
        foreach($kls->dosen_kelas as $dosen) {
            $report->kelas[$key]->dosen[] = $dosen->nama;
        }
       foreach($dokumen_ditugaskan as $idx => $dokumen) {
            if($dokumen->dikumpulkan_per ==0) {
                foreach($kls->kelas_dokumen_matkul as $index => $dokumen_matkul) {
                    if($dokumen_matkul->id_dokumen_ditugaskan == $dokumen->id_dokumen_ditugaskan) {
                        $report->kelas[$key]->dokumen[$idx] = (object) [
                            'id_dokumen_ditugaskan' => $dokumen_matkul->id_dokumen_ditugaskan,
                            'nama_dokumen'         => $dokumen->nama_dokumen,
                        ];
                        if($dokumen_matkul->file_dokumen != null) {
                            $report->kelas[$key]->dokumen[$idx]->status = 1;
                            $submit++;

                            $tenggat_waktu=Carbon::parse($dokumen->tenggat_waktu);
                            $waktu_pengumpulan=Carbon::parse($dokumen_matkul->waktu_pengumpulan);

                            if($waktu_pengumpulan->isAfter($tenggat_waktu)) {
                                $report->kelas[$key]->dokumen[$idx]->is_late = 1;
                                $late++;
                            } else {
                                $report->kelas[$key]->dokumen[$idx]->is_late = 0;
                                $ontime++;
                            }
                        } else {
                            $tenggat_waktu=Carbon::parse($dokumen->tenggat_waktu);
                            if(Carbon::now()->isAfter($tenggat_waktu)) {
                                $terlewat++;
                            } else if(Carbon::now()->diffInDays($tenggat_waktu) > 0 && Carbon::now()->diffInDays($tenggat_waktu) <= 7) {
                                $mendekati_dedline++;
                            }
                            $report->kelas[$key]->dokumen[$idx]->status = 0;
                            $empty++;
                        }
                        break;
                    } else {
                        if ($index == count($kls->kelas_dokumen_matkul)-1) {
                            $report->kelas[$key]->dokumen[$idx] = (object) [
                                'id_dokumen_ditugaskan' => $dokumen->id_dokumen_ditugaskan,
                                'nama_dokumen'          => $dokumen->nama_dokumen,
                                'status'                => 2,
                            ];
                        }
                    }
                }
            } else {
                foreach($kls->dokumen_kelas as $index => $dokumen_kelas) {
                    if($dokumen_kelas->id_dokumen_ditugaskan == $dokumen->id_dokumen_ditugaskan) {
                        $report->kelas[$key]->dokumen[$idx] = (object) [
                            'id_dokumen_ditugaskan' => $dokumen_kelas->id_dokumen_ditugaskan,
                            'nama_dokumen'         => $dokumen->nama_dokumen,
                        ];

                        if($dokumen_kelas->file_dokumen != null) {
                            $report->kelas[$key]->dokumen[$idx]->status = 1;
                            
                            $submit++;
                            
                            $tenggat_waktu=Carbon::parse($dokumen->tenggat_waktu);
                            $waktu_pengumpulan=Carbon::parse($dokumen_kelas->waktu_pengumpulan);

                            if($waktu_pengumpulan->isAfter($tenggat_waktu)) {
                                $report->kelas[$key]->dokumen[$idx]->is_late = 1;
                                $late++;
                            } else {
                                $report->kelas[$key]->dokumen[$idx]->is_late = 0;
                                $ontime++;
                            }
                        } else {
                            $report->kelas[$key]->dokumen[$idx]->status = 0;
                            $empty++;
                        }
                        break;
                    } else {
                        if ($index == count($kls->dokumen_kelas)-1) {
                            $report->kelas[$key]->dokumen[$idx] = (object) [
                                'id_dokumen_ditugaskan' => $dokumen->id_dokumen_ditugaskan,
                                'nama_dokumen'          => $dokumen->nama_dokumen,
                                'status'                => 2,
                            ];
                        }
                    }
                }
            }
        }
        $report->kelas[$key]->terkumpul = $submit;
        $report->kelas[$key]->belum_terkumpul = $empty;
        $report->kelas[$key]->tepat_waktu = $ontime;
        $report->kelas[$key]->terlambat = $late;
        $report->kelas[$key]->ditugaskan = count($kls->kelas_dokumen_matkul)+count($kls->dokumen_kelas);
        $total_dikumpul += $submit;
        $total_belum_dikumpul += $empty;
        $total_tepat_waktu += $ontime;
        $total_terlambat += $late;
        $total_mendekati_dedline += $mendekati_dedline;
        $total_terlewat += $terlewat;
        $total_ditugaskan += count($kls->kelas_dokumen_matkul)+count($kls->dokumen_kelas);
    }

    $report->total_dikumpul = $total_dikumpul;
    $report->total_belum_dikumpul = $total_belum_dikumpul;
    $report->total_tepat_waktu = $total_tepat_waktu;
    $report->total_terlambat = $total_terlambat;
    $report->total_mendekati_dedline = $total_mendekati_dedline;
    $report->total_terlewat = $total_terlewat;
    $report->total_ditugaskan = $total_ditugaskan;

    
    return $report;
}

function makeArchive($name, $path) {
    $zip = new \ZipArchive();
    $fileName = $name.'.zip';
    $zip->open(storage_path('app/zip/'.$fileName), \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

    // Create recursive directory iterator
    /** @var SplFileInfo[] $files */
    $files = new \RecursiveIteratorIterator(
        new \RecursiveDirectoryIterator($path),
        \RecursiveIteratorIterator::LEAVES_ONLY
    );
    // dd($files);

    foreach ($files as $key => $file)
    {
        
        if (!$file->isDir()) {
            // dd($file);
            $filePath = $file->getRealPath();
            $relativeNameInZipFile = basename($file);
            $zip->addFile($filePath, $name.'/'.$relativeNameInZipFile);
        }
    }

    // Zip archive will be created only after closing object
    $zip->close();

    return storage_path('app/zip/'.$fileName);
}

function mergerDokumen($kelas)
{
    $dokumen_all = [];
    foreach($kelas as $kls) {
        $dosen=[];
        foreach($kls->dosen_kelas as $dsn) {
            $dosen[] = $dsn->nama;
        }
        foreach($kls->dokumen_kelas as $dokumen) {
            foreach($dokumen->scores as $score) {
                if($score->kode_kelas == $kls->kode_kelas) {
                    $poin = $score->poin;
                    $bonus = $score->bonus;
                }
            }
            $dokumen_all[] = (object) [
                'kode_kelas'        => $kls->kode_kelas,
                'id_dokumen'        => $dokumen->id_dokumen_kelas,
                'nama_dokumen'      => $dokumen->dokumen_ditugaskan->nama_dokumen,
                'matkul'            => $kls->matkul->nama_matkul,
                'kelas'             => $kls->nama_kelas,
                'dosen'             => $dosen,
                'poin'              => $poin,
                'bonus'             => $bonus,
                'dikumpul'          => $dokumen->dokumen_ditugaskan->dikumpul,
                'waktu_pengumpulan' => $dokumen->waktu_pengumpulan,
                'tenggat_waktu'     => $dokumen->dokumen_ditugaskan->tenggat_waktu,
            ];
        }
        // dd($dokumen_all);
        
        foreach($kls->kelas_dokumen_matkul as $dokumen) {
            foreach($dokumen->scores as $score) {
                if($score->kode_kelas == $kls->kode_kelas) {
                    $poin = $score->poin;
                    $bonus = $score->bonus;
                }
            }
            $dokumen_all[] = (object) [
                'kode_kelas'        => $kls->kode_kelas,
                'id_dokumen'        => $dokumen->id_dokumen_matkul,
                'nama_dokumen'      => $dokumen->dokumen_ditugaskan->nama_dokumen,
                'matkul'            => $kls->matkul->nama_matkul,
                'kelas'             => $kls->nama_kelas,
                'dosen'             => $dosen,
                'poin'              => $poin,
                'bonus'             => $bonus,
                'dikumpul'          => $dokumen->dokumen_ditugaskan->dikumpul,
                'waktu_pengumpulan' => $dokumen->waktu_pengumpulan,
                'tenggat_waktu'     => $dokumen->dokumen_ditugaskan->tenggat_waktu,
            ];
        }
    }
    
    // Panggil usort() dengan array dokumen beserta fungsi pengurutannya sebagai parameter
    usort($dokumen_all, function($a, $b) {
        $timeA = strtotime($a->waktu_pengumpulan);
        $timeB = strtotime($b->waktu_pengumpulan);
    
        if ($timeA == $timeB) {
            return 0;
        }
        return ($timeA > $timeB) ? -1 : 1;
    });
    
    return $dokumen_all;
}