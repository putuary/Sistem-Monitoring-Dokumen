<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DokumenDitugaskan;
use App\Models\Kelas;

class DokumenKelas extends Model
{
    use HasFactory;
    protected $table = 'dokumen_kelas';
    protected $primaryKey = 'id_dokumen_kelas';
    
    protected $fillable = [
        // 'id_dokumen_dikumpul',
        'id_dokumen_ditugaskan',
        'kode_kelas',
        'file_dokumen',
        'waktu_pengumpulan',
    ];

    public function dokumen_ditugaskan()
    {
        return $this->belongsTo(DokumenDitugaskan::class, 'id_dokumen_ditugaskan');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kode_kelas');
    }
}