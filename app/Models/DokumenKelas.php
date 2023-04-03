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
            } else if( $filter == 'belum_terkumpul') {
                return $query->whereNull('file_dokumen');
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
}