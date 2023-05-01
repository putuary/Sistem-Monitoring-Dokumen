<?php

namespace App\Models;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'nama_badge',
        'gambar',
        'deskripsi',
    ];

    public function user_badge()
    {
        return $this->belongsToMany(User::class, 'user_badges', 'id_badge', 'id_user');
    }
}