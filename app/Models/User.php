<?php

namespace App\Models;
use App\Models\AktifRole;
use App\Models\Kelas;
use App\Models\Score;
use App\Models\Badge;
use App\Models\LeaderBoard;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'email',
        'password',
        'avatar',
        'role',
    ];

    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function scopeAktifBadge($query) {
        return $query->with(['user_badge' => function($query) {
            $query->where('is_aktif', 1);
        }])->where('id', Auth::user()->id);
    }

    public function dosen_kelas()
    {
        return $this->belongsToMany(Kelas::class, 'dosen_kelas', 'id_dosen', 'kode_kelas');
    }

    public function aktif_role()
    {
        return $this->hasOne(AktifRole::class, 'id_user');
    }

    public function score()
    {
        return $this->hasMany(Score::class, 'id_dosen');
    }

    public function leader_board()
    {
        return $this->hasMany(LeaderBoard::class, 'id_dosen');
    }

    public function user_badge()
    {
        return $this->belongsToMany(Badge::class, 'user_badges', 'id_user', 'id_badge');
    }
}