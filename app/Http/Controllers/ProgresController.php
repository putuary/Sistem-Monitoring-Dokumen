<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DokumenDitugaskan;
use App\Models\User;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ProgresController extends Controller
{
    public function index()
    {
        $tahun_ajaran = TahunAjaran::orderBy('id_tahun_ajaran', 'desc')->get();
        if(request('filter')== null || request('filter')== 'kelas') {
            $kelas = Kelas::with(['dosen_kelas', 'tahun_ajaran', 'dokumen_kelas', 'matkul','kelas_dokumen_matkul'])->kelasTahun(request('tahun_ajaran'))->get();
            // dd($kelas);
            return view('admin.progres.progres_kelas', ['kelas' => $kelas, 'tahun_ajaran' => $tahun_ajaran]);
        } else if(request('filter') != null && request('filter') == 'dokumen') {
            $dokumen = DokumenDitugaskan::with(['dokumen_perkuliahan', 'dokumen_matkul', 'dokumen_kelas'])->dokumenTahun(request('tahun_ajaran'))->get();
            // dd($dokumen);
            return view('admin.progres.progres_dokumen', ['dokumen' => $dokumen, 'tahun_ajaran' => $tahun_ajaran]);
        }
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