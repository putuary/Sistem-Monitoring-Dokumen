<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\DokumenDitugaskan;
use App\Models\Kelas;
use App\Models\TahunAjaran;

class Score extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user',
        'id_dokumen_ditugaskan',
        'kode_kelas',
        'id_tahun_ajaran',
        'poin',
        'tepat_waktu',
        'terlambat',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function dokumen_ditugaskan()
    {
        return $this->belongsTo(DokumenDitugaskan::class, 'id_dokumen_ditugaskan');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kode_kelas');
    }

    public function tahun_ajaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'id_tahun_ajaran');
    }
}