<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Kelas;
use App\Models\DokumenDitugaskan;
use App\Models\Score;
use App\Models\UserBadge;

class TahunAjaran extends Model
{
    use HasFactory;
    protected $table = 'tahun_ajaran';
    protected $primaryKey = 'id_tahun_ajaran';
    
    protected $fillable = [
        'tahun_ajaran',
        'status',
    ];

    public function scopeTahunAktif($query)
    {
        return $query->where('status', 1);
    }

    public function dokumen_ditugaskan()
    {
        return $this->hasMany(DokumenDitugaskan::class, 'id_tahun_ajaran');
    }


    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'id_tahun_ajaran');
    }

    public function score()
    {
        return $this->hasMany(Score::class, 'id_tahun_ajaran');
    }

    public function user_badge()
    {
        return $this->hasMany(UserBadge::class, 'id_tahun_ajaran');
    }
}