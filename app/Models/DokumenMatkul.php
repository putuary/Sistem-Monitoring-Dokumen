<?php

namespace App\Models;
use App\Models\DokumenDitugaskan;
use App\Models\MataKuliah;
use App\Models\Kelas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenMatkul extends Model
{
    use HasFactory;
    protected $table = 'dokumen_matkul';
    protected $primaryKey = 'id_dokumen_matkul';
    
    protected $fillable = [
        // 'id_dokumen_dikumpul',
        'id_dokumen_ditugaskan',
        'kode_matkul',
        'file_dokumen',
        'waktu_pengumpulan',
    ];

    public function dokumen_ditugaskan()
    {
        return $this->belongsTo(DokumenDitugaskan::class, 'id_dokumen_ditugaskan');
    }

    public function kelas_dokumen_matkul()
    {
        return $this->belongsToMany(Kelas::class, 'kelas_dokumen_matkul', 'id_dokumen_matkul', 'kode_kelas');
    }

    public function matkul()
    {
        return $this->belongsTo(MataKuliah::class, 'kode_matkul');
    }
}