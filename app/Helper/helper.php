<?php

use Carbon\Carbon;
use App\Models\AktifRole;
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

function CreateorDeleteAktifRole($id, $role, $requestRole) {
    if($role != $requestRole) {
        if(in_array($role, ['kaprodi', 'gkmp']) && in_array($requestRole, ['dosen', 'admin'])) {
            AktifRole::where('id_user', $id)->delete();
        } else if (in_array($role, ['dosen', 'admin']) && in_array($requestRole, ['kaprodi', 'gkmp'])) {
            AktifRole::create([
                'id_user'   => $id,
                'is_dosen'  => 0,
            ]);
        }
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
        return 'Mulltiple Dokumen';
    }
    return 'Single Dokumen';
}

function createTenggat($date, $default) {
    $tenggat=Carbon::createFromLocaleIsoFormat('D MMMM YYYY', 'id', $date, 'Asia/Jakarta')->addWeek($default)->format('Y-m-d');
    $dt=Carbon::parse($tenggat);
    
    return $dt->endOfWeek()->addDay(-2);
}

function showTenggat($date) {
    $tenggat=Carbon::parse($date)->locale('id')->isoFormat('LLLL');
    
    return $tenggat;
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
    $waktu_pengumpulan=Carbon::parse($waktu_pengumpulan);
    
    if($waktu_pengumpulan->isBefore($tenggat)) {
        return 'bg-success-light text-success';
    }
    return 'bg-danger-light text-danger';
}

function statusPengumpulan($tenggat, $waktu_pengumpulan) {
    $tenggat=Carbon::parse($tenggat);
    $waktu_pengumpulan=Carbon::parse($waktu_pengumpulan);
    
    if($waktu_pengumpulan->isBefore($tenggat)) {
        return 'Dikumpulkan';
    }
    return 'Terlambat Dikumpulkan';
}

function submitScore($tenggat, $waktu_pengumpulan, $isDokumenMatkul, $id_dokumen_terkumpul, $id_dokumen_ditugaskan) {
    $tenggat=Carbon::parse($tenggat);
    $waktu_pengumpulan=Carbon::parse($waktu_pengumpulan);
    
    if($waktu_pengumpulan->isBefore($tenggat)) {
        $status=2;
        $poin=100;
    } else {
        $status=1;
        $poin=-25;
    }

    if($isDokumenMatkul) {
        $dokumen_matkul_terkumpul=DokumenMatkul::where('id_dokumen_ditugaskan', $id_dokumen_ditugaskan)->wherenotnull('waktu_pengumpulan')->orderBy('waktu_pengumpulan', 'asc')->get();
        foreach ($dokumen_matkul_terkumpul as $key => $item) {
            if($id_dokumen_terkumpul == $item->id_dokumen_matkul) {
                if($key+1 == 1) {
                    $poin+=50;
                } else if ($key+1 == 2) {
                    $poin+=25;
                } else if ($key+1 == 3) {
                    $poin+=10;
                } else {
                    $poin+=0;
                }
                break;
            }
        }
    } else {
        $dokumen_kelas_terkumpul=DokumenKelas::where('id_dokumen_ditugaskan', $id_dokumen_ditugaskan)->wherenotnull('waktu_pengumpulan')->orderBy('waktu_pengumpulan', 'asc')->get();
        foreach ($dokumen_kelas_terkumpul as $key => $item) {
            if($id_dokumen_terkumpul == $item->id_dokumen_kelas) {
                if($key+1 == 1) {
                    $poin+=50;
                } else if ($key+1 == 2) {
                    $poin+=25;
                } else if ($key+1 == 3) {
                    $poin+=10;
                } else {
                    $poin+=0;
                }
                break;
            }
        }
    }

    return [
        'status'  => $status,
        'poin'      => $poin,
    ];
}

function isKelasDiampu($kode_kelas) {
    $kelas=Kelas::where('kode_kelas', $kode_kelas)->kelasDiampu()->first();
    
    if($kelas) {
        return true;
    }
    return false;
}

function kelasSummary($dokumen_kelas, $dokumen_matkul) {
    $terlewat=0;
    $telat=0;
    $terkumpul=0;
    $ditugaskan=0;

    foreach($dokumen_kelas as $dokumen) {
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

    foreach($dokumen_matkul as $dokumen) {
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

    return (object) [
        'terlewat' => $terlewat,
        'telat' => $telat,
        'terkumpul' => $terkumpul,
        'ditugaskan' => $ditugaskan,
        'persentase_dikumpul' => $persentase_dikumpul,
    ];
}

function dokumenSummary($dokumen_ditugaskan) {
    $terlewat=0;
    $telat=0;
    $terkumpul=0;
    $ditugaskan=0;

    if($dokumen_ditugaskan->dokumen_perkuliahan->dikumpulkan_per==0){
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

    return (object) [
        'terlewat' => $terlewat,
        'telat' => $telat,
        'terkumpul' => $terkumpul,
        'ditugaskan' => $ditugaskan,
        'persentase_dikumpul' => $persentase_dikumpul,
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
        return 'dokumen-perkuliahan/'.str_replace('/','-',$tahun_ajaran).'/'.$matkul.'/dokumen-matkul';
    }
    return 'dokumen-perkuliahan/'.str_replace('/','-',$tahun_ajaran).'/'.$matkul.'/'.$kelas;
}

function countStatusDokumen($filter=null, $tahun_ajaran=null) {
    $dokumen_kelas=DokumenKelas::whereHas('dokumen_ditugaskan', function($query) use ($tahun_ajaran) {
        $query->dokumenTahun($tahun_ajaran);
    })->filter($filter);
    $dokumen_all=DokumenMatkul::with(['dokumen_ditugaskan', 'kelas_dokumen_matkul'])->whereHas('dokumen_ditugaskan', function($query) use ($tahun_ajaran) {
        $query->dokumenTahun($tahun_ajaran);
    })->filter($filter)->union($dokumen_kelas)->orderBy('waktu_pengumpulan', 'desc')->get();

    return count($dokumen_all);
}

function dosenKelas($kelas_dokumen_matkul) {
    $dosen=array();
    foreach ($kelas_dokumen_matkul as $kelas) {
        foreach ($kelas->dosen_kelas as $dosen_kelas) {
            if(!in_array($dosen_kelas->nama, $dosen)) {
                array_push($dosen, $dosen_kelas->nama);
            }
        }
    }
    return $dosen;
}

function like_match($pattern, $subject) {
    $pattern = str_replace('%', '.*', preg_quote($pattern, '/'));
    
    return (bool) preg_match("/^{$pattern}$/i", $subject);
}

function showProfilDokumen($id_dokumen) {
    $dokumen=DokumenMatkul::where('id_dokumen_matkul', $id_dokumen)->first();

    return (object) [
        'nama_matkul' => $dokumen->matkul->nama_matkul,
        'dosen'       => dosenKelas($dokumen->kelas_dokumen_matkul),
    ];
}

function showRank($users) {
    $leaderboard=array();
    foreach($users as $user) {
        $late=$onTime=$sum_submited=$empty=$sum_score=0;
        $task=count($user->score);
        // dd($user->score);
        foreach ($user->score as $score) {
                
            if($score->status === 0) {
                $empty++;
            } else if($score->status == 1){
                $late++;
            } else if($score->status == 2){
                $onTime++;
            }

            if($score->scoreable->file_dokumen != null) {
                $sum_submited++;
            }
            
            $sum_score+=$score->score;
        }
        try {
            $percent=round(($sum_submited/$task)*100, 1);
        } catch (\Throwable $th) {
            $percent=0;
        }
        
        try {
            $point=round($sum_score/$task, 1);
        } catch (\Throwable $th) {
            $point=0;
        }

        $leaderboard[]=(object) [
            'user' => $user,
            'late'  => $late,
            'onTime'=> $onTime,
            'empty' => $empty,
            'task'  => $task,
            'percent' => $percent,
            'point' => $point,
        ];
        
    }
    // dd($leaderboard);

    usort($leaderboard, function($a, $b) {
        return $b->point <=> $a->point;
    });

    // dd($leaderboard);
    
    return $leaderboard;
}

function giveBadge($id_user, $id_badge, $id_tahun_ajaran) {
    $user=User::find($id_user);
    $user->user_badge()->attach($id_badge,['is_aktif' => 1, 'id_tahun_ajaran' => $id_tahun_ajaran]);

    return true;
}