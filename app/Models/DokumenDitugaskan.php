<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DokumenPerkuliahan;
use App\Models\TahunAjaran;

class DokumenDitugaskan extends Model
{
    use HasFactory;
    protected $table = 'dokumen_ditugaskan';
    protected $primaryKey = 'id_dokumen_ditugaskan';
    public $incrementing = false;
    
    protected $fillable = [
        'id_dokumen_ditugaskan',
        'id_dokumen',
        'id_tahun_ajaran',
        'tenggat_waktu',
        'pengumpulan',
    ];

    public function dokumen_perkuliahan()
    {
        return $this->belongsTo(DokumenPerkuliahan::class, 'id_dokumen');
    }

    public function tahun_ajaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'id_tahun_ajaran');
    }

    public function dokumen_dikumpul()
    {
        return $this->belongsToMany(Kelas::class, 'dokumen_dikumpul', 'id_dokumen_ditugaskan', 'kode_kelas')->withPivot(['file_dokumen', 'waktu_pengumpulan']);
    }


}