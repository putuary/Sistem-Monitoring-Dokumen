<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DokumenDitugaskan;
use App\Models\User;
use App\Models\DokumenPerkuliahan;
use App\Models\Kelas;
use App\Models\MataKuliah;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Carbon\Carbon;

class PengingatController extends Controller
{
    public function showPengingat()
    {
        $tahun_ajaran = TahunAjaran::orderBy('id_tahun_ajaran', 'desc')->get();
        $tahun_aktif=TahunAjaran::tahunAktif()->first();
        $dokumen=DokumenDitugaskan::with(['dokumen_perkuliahan', 'tahun_ajaran'])->dokumenTahun(request('tahun_ajaran'))->get();
        
        return view('admin.pengingat.index', ['tahun_ajaran' => $tahun_ajaran, 'tahun_aktif' => $tahun_aktif,'dokumen' => $dokumen]);
    }

    public function editPengingat(Request $request)
    {
        $request->validate([
            'id_dokumen_ditugaskan' => 'required',
            'tenggat_waktu' => 'required',
        ]);

        $dokumen=DokumenDitugaskan::find($request->id_dokumen_ditugaskan)->update([
            'tenggat_waktu' => $request->tenggat_waktu,
        ]);
        
        return redirect()->back()->with('success', 'Berhasil mengubah pengingat');
    }

    public function editPengumpulan(Request $request) {
        $request->validate([
            'id_dokumen_ditugaskan' => 'required',
        ]);

        $dokumen=DokumenDitugaskan::find($request->id_dokumen_ditugaskan);
        // dd($dokumen->pengumpulan);
        if($dokumen->pengumpulan == 1) {
            $dokumen->update([
                'pengumpulan' => 0,
            ]);
            return response()->json([
                'pengumpulan' => false,
                'status' => 'success',
                'message' => 'Pengumpulan dimatikan!',
            ]);
        } else {
            $dokumen->update([
                'pengumpulan' => 1,
            ]);
            return response()->json([
                'pengumpulan' => true,
                'status' => 'success',
                'message' => 'Pengumpulan dihidupkan!',
            ]);
        }
    }

}