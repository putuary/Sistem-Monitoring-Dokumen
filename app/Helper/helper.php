<?php

use Carbon\Carbon;
use App\Models\AktifRole;
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
   

function isKelasDiampu($kode_kelas, $id_dosen) {
    $kelas=Kelas::where('kode_kelas', $kode_kelas)->whereHas('dosen_kelas', function($query) use ($id_dosen) {
        $query->where('id_dosen', $id_dosen);
    })->first();
    
    if($kelas) {
        return true;
    }
    return false;
}

function kelasSummary($dokumen_dikumpul) {
    $terlewat=0;
    $telat=0;
    $terkumpul=0;
    $ditugaskan=0;

    foreach($dokumen_dikumpul as $dokumen) {
        $tenggat=Carbon::parse($dokumen->dokumen_ditugaskan->tenggat_waktu);
        $waktu_pengumpulan=Carbon::parse($dokumen->waktu_pengumpulan);
        
        if($waktu_pengumpulan->isAfter($tenggat)) {
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

function dokumenSummary($dokumen_dikumpul) {
    $terlewat=0;
    $telat=0;
    $terkumpul=0;
    $ditugaskan=0;

    foreach($dokumen_dikumpul as $dokumen) {
        $tenggat=Carbon::parse($dokumen->dokumen_ditugaskan->tenggat_waktu);
        $waktu_pengumpulan=Carbon::parse($dokumen->waktu_pengumpulan);
        
        if($waktu_pengumpulan->isAfter($tenggat)) {
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
        return '/Dokumen_Perkuliahan/'.str_replace('/','-',$tahun_ajaran).'/Dokumen_Mata_Kuliah/'.$matkul.'/';
    }
    return '/Dokumen_Perkuliahan/'.str_replace('/','-',$tahun_ajaran).'/Dokumen_Kelas/'.$matkul.'/'.$kelas;
}