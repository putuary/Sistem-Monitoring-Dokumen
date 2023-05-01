<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Kelas;
use App\Models\DokumenMatkul;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class MatkulDibuka extends Model
{
    use HasFactory;
    protected $table = 'matkul_dibuka';
    protected $primaryKey = 'id_matkul_dibuka';
    public $incrementing = false;
    
    protected $fillable = [
        'id_matkul_dibuka',
        'kode_matkul',
        'nama_matkul',
        'bobot_sks',
        'praktikum',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id_matkul_dibuka = IdGenerator::generate(['table' => 'matkul_dibuka', 'field' => 'id_matkul_dibuka', 'length' => 10, 'prefix' => 'MK']);
        });
    }
}