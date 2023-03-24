<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    use HasFactory;
    protected $table = 'mata_kuliah';
    protected $primaryKey = 'kode_matkul';
    public $incrementing = false;
    
    protected $fillable = [
        'kode_matkul',
        'nama_matkul',
        'bobot_sks',
        'praktikum',
    ];

    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'kode_matkul');
    }
}