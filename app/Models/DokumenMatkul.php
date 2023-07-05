<?php

namespace App\Models;
use App\Models\DokumenDitugaskan;
use App\Models\Kelas;
use App\Models\Score;
use App\Models\CatatanPenolakan;
use Haruncpi\LaravelIdGenerator\IdGenerator;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenMatkul extends Model
{
    use HasFactory;
    protected $table = 'dokumen_matkul';
    protected $primaryKey = 'id_dokumen_matkul';
    public $incrementing = false;
    public $timestamps = false;
    
    
    protected $fillable = [
        'id_dokumen_matkul',
        'id_dokumen_ditugaskan',
        'id_matkul_dibuka',
        'file_dokumen',
        'waktu_pengumpulan',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id_dokumen_matkul = IdGenerator::generate(['table' => 'dokumen_matkul', 'field' => 'id_dokumen_matkul', 'length' => 10, 'prefix' => 'DM']);
        });
    }

    public function scopeFilter($query, $filter)
    {
        if(isset($filter)) {
            if($filter == 'terkumpul') {
                return $query->whereNotNull('file_dokumen');
            } else if($filter == 'tepat_waktu') {
                return $query->whereNotNull('file_dokumen')->where('waktu_pengumpulan', '<=', function($query) {
                    $query->select('tenggat_waktu')->from('dokumen_ditugaskan')->whereColumn('id_dokumen_ditugaskan', 'dokumen_matkul.id_dokumen_ditugaskan');
                });
            } else if($filter == 'terlambat') {
                return $query->whereNotNull('file_dokumen')->where('waktu_pengumpulan', '>', function($query) {
                    $query->select('tenggat_waktu')->from('dokumen_ditugaskan')->whereColumn('id_dokumen_ditugaskan', 'dokumen_matkul.id_dokumen_ditugaskan');
                });
            } else if( $filter == 'belum_terkumpul') {
                return $query->whereNull('file_dokumen');
            } else if( $filter == 'melewati_tenggat_waktu') {
                return $query->whereNull('file_dokumen')->whereHas('dokumen_ditugaskan', function($query) {
                    $query->where('tenggat_waktu', '<', now());
                });
            } else if( $filter=="mendekati_tenggat_waktu") {
                return $query->whereNull('file_dokumen')->whereHas('dokumen_ditugaskan', function($query) {
                    $query->where('tenggat_waktu', '>=', now())
                          ->whereRaw('tenggat_waktu - INTERVAL 1 WEEK <= NOW()');
                });
            }
        }
        return $query->whereNotNull('file_dokumen');
    }

    public function scopeDokumenKelas($query, $kode_kelas)
    {
        return $query->whereHas('kelas_dokumen_matkul', function($query) use ($kode_kelas) {
            $query->where('kelas_dokumen_matkul.kode_kelas', $kode_kelas);
        });
    }

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
        return $this->belongsTo(MatkulDibuka::class, 'id_matkul_dibuka');
    }

    public function scores()
    {
        return $this->morphMany(Score::class, 'scoreable');
    }

    public function note()
    {
        return $this->morphOne(CatatanPenolakan::class, 'noteable');
    }
}