<?php

namespace App\Models;
use App\Models\User;
use App\Models\TahunAjaran;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaderBoard extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'id_dosen',
        'id_tahun_ajaran',
        'tepat_waktu',
        'terlambat',
        'kosong',
        'skor',
    ];

    public function scopeLeaderboardAktif($query)
    {
        return $query->whereHas('tahun_ajaran', function($query) {
            $query->where('is_aktif', 1);
        });
    }

    public function scopeLeaderboardTahun($query, $id_tahun_ajaran)
    {
        if($id_tahun_ajaran != null) {
            return $query->where('id_tahun_ajaran', $id_tahun_ajaran);
        }
        return $query->leaderboardAktif();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_dosen', 'id');
    }

    public function tahun_ajaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'id_tahun_ajaran', 'id_tahun_ajaran');
    }
}