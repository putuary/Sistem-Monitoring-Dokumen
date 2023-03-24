<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AktifRole extends Model
{
    use HasFactory;
    protected $table = 'aktif_role';
    public $timestamps = false;
    
    protected $fillable = [
        'id_user',
        'is_dosen',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}