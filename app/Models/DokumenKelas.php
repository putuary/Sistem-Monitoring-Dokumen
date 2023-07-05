<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DokumenDitugaskan;
use App\Models\Kelas;
use App\Models\Score;
use App\Models\CatatanPenolakan;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class DokumenKelas extends Model
{
    use HasFactory;
    protected $table = 'dokumen_kelas';
    protected $primaryKey = 'id_dokumen_kelas';
    public $incrementing = false;
    public $timestamps = false;
    
    protected $fillable = [
        'id_dokumen_kelas',
        'id_dokumen_ditugaskan',
        'kode_kelas',
        'file_dokumen',
        'waktu_pengumpulan',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id_dokumen_kelas = IdGenerator::generate(['table' => 'dokumen_kelas', 'field' => 'id_dokumen_kelas', 'length' => 10, 'prefix' => 'DK']);
        });
    }
    
    public function scopeFilter($query, $filter)
    {
        if(isset($filter)) {
            if($filter == 'terkumpul') {
                return $query->whereNotNull('file_dokumen');
            } else if($filter == 'tepat_waktu') {
                return $query->whereNotNull('file_dokumen')->where('waktu_pengumpulan', '<=', function($query) {
                    $query->select('tenggat_waktu')->from('dokumen_ditugaskan')->whereColumn('id_dokumen_ditugaskan', 'dokumen_kelas.id_dokumen_ditugaskan');
                });
            } else if($filter == 'terlambat') {
                return $query->whereNotNull('file_dokumen')->where('waktu_pengumpulan', '>', function($query) {
                    $query->select('tenggat_waktu')->from('dokumen_ditugaskan')->whereColumn('id_dokumen_ditugaskan', 'dokumen_kelas.id_dokumen_ditugaskan');
                });
            } else if($filter == 'belum_terkumpul') {
                return $query->whereNull('file_dokumen');
            } else if($filter == 'melewati_tenggat_waktu') {
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

    public function dokumen_ditugaskan()
    {
        return $this->belongsTo(DokumenDitugaskan::class, 'id_dokumen_ditugaskan');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kode_kelas');
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