<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatatanPenolakan extends Model
{
    use HasFactory;
    protected $table = 'catatan_penolakan';

    protected $fillable = [
        'noteable_id',
        'noteable_type',
        'isi_catatan',
        'is_aktif',
    ];

    public function noteable()
    {
        return $this->morphTo();
    }
}