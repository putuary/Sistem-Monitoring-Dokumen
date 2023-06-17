<?php

namespace App\Models;
use App\Models\User;
use App\Models\DokumenMatkul;
use App\Models\DokumenDitugaskan;
use App\Models\DokumenKelas;
use App\Models\Score;
use App\Models\UserBadge;
use App\Models\LeaderBoard;

use Carbon\Carbon;

class Gamifikasi
{
    protected static $poin_ontime = 100;
    protected static $poin_terlambat = -25;
    protected static $poin_dokumen_salah = -50;
    protected static $poin_dokumen_kosong = -100;
    protected static $poin_bonus1 = 50;
    protected static $poin_bonus2 = 30;
    protected static $poin_bonus3 = 15;

    public static function getPointDokumenSalah() {
        return self::$poin_dokumen_salah;
    }

    public static function getPointDokumenKosong() {
        return self::$poin_dokumen_kosong;
    }

    public function giveBadge($id_user, $id_badge, $id_tahun_ajaran) {
        $user=User::find($id_user);
        $user->user_badge()->attach($id_badge,['is_aktif' => 1, 'id_tahun_ajaran' => $id_tahun_ajaran]);
    
        return true;
    }

    public static function submitScore($tenggat_waktu, $waktu_pengumpulan_dokumen) {
        $tenggat=Carbon::parse($tenggat_waktu);
        $waktu_pengumpulan=Carbon::parse($waktu_pengumpulan_dokumen);
        if($waktu_pengumpulan->isBefore($tenggat)) {
            $poin=self::$poin_ontime;
        } else {
            $poin=self::$poin_terlambat;
        }
        return $poin;
    }

    public static function submitScoreWithBonus($tenggat, $waktu_pengumpulan, $isDokumenMatkul, $id_dokumen_terkumpul, $id_dokumen_ditugaskan) {
        $tenggat=Carbon::parse($tenggat);
        $waktu_pengumpulan=Carbon::parse($waktu_pengumpulan);
        if($waktu_pengumpulan->isBefore($tenggat)) {
            $poin=self::$poin_ontime;
        } else {
            $poin=self::$poin_terlambat;
        }
        
        $bonus=0;
        if($isDokumenMatkul) {
            $dokumen_matkul_terkumpul=DokumenMatkul::where('id_dokumen_ditugaskan', $id_dokumen_ditugaskan)->wherenotnull('waktu_pengumpulan')->orderBy('waktu_pengumpulan', 'asc')->get();
            foreach ($dokumen_matkul_terkumpul as $key => $item) {
                if($id_dokumen_terkumpul == $item->id_dokumen_matkul) {
                    if($key+1 == 1) {
                        $bonus+=self::$poin_bonus1;
                    } else if ($key+1 == 2) {
                        $bonus+=self::$poin_bonus2;
                    } else if ($key+1 == 3) {
                        $bonus+=self::$poin_bonus3;
                    }
                    break;
                }
            }
        } else {
            $dokumen_kelas_terkumpul=DokumenKelas::where('id_dokumen_ditugaskan', $id_dokumen_ditugaskan)->whereDoesntHave('note')->wherenotnull('waktu_pengumpulan')->orderBy('waktu_pengumpulan', 'asc')->get();
            foreach ($dokumen_kelas_terkumpul as $key => $item) {
                if($id_dokumen_terkumpul == $item->id_dokumen_kelas) {
                    if($key+1 == 1) {
                        $bonus+=self::$poin_bonus1;
                    } else if ($key+1 == 2) {
                        $bonus+=self::$poin_bonus2;
                    } else if ($key+1 == 3) {
                        $bonus+=self::$poin_bonus3;
                    }
                    break;
                }
            }
        }
    
        return [
            'poin'      => $poin,
            'bonus'     => ($bonus == 0 ? null : $bonus),
        ];
    }

    public static function updateBonusDokumen($id_dokumen_ditugaskan, $dikumpulkan_per) {
        if($dikumpulkan_per == 0) {
            $dokumen_matkul_terkumpul=DokumenMatkul::where('id_dokumen_ditugaskan', $id_dokumen_ditugaskan)->whereDoesntHave('note')->wherenotnull('waktu_pengumpulan')->orderBy('waktu_pengumpulan', 'asc')->get();
            // dd($dokumen_matkul_terkumpul);
            foreach ($dokumen_matkul_terkumpul as $key => $item) {
                if($key+1 == 1) {
                    $bonus=self::$poin_bonus1;
                } else if ($key+1 == 2) {
                    $bonus=self::$poin_bonus2;
                } else if ($key+1 == 3) {
                    $bonus=self::$poin_bonus3;
                } else {
                    $bonus=null;
                }
    
                $item->scores()->update([
                    'bonus' => $bonus,
                ]);
            }
        } else {
            $dokumen_kelas_terkumpul=DokumenKelas::where('id_dokumen_ditugaskan', $id_dokumen_ditugaskan)->wherenotnull('waktu_pengumpulan')->orderBy('waktu_pengumpulan', 'asc')->get();
        
            foreach ($dokumen_kelas_terkumpul as $key => $item) {
                if($key+1 == 1) {
                    $bonus=self::$poin_bonus1;
                } else if ($key+1 == 2) {
                    $bonus=self::$poin_bonus2;
                } else if ($key+1 == 3) {
                    $bonus=self::$poin_bonus3;
                } else {
                    $bonus=null;
                }
                
                $item->scores()->update([
                    'bonus' => $bonus,
                ]);
            }
        }
        return true;
    }

    public static function showRank($users) {
        $leaderboard=array();
        foreach($users as $user) {
            $late=$onTime=$sum_submited=$empty=$sum_poin=0;
            $task=count($user->score);
            // dd($user->score);
            foreach ($user->score as $score) {
                if(isset($score->scoreable->file_dokumen) && $score->scoreable->file_dokumen != null) {
                    $sum_submited++;
                    $waktu_pengumpulan=Carbon::parse($score->scoreable->waktu_pengumpulan);
                    $tenggat=Carbon::parse($score->scoreable->dokumen_ditugaskan->tenggat_waktu);
    
                    if($waktu_pengumpulan->isAfter($tenggat)) {
                        $late++;
                    } else {
                        $onTime++;
                    }
                } else {
                    $empty++;
                }
                
                $sum_poin+=$score->poin+$score->bonus;
            }
            try {
                $percent=round(($sum_submited/$task)*100, 1);
            } catch (\Throwable $th) {
                $percent=0;
            }
            
            try {
                $score=round($sum_poin/$task, 1);
            } catch (\Throwable $th) {
                $score=0;
            }
    
            $leaderboard[]=(object) [
                'user' => $user,
                'late'  => $late,
                'onTime'=> $onTime,
                'empty' => $empty,
                'total_terkumpul' => $sum_submited,
                'task'  => $task,
                'percent' => $percent,
                'total_poin' => $sum_poin,
                'score' => $score,
            ];
            
        }
        // dd($leaderboard);
    
        usort($leaderboard, function($a, $b) {
            return $b->score <=> $a->score;
        });
    
        // dd($leaderboard);
        
        return $leaderboard;
    }

    public static function storeResultBadge($id_tahun_ajaran) {
        DokumenDitugaskan::where('id_tahun_ajaran', $id_tahun_ajaran)->update(['pengumpulan' => 0]);
        Score::whereHas('scoreable', function($query) {
            $query->whereNull('file_dokumen');
        })->scoreTahun($id_tahun_ajaran)->update(['poin'  => self::$poin_dokumen_kosong]);

        // Score::whereNull('poin')->where('id_tahun_ajaran', $request->id_tahun_ajaran)->update(['poin'  => -100]);

        $users=User::with(['score' => function($query) {
            $query->with('scoreable')->whereHas('tahun_ajaran', function($query) {
                $query->tahunAktif();
            });
        }])->whereHas('dosen_kelas', function($query) {
            $query->kelasAktif();
        })->where('role', '!=', 'admin')->get();

        // dd($users);

        $tahun_aktif_badge=UserBadge::where('is_aktif', 1)->first();

        if($id_tahun_ajaran == ($tahun_aktif_badge->id_tahun_ajaran ?? null)) {
            UserBadge::where('id_tahun_ajaran', $id_tahun_ajaran)->delete();
        }

        $lecturers=self::showRank($users);

        UserBadge::where('is_aktif', 1)->update(['is_aktif' => 0]);
        LeaderBoard::where('id_tahun_ajaran', $id_tahun_ajaran)->delete();
        foreach ($lecturers as $key => $lecturer) {
            LeaderBoard::create([
                'id_dosen'        => $lecturer->user->id,
                'id_tahun_ajaran' => $id_tahun_ajaran,
                'tepat_waktu'     => $lecturer->onTime,
                'terlambat'       => $lecturer->late,
                'kosong'          => $lecturer->empty,
                'skor'            => $lecturer->score,
            ]);
            
            if($key === 0) {
                self::giveBadge($lecturer->user->id, 1, $id_tahun_ajaran);
            } else if($key == 1) {
                self::giveBadge($lecturer->user->id, 2, $id_tahun_ajaran);
            } else if($key == 2) {
                self::giveBadge($lecturer->user->id, 3, $id_tahun_ajaran);
            } else if($key == (count($lecturers) - 1)) {
                self::giveBadge($lecturer->user->id, 4, $id_tahun_ajaran);
            } else if($key == (count($lecturers) - 2)) {
                self::giveBadge($lecturer->user->id, 5, $id_tahun_ajaran);
            } else if($key == (count($lecturers) - 3)) {
                self::giveBadge($lecturer->user->id, 6, $id_tahun_ajaran);
            }

            if($lecturer->onTime == $lecturer->task) {
                self::giveBadge($lecturer->user->id, 7, $id_tahun_ajaran);
            }

            if($lecturer->late >= (0.5 * $lecturer->task)) {
                self::giveBadge($lecturer->user->id, 8, $id_tahun_ajaran);
            }

            if($lecturer->empty >= 1) {
                self::giveBadge($lecturer->user->id, 9, $id_tahun_ajaran);
            }
        }
        return true;
    }
}