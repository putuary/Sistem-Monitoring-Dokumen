<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Models\DokumenKelas;
use App\Models\Score;
use App\Models\TahunAjaran;
use App\Models\User;
use App\Models\UserBadge;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaderBoardController extends Controller
{
    public function index()
    {
        $tahun_aktif=TahunAjaran::tahunAktif()->first();

        $users=User::with(['score' => function($query) {
            $query->with(['scoreable' => function($query) {
                $query->with('dokumen_ditugaskan');
            }])->whereHas('tahun_ajaran', function($query) {
                $query->tahunAktif();
            });
        }])->whereHas('dosen_kelas', function($query) {
            $query->kelasAktif();
        })->where('role', '!=', 'admin')->get();

        // dd($users[0]);

        $leaderboards=showRank($users);
        // dd($leaderboards[0]->score[0]->scoreable->nama_matkul);

       return view('admin.leaderboard.tampil-leaderboard', ['leaderboards' => $leaderboards, 'tahun_aktif' => $tahun_aktif]);
    }

    public function resultBadge(Request $request) {

        Score::where('score', 0)->where('id_tahun_ajaran', $request->id_tahun_ajaran)->update(['score'  => -100]);

        $users=User::with(['score' => function($query) {
            $query->with('scoreable')->whereHas('tahun_ajaran', function($query) {
                $query->tahunAktif();
            });
        }])->whereHas('dosen_kelas', function($query) {
            $query->kelasAktif();
        })->where('role', '!=', 'admin')->get();

        // dd($users);

        $tahun_aktif_badge=UserBadge::where('is_aktif', 1)->first();

        if($request->id_tahun_ajaran == ($tahun_aktif_badge->id_tahun_ajaran ?? null)) {
            UserBadge::where('id_tahun_ajaran', $request->id_tahun_ajaran)->delete();
        }

        $lecturers=showRank($users);

        UserBadge::where('is_aktif', 1)->update(['is_aktif' => 0]);
        foreach ($lecturers as $key => $lecturer) {
            if($key === 0) {
                giveBadge($lecturer->user->id, 1, $request->id_tahun_ajaran);
            } else if($key == 1) {
                giveBadge($lecturer->user->id, 2, $request->id_tahun_ajaran);
            } else if($key == 2) {
                giveBadge($lecturer->user->id, 3, $request->id_tahun_ajaran);
            } else if($key == (count($lecturers) - 1)) {
                giveBadge($lecturer->user->id, 4, $request->id_tahun_ajaran);
            } else if($key == (count($lecturers) - 2)) {
                giveBadge($lecturer->user->id, 5, $request->id_tahun_ajaran);
            } else if($key == (count($lecturers) - 3)) {
                giveBadge($lecturer->user->id, 6, $request->id_tahun_ajaran);
            }

            if($lecturer->onTime == $lecturer->task) {
                giveBadge($lecturer->user->id, 7, $request->id_tahun_ajaran);
            }

            if($lecturer->late >= (0.5 * $lecturer->task)) {
                giveBadge($lecturer->user->id, 8, $request->id_tahun_ajaran);
            }

            if($lecturer->empty >= 1) {
                giveBadge($lecturer->user->id, 9, $request->id_tahun_ajaran);
            }
        }
        return redirect('/badge')->with('success', 'Berhasil memberikan badge');
    }

    public function showResultBadge() {
        $tahun_aktif=TahunAjaran::tahunAktif()->first();
        $tahun_ajaran=TahunAjaran::orderBy('id_tahun_ajaran', 'desc')->get();
        $users=User::with(['user_badge' => function($query) use ($tahun_aktif) {
            $query->where('id_tahun_ajaran', (request('tahun_ajaran') ?? $tahun_aktif->id_tahun_ajaran));
            }, 'score' => function($query) {
            $query->scoreTahun(request('tahun_ajaran'));
        }])->whereHas('dosen_kelas', function($query) {
            $query->kelasTahun(request('tahun_ajaran'));
        })->where('role', '!=', 'admin')->get();

        $user_badges=showRank($users);
        // dd($user_badges);

        return view('admin.leaderboard.tampil-perolehan-badge', ['tahun_aktif' => $tahun_aktif, 'tahun_ajaran' => $tahun_ajaran, 'user_badges' => $user_badges]);
    }
}