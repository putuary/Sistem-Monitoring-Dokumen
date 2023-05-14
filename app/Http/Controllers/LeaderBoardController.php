<?php

namespace App\Http\Controllers;

use App\Models\Score;
use App\Models\TahunAjaran;
use App\Models\User;
use App\Models\UserBadge;
use App\Models\DokumenDitugaskan;
use App\Models\LeaderBoard;
use Illuminate\Http\Request;

class LeaderBoardController extends Controller
{
    public static function storeResultBadge($id_tahun_ajaran) {
        DokumenDitugaskan::where('id_tahun_ajaran', $id_tahun_ajaran)->update(['pengumpulan' => 0]);
        Score::whereHas('scoreable', function($query) {
            $query->whereNull('file_dokumen');
        })->scoreTahun($id_tahun_ajaran)->update(['poin'  => -100]);

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

        $lecturers=showRank($users);

        UserBadge::where('is_aktif', 1)->update(['is_aktif' => 0]);
        LeaderBoard::where('id_tahun_ajaran', $id_tahun_ajaran)->delete();
        foreach ($lecturers as $key => $lecturer) {
            LeaderBoard::create([
                'id_dosen'        => $lecturer->user->id,
                'id_tahun_ajaran' => $id_tahun_ajaran,
                'tepat_waktu'     => $lecturer->onTime,
                'terlambat'       => $lecturer->late,
                'kosong'          => $lecturer->empty,
                'skor'            => $lecturer->point,
            ]);
            
            if($key === 0) {
                giveBadge($lecturer->user->id, 1, $id_tahun_ajaran);
            } else if($key == 1) {
                giveBadge($lecturer->user->id, 2, $id_tahun_ajaran);
            } else if($key == 2) {
                giveBadge($lecturer->user->id, 3, $id_tahun_ajaran);
            } else if($key == (count($lecturers) - 1)) {
                giveBadge($lecturer->user->id, 4, $id_tahun_ajaran);
            } else if($key == (count($lecturers) - 2)) {
                giveBadge($lecturer->user->id, 5, $id_tahun_ajaran);
            } else if($key == (count($lecturers) - 3)) {
                giveBadge($lecturer->user->id, 6, $id_tahun_ajaran);
            }

            if($lecturer->onTime == $lecturer->task) {
                giveBadge($lecturer->user->id, 7, $id_tahun_ajaran);
            }

            if($lecturer->late >= (0.5 * $lecturer->task)) {
                giveBadge($lecturer->user->id, 8, $id_tahun_ajaran);
            }

            if($lecturer->empty >= 1) {
                giveBadge($lecturer->user->id, 9, $id_tahun_ajaran);
            }
        }
        return true;
    }

    public function index()
    {
        $tahun_aktif=TahunAjaran::tahunAktif()->first();
        $tahun_ajaran=TahunAjaran::orderBy('tahun_ajaran', 'desc')->get();

        $users=User::with(['score' => function($query) {
            $query->with(['scoreable' => function($query) {
                $query->with('dokumen_ditugaskan');
            }])->scoreTahun(request('tahun_ajaran'));
        }])->whereHas('dosen_kelas', function($query) {
            $query->kelasTahun(request('tahun_ajaran'));
        })->where('role', '!=', 'admin')->get();

        // dd($users[0]);

        $leaderboards=showRank($users);
        // dd($leaderboards[0]->score[0]->scoreable->nama_matkul);

       return view('user.leaderboard.tampil-leaderboard', ['tahun_aktif' => $tahun_aktif, 'tahun_ajaran' => $tahun_ajaran, 'leaderboards' => $leaderboards]);
    }

    // public function showUserScore() {
    //     $tahun_aktif=TahunAjaran::tahunAktif()->first();
    //     $tahun_ajaran=TahunAjaran::orderBy('tahun_ajaran', 'desc')->get();
        
    //     $scores = Score::with([
    //         'scoreable' => function (MorphTo $morphTo) {
    //             $morphTo->morphWith([
    //                 DokumenMatkul::class => ['matkul', 'dokumen_ditugaskan'],
    //                 DokumenKelas::class => ['dokumen_ditugaskan', 'kelas' => function($query) {
    //                     $query->with('matkul');
    //                 }],
    //             ]);
    //         }])->scoreTahun(request('tahun_ajaran'))
    //       ->where('id_dosen', Auth::user()->id)
    //       ->whereNotNull('poin')
    //       ->orderBy('updated_at', 'desc')->get();

    //     // dd($scores);
    //     return view('dosen.leaderboard.tampil-perolehan-score', ['tahun_aktif' => $tahun_aktif, 'tahun_ajaran' => $tahun_ajaran, 'scores' => $scores]);
    // }

    public function resultBadge(Request $request) {
        if($this->storeResultBadge($request->id_tahun_ajaran)) {

            return redirect('/badge')->with('success', 'Berhasil memberikan badge');
        }
    }

    // public function showResultBadges() {
    //     $tahun_aktif=TahunAjaran::tahunAktif()->first();
    //     $tahun_ajaran=TahunAjaran::orderBy('id_tahun_ajaran', 'desc')->get();
    //     $users=User::with(['user_badge' => function($query) use ($tahun_aktif) {
    //         $query->where('id_tahun_ajaran', (request('tahun_ajaran') ?? $tahun_aktif->id_tahun_ajaran));
    //         }, 'score' => function($query) {
    //         $query->scoreTahun(request('tahun_ajaran'));
    //     }])->whereHas('dosen_kelas', function($query) {
    //         $query->kelasTahun(request('tahun_ajaran'));
    //     })->where('role', '!=', 'admin')->get();

    //     $user_badges=showRank($users);
    //     // dd($user_badges);

    //     return view('user.leaderboard.tampil-perolehan-badge', ['tahun_aktif' => $tahun_aktif, 'tahun_ajaran' => $tahun_ajaran, 'user_badges' => $user_badges]);
    // }

    public function showResultBadge() {
        $tahun_aktif=TahunAjaran::tahunAktif()->first();
        $tahun_ajaran=TahunAjaran::orderBy('id_tahun_ajaran', 'desc')->get();
        $users=LeaderBoard::with(['user' => function($query) use($tahun_aktif) {
            $query->with(['user_badge' => function($query) use ($tahun_aktif) {
                $query->where('id_tahun_ajaran', (request('tahun_ajaran') ?? $tahun_aktif->id_tahun_ajaran));
            }]);
        }])->leaderboardTahun(request('tahun_ajaran'))->orderBy('skor', 'desc')->get();
        // dd(count($users !=0));

        return view('user.leaderboard.tampil-perolehan-badge', ['tahun_aktif' => $tahun_aktif, 'tahun_ajaran' => $tahun_ajaran, 'users' => $users]);
    }


    public function deleteBadge(Request $request) {
        DokumenDitugaskan::where('id_tahun_ajaran', $request->id_tahun_ajaran)->update(['pengumpulan' => 1]);
        LeaderBoard::where('id_tahun_ajaran', $request->id_tahun_ajaran)->delete();
        UserBadge::where('id_tahun_ajaran', $request->id_tahun_ajaran)->delete();
        Score::whereHas('scoreable', function($query) {
            $query->whereNull('file_dokumen')->whereDoesntHave('note');
        })->scoreTahun($request->tahun_ajaran)->update(['poin' => null]);
        Score::whereHas('scoreable', function($query) {
            $query->whereHas('note');
        })->scoreTahun($request->tahun_ajaran)->update(['poin' => -50]);
        return redirect('/badge')->with('success', 'Berhasil menghapus badge');
    }
}