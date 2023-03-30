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
        $dokumen=DokumenDitugaskan::with('dokumen_perkuliahan')->DokumenAktif()->get();
        
        return view('admin.pengingat.index', ['dokumen' => $dokumen]);
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
        
        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil mengubah pengingat',
        ]);
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
        } else {
            $dokumen->update([
                'pengumpulan' => 1,
            ]);
        }
    }

}