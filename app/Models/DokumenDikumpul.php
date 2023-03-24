<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DokumenDitugaskan;
use App\Models\Kelas;

class DokumenDikumpul extends Model
{
    use HasFactory;
    protected $table = 'dokumen_dikumpul';
    protected $primaryKey = 'id_dokumen_dikumpul';
    
    protected $fillable = [
        'id_dokumen_ditugaskan',
        'kode_kelas',
        'file_dokumen',
        'waktu_pengumpulan',
    ];
}