<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Kelas;
use App\Models\DokumenMatkul;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use App\Models\TahunAjaran;

class MatkulDibuka extends Model
{
    use HasFactory;
    protected $table = 'matkul_dibuka';
    protected $primaryKey = 'id_matkul_dibuka';
    public $incrementing = false;
    
    protected $fillable = [
        'id_matkul_dibuka',
        'kode_matkul',
        'id_tahun_ajaran',
        'nama_matkul',
        'bobot_sks',
        'praktikum',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id_matkul_dibuka = IdGenerator::generate(['table' => 'matkul_dibuka', 'field' => 'id_matkul_dibuka', 'length' => 10, 'prefix' => 'MK']);
        });
    }

    public function scopeMatkulAktif($query)
    {
        return $query->whereHas('tahun_ajaran', function($query) {
            $query->where('is_aktif', 1);
        });
    }

    public function scopeMatkulTahun($query, $id_tahun_ajaran)
    {
        if(isset($id_tahun_ajaran)) {
            return $query->whereHas('tahun_ajaran', function($query) use ($id_tahun_ajaran) {
                $query->where('id_tahun_ajaran', $id_tahun_ajaran);
            });   
        }
        return $query->matkulAktif();
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
        return $this->hasMany(Kelas::class, 'id_matkul_dibuka');
    }

    public function dokumen_matkul()
    {
        return $this->hasMany(DokumenMatkul::class, 'id_matkul_dibuka');
    }

    public function tahun_ajaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'id_tahun_ajaran');
    }
}