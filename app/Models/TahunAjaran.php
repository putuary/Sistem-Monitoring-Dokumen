<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Kelas;
use App\Models\DokumenDitugaskan;

class TahunAjaran extends Model
{
    use HasFactory;
    protected $table = 'tahun_ajaran';
    protected $primaryKey = 'id_tahun_ajaran';
    
    protected $fillable = [
        'tahun_ajaran',
        'status',
    ];

    public function dokumen_ditugaskan()
    {
        return $this->hasMany(DokumenDitugaskan::class, 'id_tahun_ajaran');
    }


    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'id_tahun_ajaran');
    }
}