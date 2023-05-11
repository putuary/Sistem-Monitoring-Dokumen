<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\TahunAjaran;
use App\Models\MatkulDibuka;
use Illuminate\Support\Facades\Auth;
use App\Models\DokumenKelas;
use App\Models\DokumenMatkul;
use App\Models\Score;

class Kelas extends Model
{
    use HasFactory;
    protected $table = 'kelas';
    protected $primaryKey = 'kode_kelas';
    public $timestamps = false;
    
    protected $fillable = [
        'nama_kelas',
        'id_matkul_dibuka',
        'id_tahun_ajaran',
    ];

    public function scopeSearchKelas($query, $search)
    {
        return $query->whereHas('matkul', function($query) use ($search) {
            $query->where('nama_matkul', 'like', '%'.$search.'%');
        })->orWhere('nama_kelas', 'like', '%'.$search.'%');
    }

    public function scopeKelasAktif($query)
    {
        return $query->whereHas('tahun_ajaran', function($query) {
            $query->where('is_aktif', 1);
        });
    }

    public function scopeKelasTahun($query, $id_tahun_ajaran)
    {
        if(isset($id_tahun_ajaran)) {
            return $query->whereHas('tahun_ajaran', function($query) use ($id_tahun_ajaran) {
                $query->where('id_tahun_ajaran', $id_tahun_ajaran);
            });   
        }
        return $query->kelasAktif();
    }

    public function scopeKelasMatkulTahun($query, $kode_matkul, $id_tahun_ajaran)
    {
        return $query->where('kode_matkul', $kode_matkul)
        ->where('id_tahun_ajaran', $id_tahun_ajaran);
    }

    public function scopeKelasDiampu($query)
    {
        return $query->whereHas('dosen_kelas', function($query) {
            $query->where('id_dosen', Auth::user()->id);
        });
    }
        

    public function dokumen_kelas()
    {
        return $this->hasMany(DokumenKelas::class, 'kode_kelas');
    }
    
    public function kelas_dokumen_matkul()
    {
        return $this->belongsToMany(DokumenMatkul::class, 'kelas_dokumen_matkul', 'kode_kelas', 'id_dokumen_matkul');
    }

    public function matkul()
    {
        return $this->belongsTo(MatkulDibuka::class, 'id_matkul_dibuka');
    }

    public function tahun_ajaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'id_tahun_ajaran');
    }

    public function dosen_kelas()
    {
        return $this->belongsToMany(User::class, 'dosen_kelas', 'kode_kelas', 'id_dosen');
    }

    public function score()
    {
        return $this->hasMany(Score::class, 'kode_kelas');
    }
}