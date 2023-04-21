<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DokumenDitugaskan;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class DokumenPerkuliahan extends Model
{
    use HasFactory;
    protected $table = 'dokumen_perkuliahan';
    protected $primaryKey = 'id_dokumen';
    public $incrementing = false;
    
    protected $fillable = [
        'id_dokumen',
        'nama_dokumen',
        'tenggat_waktu_default',
        'dikumpulkan_per',
        'template',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id_dokumen = IdGenerator::generate(['table' => 'dokumen_perkuliahan', 'field' => 'id_dokumen', 'length' => 10, 'prefix' => 'DP']);
        });
    }

    public function dokumen_ditugaskan()
    {
        return $this->hasMany(DokumenDitugaskan::class, 'id_dokumen');
    }
}