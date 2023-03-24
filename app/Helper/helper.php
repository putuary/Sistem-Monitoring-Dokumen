<?php

use Carbon\Carbon;

function nameRoles($role) {
    switch($role) {
        case 'superRole':
            return ['kaprodi', 'gkmp'];
        break;
        case 'midleRole':
            return ['kaprodi', 'gkmp', 'admin'];
        break;
        case 'allRole':
            return ['kaprodi', 'gkmp', 'admin', 'dosen'];
        break;
        default:
            return ['kaprodi', 'gkmp', 'admin', 'dosen'];
    }
}


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

function createTenggat($date, $default) {
    $tenggat=Carbon::createFromLocaleIsoFormat('D MMMM YYYY', 'id', $date, 'Asia/Jakarta')->addWeek($default)->format('Y-m-d');
    $dt=Carbon::parse($tenggat);
    
    return $dt->endOfWeek()->addDay(-2);
}

function showTenggat($date) {
    $tenggat=Carbon::parse($date)->locale('id')->isoFormat('LLLL');
    
    return $tenggat;
}