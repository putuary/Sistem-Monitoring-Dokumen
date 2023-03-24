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

class KelasController extends Controller
{
    public function showKelasDiampu()
    {
        $kelas = Kelas::with(['dosen_kelas', 'tahun_ajaran'])->whereHas('dosen_kelas', function($query) {
            $query->where('id_dosen', Auth::user()->id);
        })->whereHas('tahun_ajaran', function($query) {
            $query->where('status', 1);
        })->get();
        // $kelas=User::with('dosen_kelas')->where('id', Auth::user()->id)->whereRelation('tahun_ajaran', 'status', '=', 1)->get();
        // dd($kelas);
        
        return view('dosen.kelas-diampu.index', ['kelas' => $kelas]);
    }

    public function editDokumen(Request $request)
    {
        if(in_array(Auth::user()->role, nameRoles('midleRole'))) {
            // dd(request()->all());
            $request->validate([
                'id_dokumen'            => 'required',
                'nama_dokumen'          => 'required',
                'tenggat_waktu_default' => 'required',
                'dikumpulkan_per'       => 'required',
                'template'              => 'mimes:docx,doc,xls,xlsx|max:3072'
            ]);

            if($request->hasFile('template')) {
                // dd($request->file('template')->extension());
                $nama_dokumen= 'Template-'.$request->nama_dokumen.'.'.$request->file('template')->extension();
                $request->file('template')->storeAs('public/template', $nama_dokumen); // 'public' adalah nama folder di storage/app/public/template

                // dd($id);
                DokumenPerkuliahan::where('id_dokumen', $request->id_dokumen)->update([
                    'nama_dokumen'          => $request->nama_dokumen,
                    'tenggat_waktu_default' => $request->tenggat_waktu_default,
                    'dikumpulkan_per'       => $request->dikumpulkan_per,
                    'template'              => $nama_dokumen,
            ]);
            } else {
                DokumenPerkuliahan::where('id_dokumen', $request->id_dokumen)->update([
                    'nama_dokumen'          => $request->nama_dokumen,
                    'tenggat_waktu_default' => $request->tenggat_waktu_default,
                    'dikumpulkan_per'       => $request->dikumpulkan_per,
            ]);
            }
            
            return redirect()->back()->with('success', 'Data berhasil diubah');
        }
        return abort(404);
    }

    public function deleteDokumen(Request $request)
    {
        if(in_array(Auth::user()->role, nameRoles('midleRole'))) {
            DokumenPerkuliahan::where('id_dokumen', $request->id_dokumen)->delete();

            return redirect()->back()->with('success', 'Data berhasil dihapus');
        }
        return abort(404);
    }

}