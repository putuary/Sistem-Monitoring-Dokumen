<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DokumenPerkuliahan;
use App\Models\TahunAjaran;
use App\Models\DokumenKelas;
use App\Models\DokumenMatkul;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class DokumenDitugaskan extends Model
{
    use HasFactory;
    protected $table = 'dokumen_ditugaskan';
    protected $primaryKey = 'id_dokumen_ditugaskan';
    public $incrementing = false;
    
    protected $fillable = [
        'id_dokumen_ditugaskan',
        'id_tahun_ajaran',
        'id_dokumen',
        'nama_dokumen',
        'tenggat_waktu',
        'pengumpulan',
        'dikumpulkan_per',
        'dikumpul',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id_dokumen_ditugaskan = IdGenerator::generate(['table' => 'dokumen_ditugaskan', 'field' => 'id_dokumen_ditugaskan', 'length' => 10, 'prefix' => 'DT']);
        });
    }

    public function scopeSearchDokumen($query, $search)
    {
        return $query->whereHas('dokumen_perkuliahan', function($query) use ($search) {
            $query->where('nama_dokumen', 'like', '%'.$search.'%');
        });
    }

    public function scopeDokumenAktif($query)
    {
        return $query->whereHas('tahun_ajaran', function($query) {
            $query->where('is_aktif', 1);
        });
    }

    public function scopeDokumenTahun($query, $id_tahun_ajaran)
    {
        if(isset($id_tahun_ajaran)) {
            return $query->whereHas('tahun_ajaran', function($query) use ($id_tahun_ajaran) {
                $query->where('id_tahun_ajaran', $id_tahun_ajaran);
            });   
        }
        return $query->dokumenAktif();
    }

    public function dokumen_perkuliahan()
    {
        return $this->belongsTo(DokumenPerkuliahan::class, 'id_dokumen');
    }

    public function tahun_ajaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'id_tahun_ajaran');
    }

    public function dokumen_kelas()
    {
        return $this->hasMany(DokumenKelas::class, 'id_dokumen_ditugaskan');
    }

    public function dokumen_matkul()
    {
        return $this->hasMany(DokumenMatkul::class, 'id_dokumen_ditugaskan');
    }


}