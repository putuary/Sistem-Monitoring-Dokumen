<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DokumenDikumpul;
use App\Models\DokumenDitugaskan;
use App\Models\DokumenKelas;
use App\Models\DokumenMatkul;
use App\Models\DokumenPerkuliahan;
use App\Models\Kelas;
use App\Models\MataKuliah;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function showKelasDiampu()
    {
        $kelas = Kelas::with(['dosen_kelas', 'tahun_ajaran', 'dokumen_kelas', 'matkul','kelas_dokumen_matkul'])->kelasDiampu()->kelasAktif()->get();
        // dd($kelas[0]->kelas_dokumen_matkul);
        // $kelas=User::with('dosen_kelas')->where('id', Auth::user()->id)->whereRelation('tahun_ajaran', 'status', '=', 1)->get();
        // dd($kelas[0]->dokumen_dikumpul[0]->dokumen_ditugaskan->dokumen_perkuliahan->nama_dokumen);
        
        return view('dosen.kelas-diampu.index', ['kelas' => $kelas]);
    }

    public function showDokumenDitugaskan($kode_kelas)
    {
        if(isKelasDiampu($kode_kelas)) {
            $kelas=Kelas::where('kode_kelas', $kode_kelas)->first();
            $nama_kelas=$kelas->matkul->nama_matkul.' '.$kelas->nama_kelas;
            $dokumen=DokumenDitugaskan::with(['dokumen_perkuliahan', 'dokumen_matkul' => function($query) use ($kode_kelas) {
                $query->whereHas('kelas_dokumen_matkul', function($query) use ($kode_kelas) {
                    $query->where('kelas_dokumen_matkul.kode_kelas', $kode_kelas);
                });
            }, 'dokumen_kelas' => function($query) use ($kode_kelas) {
                $query->where('kode_kelas', $kode_kelas);
            }])->whereHas('dokumen_matkul', function($query) use ($kode_kelas) {
                $query->whereHas('kelas_dokumen_matkul', function($query) use ($kode_kelas) {
                    $query->where('kelas_dokumen_matkul.kode_kelas', $kode_kelas);
                });
            })->orWhereHas('dokumen_kelas', function($query) use ($kode_kelas) {
                $query->where('kode_kelas', $kode_kelas);
            })->get();
            
            // dd($dokumen);
            return view('dosen.kelas-diampu.dokumen-ditugaskan', ['nama_kelas' => $nama_kelas, 'dokumen' => $dokumen]);
        }
        abort(403, 'Anda tidak memiliki akses ke halaman ini');
    }

    public function downloadTemplate($id_dokumen)
    {
        $dokumen=DokumenPerkuliahan::find($id_dokumen);
        
        return response()->download(public_path('storage/template/'.$dokumen->template));
    }

    public function uploadDokumen(Request $request)
    {
        $request->validate([
            'file_dokumen' => 'required|mimes:pdf|max:10240',
        ]);
        
        // dd($request->all());
        if(is_null($request->id_dokumen_kelas)) {
            $dokumen=DokumenMatkul::where('id_dokumen_matkul', $request->id_dokumen_matkul)->first();
            $tahun_ajaran=$dokumen->dokumen_ditugaskan->tahun_ajaran->tahun_ajaran;
            $matkul=$dokumen->kelas_dokumen_matkul[0]->matkul->nama_matkul;

            $nama_dokumen= $dokumen->dokumen_ditugaskan->dokumen_perkuliahan->nama_dokumen.'_'.$matkul.'.'.$request->file('file_dokumen')->extension();
            
            $request->file('file_dokumen')->storeAs('public/'.pathDokumen($tahun_ajaran, true, $matkul), $nama_dokumen);

            $dokumen->update([
                'file_dokumen' => $nama_dokumen,
                'waktu_pengumpulan' => date('Y-m-d H:i:s'),
            ]);
        } else {
            $dokumen=DokumenKelas::where('id_dokumen_kelas', $request->id_dokumen_kelas)->first();
            $tahun_ajaran=$dokumen->dokumen_ditugaskan->tahun_ajaran->tahun_ajaran;
            $matkul=$dokumen->kelas->matkul->nama_matkul;
            $kelas=$dokumen->kelas->nama_kelas;
            $nama_dokumen= $dokumen->dokumen_ditugaskan->dokumen_perkuliahan->nama_dokumen.'_'.$matkul.'_'.$kelas.'.'.$request->file('file_dokumen')->extension();
            
            $request->file('file_dokumen')->storeAs('public/'.pathDokumen($tahun_ajaran, false, $matkul, $kelas), $nama_dokumen);

            $dokumen->update([
                'file_dokumen' => $nama_dokumen,
                'waktu_pengumpulan' => date('Y-m-d H:i:s'),
            ]);
        }
        return redirect()->back()->with('success', 'Dokumen berhasil dikumpulkan');
    }
}