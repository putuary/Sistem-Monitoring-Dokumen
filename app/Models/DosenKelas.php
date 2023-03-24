<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DosenKelas extends Model
{
    use HasFactory;
    
    /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
    public $table = 'dosen_kelas';
    /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
    protected $fillable = [
        'id_dosen','kode_kelas'
    ];
}