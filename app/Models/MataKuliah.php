<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Kelas;
use App\Models\DokumenMatkul;

class MataKuliah extends Model
{
    use HasFactory;
    protected $table = 'mata_kuliah';
    protected $primaryKey = 'kode_matkul';
    public $incrementing = false;
    public $timestamps = false;
    
    protected $fillable = [
        'kode_matkul',
        'nama_matkul',
        'bobot_sks',
        'praktikum',
    ];

    public function scopeMatkulDibuka($query, $jenis)
    {
        if($jenis == 'Ganjil') {
            return $query->whereRaw("SUBSTRING(kode_matkul, 4, 1) = '1'")->orWhereRaw("SUBSTRING(kode_matkul, 4, 1) = '0'");
        } else if($jenis == 'Genap') {
            return $query->whereRaw("SUBSTRING(kode_matkul, 4, 1) = '2'")->orWhereRaw("SUBSTRING(kode_matkul, 4, 1) = '0'");
        } else {
            return $query;
        }
    }

    public function scopeMatkulKelas($query, $kode_matkul, $id_tahun_ajaran)
    {
        return $query->whereHas('kelas', function($query) use ($kode_matkul, $id_tahun_ajaran) {
            $query->where('kode_matkul', $kode_matkul)
            ->where('id_tahun_ajaran', $id_tahun_ajaran);
        });
    }

    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'kode_matkul');
    }

    public function dokumen_matkul()
    {
        return $this->hasMany(DokumenMatkul::class, 'kode_matkul');
    }
}