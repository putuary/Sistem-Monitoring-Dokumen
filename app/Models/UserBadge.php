<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TahunAjaran;

class UserBadge extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user',
        'id_badge',
        'is_aktif',
        'id_tahun_ajaran',
    ];

    public function scopeBadgeTahunAktif($query)
    { 
        return $query->whereHas('tahun_ajaran', function($query) {
            $query->tahunAktif();
        });
    }

    public function scopeBadgeTahun($query, $id_tahun_ajaran)
    {
        if(isset($id_tahun_ajaran)) {
            return $query->where('id_tahun_ajaran', $id_tahun_ajaran);
        } return $query->badgeTahunAktif();
    }

    public function tahun_ajaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'id_tahun_ajaran');
    }
}