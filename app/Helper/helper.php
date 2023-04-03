<?php

use Carbon\Carbon;
use App\Models\AktifRole;
use App\Models\DokumenKelas;
use App\Models\DokumenMatkul;
use App\Models\Kelas;

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
    
    $persentase_dikumpul=round(($terkumpul/$ditugaskan)*100, 1);

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
    
    $persentase_dikumpul=round(($terkumpul/$ditugaskan)*100, 1);

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
        return '/Dokumen_Perkuliahan/'.str_replace('/','-',$tahun_ajaran).'/'.$matkul.'/';
    }
    return '/Dokumen_Perkuliahan/'.str_replace('/','-',$tahun_ajaran).'/'.$matkul.'/'.$kelas;
}

function countStatusDokumen($filter=null, $tahun_ajaran=null) {
    $dokumen_kelas=DokumenKelas::with(['dokumen_ditugaskan', 'kelas'])->whereHas('dokumen_ditugaskan', function($query) use ($tahun_ajaran) {
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

function showProfilDokumen($id_dokumen) {
    $dokumen=DokumenMatkul::where('id_dokumen_matkul', $id_dokumen)->first();

    return (object) [
        'nama_matkul' => $dokumen->matkul->nama_matkul,
        'dosen'       => dosenKelas($dokumen->kelas_dokumen_matkul),
    ];
}