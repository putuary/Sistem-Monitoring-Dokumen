<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DokumenDitugaskan;
use App\Models\DokumenKelas;
use App\Models\DokumenMatkul;
use App\Models\DokumenPerkuliahan;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KelasDiampuController extends Controller
{
    private function showProfilDokumen($id_dokumen) {
        if(like_match('DM%', $id_dokumen)) {
            $dokumen=DokumenMatkul::with(['matkul','dokumen_ditugaskan' => function($query) {
                $query->with(['dokumen_perkuliahan', 'tahun_ajaran']);
            },
            ])->where('id_dokumen_matkul', $id_dokumen)->first();
            $tahun_ajaran=$dokumen->dokumen_ditugaskan->tahun_ajaran->tahun_ajaran;
            $matkul=$dokumen->matkul->nama_matkul;
            $nama_dokumen= $dokumen->dokumen_ditugaskan->dokumen_perkuliahan->nama_dokumen;
            $real_name= $nama_dokumen.'_'.$matkul;

            $folder_path= storage_path('app/'.pathDokumen($tahun_ajaran, true, $matkul));
            $path =  storage_path('app/'.pathDokumen($tahun_ajaran, true, $matkul).'/'.$real_name);
            $path_dokumen = storage_path('app/'.pathDokumen($tahun_ajaran, true, $matkul).'/'.$dokumen->file_dokumen);
        } else if(like_match('DK%', $id_dokumen)) {
            $dokumen=DokumenKelas::with(['kelas' => function($query) {
                $query->with(['matkul']);
            },'dokumen_ditugaskan' => function($query) {
                $query->with(['dokumen_perkuliahan', 'tahun_ajaran']);
            },
            ])->where('id_dokumen_kelas', $id_dokumen)->first();
            $tahun_ajaran=$dokumen->dokumen_ditugaskan->tahun_ajaran->tahun_ajaran;
            $matkul=$dokumen->kelas->matkul->nama_matkul;
            $kelas=$dokumen->kelas->nama_kelas;
            $nama_dokumen= $dokumen->dokumen_ditugaskan->dokumen_perkuliahan->nama_dokumen;
            $real_name= $nama_dokumen.'_'.$matkul.'_'.$kelas;

            $folder_path= storage_path('app/'.pathDokumen($tahun_ajaran, false, $matkul, $kelas));
            $path = storage_path('app/'.pathDokumen($tahun_ajaran, false, $matkul, $kelas).'/'.$real_name);
            $path_dokumen = storage_path('app/'.pathDokumen($tahun_ajaran, false, $matkul, $kelas).'/'.$dokumen->file_dokumen);
        }
        return (object) [
            'dokumen_dikumpul' => $dokumen,
            'tahun_ajaran' => $tahun_ajaran,
            'matkul'       => $matkul,
            'nama_dokumen' => $nama_dokumen,
            'real_name'    => $real_name,
            'folder_path'  => $folder_path,
            'path'         => $path,
            'path_dokumen' => $path_dokumen,
        ];

    }

    public function showKelasDiampu()
    {
        $tahun_ajaran = TahunAjaran::orderBy('id_tahun_ajaran', 'desc')->get();
        $tahun_aktif = TahunAjaran::tahunAktif()->first();
        $kelas = Kelas::with(['dosen_kelas', 'tahun_ajaran', 'dokumen_kelas', 'matkul','kelas_dokumen_matkul'])->kelasDiampu()->kelasTahun(request('tahun_ajaran'))->get();
        
        return view('dosen.kelas-diampu.index', ['kelas' => $kelas, 'tahun_ajaran' => $tahun_ajaran, 'tahun_aktif' => $tahun_aktif]);
    }

    public function showDokumenDitugaskan($kode_kelas)
    {
        if(isKelasDiampu($kode_kelas)) {
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

    public function readDokumenSingle($id_dokumen)
    {
        $dokumen=$this->showProfilDokumen($id_dokumen);
        
        return response()->file($dokumen->path_dokumen);
    }

    public function downloadDokumenSingle($id_dokumen)
    {
        $dokumen=$this->showProfilDokumen($id_dokumen);
        
        return response()->download($dokumen->path_dokumen);
    }

    public function uploadDokumen(Request $request)
    {
        $request->validate([
            'file_dokumen' => 'required|mimes:pdf|max:10240',
        ]);

        $dokumen=$this->showProfilDokumen($request->id_dokumen);
        $filename=$dokumen->real_name.'.'.$request->file('file_dokumen')->extension();
        $request->file('file_dokumen')->move($dokumen->folder_path, $filename);

        $dokumen->dokumen_dikumpul->update([
            'file_dokumen' => $filename,
            'waktu_pengumpulan' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->back()->with('success', 'Dokumen berhasil dikumpulkan');
    }

    public function uploadDokumenMultiple(Request $request){
        // dd($request->all());
        // $request->validate([
        //     'file_dokumen' => 'required|mimes:pdf|max:10240',
        // ]);
        // $data=$request->file('file_dokumen')->move(storage_path('app/dokumen'), $request->file('file_dokumen')->getClientOriginalName());
        // dd($data);
        
        // dd($request->all());
        if(is_null($request->id_dokumen_kelas)) {
            $dokumen=DokumenMatkul::with(['matkul','dokumen_ditugaskan' => function($query) {
                $query->with(['dokumen_perkuliahan', 'tahun_ajaran']);
            },
            ])->where('id_dokumen_matkul', $request->id_dokumen_matkul)->first();

            $tahun_ajaran=$dokumen->dokumen_ditugaskan->tahun_ajaran->tahun_ajaran;
            $matkul=$dokumen->matkul->nama_matkul;

            $real_name= $dokumen->dokumen_ditugaskan->dokumen_perkuliahan->nama_dokumen.'_'.$matkul;
            foreach ($request->file('file_dokumen') as $file) {
                $nama_dokumen= $file->getClientOriginalName();
                $file->move(storage_path('app/'.pathDokumen($tahun_ajaran, true, $matkul).'/'.$real_name), $nama_dokumen);
            }

            $dokumen->update([
                'file_dokumen' => $real_name,
                'waktu_pengumpulan' => date('Y-m-d H:i:s'),
            ]);
        } else {
            $dokumen=DokumenKelas::with(['kelas' => function($query) {
                $query->with(['matkul']);
            },'dokumen_ditugaskan' => function($query) {
                $query->with(['dokumen_perkuliahan', 'tahun_ajaran']);
            },
            ])->where('id_dokumen_kelas', $request->id_dokumen_kelas)->first();

            $tahun_ajaran=$dokumen->dokumen_ditugaskan->tahun_ajaran->tahun_ajaran;
            $matkul=$dokumen->kelas->matkul->nama_matkul;
            $kelas=$dokumen->kelas->nama_kelas;

            $real_name= $dokumen->dokumen_ditugaskan->dokumen_perkuliahan->nama_dokumen.'_'.$matkul.'_'.$kelas;
            foreach ($request->file('file_dokumen') as $file) {
                $nama_dokumen= $file->getClientOriginalName();
                $file->move(storage_path('app/'.pathDokumen($tahun_ajaran, false, $matkul, $kelas).'/'.$real_name), $nama_dokumen);
            }

            $dokumen->update([
                'file_dokumen' => $real_name,
                'waktu_pengumpulan' => date('Y-m-d H:i:s'),
            ]);
        }
        return redirect()->back()->with('success', 'Dokumen berhasil dikumpulkan');
    }

    public function showDokumenDikumpul($id_dokumen) {
       
        $dokumen=$this->showProfilDokumen($id_dokumen);

        $storage = array_diff(scandir($dokumen->path, SCANDIR_SORT_ASCENDING), array('.', '..'));

        return view('dosen.kelas-diampu.tampil-dokumen-multiple', [
            'title'      => $dokumen->nama_dokumen,
            'id_dokumen' => $id_dokumen,
            'nama_files' => $storage
        ]);
    }

    public function renameDokumenDikumpul(Request $request, $id_dokumen)
    {
        $dokumen=$this->showProfilDokumen($id_dokumen);

        // Cek apakah file lama ada di dalam folder
        if (file_exists($dokumen->path . '/' . $request->old_name)) {
            // Lakukan rename file
            rename($dokumen->path . '/' . $request->old_name, $dokumen->path . '/' . $request->new_name);
        
            return redirect()->back()->with('success', 'Nama file dokumen berhasil diubah');
        } else{
            return redirect()->back()->with('failed', 'File dokumen tidak ada');
        }
    }
          
}