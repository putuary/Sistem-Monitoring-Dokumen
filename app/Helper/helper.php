<?php 

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