<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;
    protected $table = 'kelas';
    protected $primaryKey = 'kode_kelas';
    
    protected $fillable = [
        'nama_kelas',
        'kode_matkul',
        'id_tahun_ajaran',
    ];

    public function dokumen_dikumpul()
    {
        return $this->belongsToMany(DokumenDitugaskan::class, 'dokumen_dikumpul', 'kode_kelas', 'id_dokumen_ditugaskan')->withPivot(['file_dokumen', 'waktu_pengumpulan']);
    }

    public function matkul()
    {
        return $this->belongsTo(MataKuliah::class, 'kode_matkul');
    }

    public function tahun_ajaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'id_tahun_ajaran');
    }

    public function dosen_kelas()
    {
        return $this->belongsToMany(User::class, 'dosen_kelas', 'kode_kelas', 'id_dosen');
    }
}