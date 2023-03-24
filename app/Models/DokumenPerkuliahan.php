<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DokumenDitugaskan;

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

    public function dokumen_ditugaskan()
    {
        return $this->hasMany(DokumenDitugaskan::class, 'id_dokumen');
    }
}