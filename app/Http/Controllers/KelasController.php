<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DokumenDikumpul;
use App\Models\DokumenDitugaskan;
use App\Models\DokumenPerkuliahan;
use App\Models\Kelas;
use App\Models\MataKuliah;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function showKelasDiampu()
    {
        $kelas = Kelas::with(['matkul','dosen_kelas', 'tahun_ajaran', 'dokumen_dikumpul'])->kelasDiampu()->kelasAktif()->get();
        // $kelas=User::with('dosen_kelas')->where('id', Auth::user()->id)->whereRelation('tahun_ajaran', 'status', '=', 1)->get();
        // dd($kelas[0]->dokumen_dikumpul[0]->dokumen_ditugaskan->dokumen_perkuliahan->nama_dokumen);
        
        return view('dosen.kelas-diampu.index', ['kelas' => $kelas]);
    }

    public function showDokumenDitugaskan($kode_kelas)
    {
        if(isKelasDiampu($kode_kelas, Auth::user()->id)) {
            $dokumen=DokumenDitugaskan::with(['dokumen_perkuliahan', 'dokumen_dikumpul' => function($query) use ($kode_kelas) {
                $query->where('kode_kelas', $kode_kelas);
            }])->whereHas('dokumen_dikumpul', function($query) use ($kode_kelas) {
                $query->where('kode_kelas', $kode_kelas);
            })->get();
            
            // dd($dokumen);
            return view('dosen.kelas-diampu.dokumen-ditugaskan', ['dokumen' => $dokumen]);
        }
        return abort(404);
    }

    public function downloadTemplate($id_dokumen)
    {
        $dokumen=DokumenPerkuliahan::find($id_dokumen);
        
        return response()->download(public_path('storage/template/'.$dokumen->template));
    }

    public function uploadDokumen(Request $request)
    {
        $request->validate([
            'id_dokumen_dikumpul' => 'required',
            'file_dokumen' => 'required|mimes:pdf|max:10240',
        ]);

        $dokumen=DokumenDikumpul::where('id_dokumen_dikumpul', $request->id_dokumen_dikumpul)->first();
        $type_dokumen=$dokumen->dokumen_ditugaskan->dokumen_perkuliahan->dikumpulkan_per;
        $tahun_ajaran=$dokumen->dokumen_ditugaskan->tahun_ajaran->tahun_ajaran;
        $matkul=$dokumen->kelas->matkul->nama_matkul;
        
        
        if($type_dokumen==0) {
            $kelas=Kelas::KelasMatkulTahun($dokumen->kelas->kode_matkul, $dokumen->kelas->id_tahun_ajaran)->get();
            $nama_dokumen= $dokumen->dokumen_ditugaskan->dokumen_perkuliahan->nama_dokumen.'_'.$matkul.'.'.$request->file('file_dokumen')->extension();
            
            $request->file('file_dokumen')->storeAs('public/'.pathDokumen($tahun_ajaran, true, $matkul), $nama_dokumen); // 'public' adalah nama folder di storage/app/public/template

            foreach($kelas as $kls) {
                DokumenDikumpul::where('id_dokumen_ditugaskan', $dokumen->dokumen_ditugaskan->id_dokumen_ditugaskan)
                ->where('kode_kelas', $kls->kode_kelas)->update([
                    'file_dokumen' => $nama_dokumen,
                    'waktu_pengumpulan' => date('Y-m-d H:i:s'),
                ]);
            }

        } else {
            $nama_dokumen= $dokumen->dokumen_ditugaskan->dokumen_perkuliahan->nama_dokumen.'_'.$matkul.'_'.$dokumen->kelas->nama_kelas.'.'.$request->file('file_dokumen')->extension();
            $kelas=$dokumen->kelas->nama_kelas;
            $request->file('file_dokumen')->storeAs('public/'.pathDokumen($tahun_ajaran, false, $matkul, $kelas), $nama_dokumen); // 'public' adalah nama folder di storage/app/public/template
            DokumenDikumpul::where('id_dokumen_dikumpul', $request->id_dokumen_dikumpul)->update([
                    'file_dokumen' => $nama_dokumen,
                    'waktu_pengumpulan' => date('Y-m-d H:i:s'),
                ]);
        }

        return redirect()->back()->with('success', 'Dokumen berhasil dikumpulkan');
    }
}