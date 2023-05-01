<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\TahunAjaran;

class Score extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_dosen',
        'id_tahun_ajaran',
        'scoreable_id',
        'scoreable_type',
        'score',
        'status',
    ];

    public function scopeScoreTahunAktif($query)
    { 
        return $query->whereHas('tahun_ajaran', function($query) {
            $query->tahunAktif();
        });
    }

    public function scopeScoreTahun($query, $id_tahun_ajaran)
    {
        if(isset($id_tahun_ajaran)) {
            return $query->where('id_tahun_ajaran', $id_tahun_ajaran);
        } return $query->scoreTahunAktif();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_dosen');
    }

    public function tahun_ajaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'id_tahun_ajaran');
    }

    public function scoreable()
    {
        return $this->morphTo();
    }
}