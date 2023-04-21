<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DokumenDitugaskan;
use App\Models\User;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\DokumenMatkul;
use App\Models\DokumenKelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgresController extends Controller
{
    public function index()
    {
        $tahun_ajaran = TahunAjaran::orderBy('id_tahun_ajaran', 'desc')->get();
        $tahun_aktif=TahunAjaran::tahunAktif()->first();
        if(request('filter')== null || request('filter')== 'kelas') {
            $kelas = Kelas::with(['dosen_kelas', 'tahun_ajaran', 'matkul','dokumen_kelas'=> function($query) {
                $query->with('dokumen_ditugaskan');
            }, 'kelas_dokumen_matkul' => function($query) {
                $query->with('dokumen_ditugaskan');
            }])->kelasTahun(request('tahun_ajaran'))->orderBy('kode_matkul', 'asc')->get();
            // dd($kelas);
            return view('admin.progres.progres_kelas', ['kelas' => $kelas, 'tahun_ajaran' => $tahun_ajaran, 'tahun_aktif' => $tahun_aktif]);
        } else if(request('filter') == 'dokumen') {
            $dokumen = DokumenDitugaskan::with(['dokumen_perkuliahan', 'dokumen_matkul', 'dokumen_kelas'])->dokumenTahun(request('tahun_ajaran'))->get();
            // dd($dokumen);
            return view('admin.progres.progres_dokumen', ['dokumen' => $dokumen, 'tahun_ajaran' => $tahun_ajaran, 'tahun_aktif' => $tahun_aktif]);
        }
    }

    public function showProgresKelas()
    {
        $kode_kelas = request('id');
        $kelas=Kelas::where('kode_kelas', $kode_kelas)->first();
        $nama_kelas=$kelas->matkul->nama_matkul.' '.$kelas->nama_kelas;
            
        $dokumen=DokumenDitugaskan::with(['dokumen_perkuliahan', 'dokumen_matkul' => function($query) use ($kode_kelas) {
            $query->dokumenKelas($kode_kelas);
        }, 'dokumen_kelas' => function($query) use ($kode_kelas) {
            $query->where('kode_kelas', $kode_kelas);
        }])->whereHas('dokumen_matkul', function($query) use ($kode_kelas) {
            $query->dokumenKelas($kode_kelas);
        })->orWhereHas('dokumen_kelas', function($query) use ($kode_kelas) {
            $query->where('kode_kelas', $kode_kelas);
        })->get();

        return view('admin.progres.dokumen_ditugaskan', ['nama_kelas' => $nama_kelas,'dokumen' => $dokumen]);
    }

    public function showProgresDokumen()
    {
        $id_dokumen = request('id');
        $dokumen=DokumenDitugaskan::with(['tahun_ajaran', 'dokumen_perkuliahan', 'dokumen_matkul' => function($query) {
            $query->with('matkul');
        }, 'dokumen_kelas' => function($query) {
            $query->with(['kelas' => function($query) {
                $query->with('matkul');
            }]);
        }])->where('id_dokumen_ditugaskan', $id_dokumen)->first();
        // dd($dokumen);
        
        return view('admin.progres.kelas_ditugaskan', ['dokumen' => $dokumen]);
    }

    public function showRiwayat() {
        
        $tahun_ajaran = TahunAjaran::orderBy('id_tahun_ajaran', 'desc')->get();
        // union two table
        $dokumen_kelas=DokumenKelas::with(['dokumen_ditugaskan'])->whereHas('dokumen_ditugaskan', function($query) {
            $query->dokumenTahun(request('tahun_ajaran'));
        })->filter(request('filter'));
        $dokumen_matkul=DokumenMatkul::with(['dokumen_ditugaskan'])->whereHas('dokumen_ditugaskan', function($query) {
            $query->dokumenTahun(request('tahun_ajaran'));
        })->filter(request('filter'));

        $dokumen_all = $dokumen_kelas->union($dokumen_matkul)->orderBy('waktu_pengumpulan', 'desc')->get();
        // dd($dokumen_all);

        return view('admin.riwayat.index', [
            'tahun_ajaran' => $tahun_ajaran,
            'dokumen' => $dokumen_all,
            'terkumpul' => countStatusDokumen('terkumpul', request('tahun_ajaran') ?? null),
            'tepat_waktu' => countStatusDokumen('tepat_waktu', request('tahun_ajaran') ?? null),
            'terlambat' => countStatusDokumen('terlambat', request('tahun_ajaran') ?? null),
            'belum_terkumpul' => countStatusDokumen('belum_terkumpul', request('tahun_ajaran') ?? null),
        ]);
    }

    public function delete_user(Request $request)
    {
        if(in_array(Auth::user()->role, ["kaprodi", "gkmp", "admin"])) {
            User::where('id', $request->id_pengguna)->delete();
            return redirect('/manajemen-pengguna')->with('success', 'Data berhasil dihapus');
        }
        return abort(404);
    }

}