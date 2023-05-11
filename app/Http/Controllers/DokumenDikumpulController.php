<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DokumenKelas;
use App\Models\DokumenMatkul;
use App\Models\DokumenPerkuliahan;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

class DokumenDikumpulController extends Controller
{
    public static function showProfilDokumen($id_dokumen) {
        if(like_match('DM%', $id_dokumen)) {
            $dokumen=DokumenMatkul::with(['matkul','dokumen_ditugaskan' => function($query) {
                $query->with('tahun_ajaran');
            },
            ])->where('id_dokumen_matkul', $id_dokumen)->first();
            $tahun_ajaran=$dokumen->dokumen_ditugaskan->tahun_ajaran->tahun_ajaran;
            $matkul=$dokumen->matkul->nama_matkul;
            $nama_dokumen= $dokumen->dokumen_ditugaskan->nama_dokumen;
            $real_name= $nama_dokumen.'_'.$matkul;

            $folder_path= storage_path('app/'.pathDokumen($tahun_ajaran, true, $matkul));
            $path =  storage_path('app/'.pathDokumen($tahun_ajaran, true, $matkul).'/'.$real_name);
            $path_dokumen = storage_path('app/'.pathDokumen($tahun_ajaran, true, $matkul).'/'.$dokumen->file_dokumen);
        } else if(like_match('DK%', $id_dokumen)) {
            $dokumen=DokumenKelas::with(['kelas' => function($query) {
                $query->with(['matkul']);
            },'dokumen_ditugaskan' => function($query) {
                $query->with('tahun_ajaran');
            },
            ])->where('id_dokumen_kelas', $id_dokumen)->first();
            $tahun_ajaran=$dokumen->dokumen_ditugaskan->tahun_ajaran->tahun_ajaran;
            $matkul=$dokumen->kelas->matkul->nama_matkul;
            $kelas=$dokumen->kelas->nama_kelas;
            $nama_dokumen= $dokumen->dokumen_ditugaskan->nama_dokumen;
            $real_name= $nama_dokumen.'_'.$matkul.'_'.$kelas;

            $folder_path= storage_path('app/'.pathDokumen($tahun_ajaran, false, $matkul, $kelas));
            $path = storage_path('app/'.pathDokumen($tahun_ajaran, false, $matkul, $kelas).'/'.$nama_dokumen);
            $path_dokumen = storage_path('app/'.pathDokumen($tahun_ajaran, false, $matkul, $kelas).'/'.$dokumen->file_dokumen);
        }
        return (object) [
            'dokumen_dikumpul' => $dokumen,
            'tahun_ajaran' => $tahun_ajaran,
            'matkul'       => $matkul,
            'nama_dokumen' => $nama_dokumen,
            'real_name'    => $real_name,
            'folder_path'  => $folder_path,
            'path_multiple'=> $path,
            'path_dokumen' => $path_dokumen,
        ];

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

    public function deleteDokumen($id_dokumen)
    {
        $dokumen=$this->showProfilDokumen($id_dokumen);
        if($dokumen->dokumen_dikumpul->dokumen_ditugaskan->dikumpul==0) {
            File::delete($dokumen->path_dokumen);
        } else {
            File::deleteDirectory($dokumen->path_dokumen);
        }
        $dokumen->dokumen_dikumpul->update([
            'file_dokumen'      => null,
            'waktu_pengumpulan' => null,
        ]);

        return redirect()->back()->with('success', 'Dokumen berhasil dihapus');
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
            'file_dokumen'      => $filename,
            'waktu_pengumpulan' => date('Y-m-d H:i:s'),
        ]);

        if($dokumen->dokumen_dikumpul->dokumen_ditugaskan->dikumpulkan_per == 0) {
            $dokumen_matkul=DokumenMatkul::with(['kelas_dokumen_matkul' => function($query) {
                $query->with('dosen_kelas');
            }])->find($dokumen->dokumen_dikumpul->id_dokumen_matkul);

            $data=submitScore($dokumen->dokumen_dikumpul->dokumen_ditugaskan->tenggat_waktu, $dokumen_matkul->waktu_pengumpulan, true, $dokumen_matkul->id_dokumen_matkul, $dokumen->dokumen_dikumpul->dokumen_ditugaskan->id_dokumen_ditugaskan);
              
            $dokumen_matkul->scores()->update([
                'poin'       => $data['poin'],
            ]);
        
        } else {
            $dokumen_kelas=DokumenKelas::with(['kelas' => function($query) {
                $query->with('dosen_kelas');
            }])->find($dokumen->dokumen_dikumpul->id_dokumen_kelas);
            
            $data=submitScore($dokumen->dokumen_dikumpul->dokumen_ditugaskan->tenggat_waktu, $dokumen_kelas->waktu_pengumpulan, false, $dokumen_kelas->id_dokumen_kelas, $dokumen->dokumen_dikumpul->dokumen_ditugaskan->id_dokumen_ditugaskan);

            $dokumen_kelas->scores()->update([
                'poin'     => $data['poin'],
            ]);
        }

        return redirect()->back()->with('success', 'Dokumen berhasil dikumpulkan');
    }

    public function uploadDokumenMultiple(Request $request){
        // dd($request->all());
        $request->validate([
            'file_dokumen.*' => 'required|mimes:pdf|max:10240',
        ]);
        // $data=$request->file('file_dokumen')->move(storage_path('app/dokumen'), $request->file('file_dokumen')->getClientOriginalName());
        // dd($data);

        $dokumen=$this->showProfilDokumen($request->id_dokumen);
        foreach ($request->file('file_dokumen') as $file) {
            $nama_dokumen= $file->getClientOriginalName();
            $file->move($dokumen->path_multiple, $nama_dokumen);
        }
        
        $dokumen->dokumen_dikumpul->update([
            'file_dokumen'      => $dokumen->nama_dokumen,
            'waktu_pengumpulan' => date('Y-m-d H:i:s'),
        ]);

        if($dokumen->dokumen_dikumpul->dokumen_ditugaskan->dikumpulkan_per == 0) {
            $dokumen_matkul=DokumenMatkul::with(['kelas_dokumen_matkul' => function($query) {
                $query->with('dosen_kelas');
            }])->find($dokumen->dokumen_dikumpul->id_dokumen_matkul);

            $data=submitScore($dokumen->dokumen_dikumpul->dokumen_ditugaskan->tenggat_waktu, $dokumen_matkul->waktu_pengumpulan, true, $dokumen_matkul->id_dokumen_matkul, $dokumen->dokumen_dikumpul->dokumen_ditugaskan->id_dokumen_ditugaskan);
              
            $dokumen_matkul->scores()->update([
                'poin'       => $data['poin'],
            ]);

        } else {
            $dokumen_kelas=DokumenKelas::with(['kelas' => function($query) {
                $query->with('dosen_kelas');
            }])->find($dokumen->dokumen_dikumpul->id_dokumen_kelas);

            $data=submitScore($dokumen->dokumen_dikumpul->dokumen_ditugaskan->tenggat_waktu, $dokumen_kelas->waktu_pengumpulan, false, $dokumen_kelas->id_dokumen_kelas, $dokumen->dokumen_dikumpul->dokumen_ditugaskan->id_dokumen_ditugaskan);

            $dokumen_kelas->scores()->update([
                'poin'           => $data['poin'],
            ]);
        }


        return redirect()->back()->with('success', 'Dokumen berhasil dikumpulkan');
    }

    public function showDokumenMultiple($id_dokumen) {
       
        $dokumen=$this->showProfilDokumen($id_dokumen);
        // $data=scandir('D:\Kuliah\TA\Program\scele-if - Copy\storage\app/Dokumen_Perkuliahan/2023-2024 Ganjil/Teori Bahasa Formal dan Otomata/Portofolio Perkuliahan_Teori Bahasa Formal dan Otomata', SCANDIR_SORT_ASCENDING);
        // dd($data);

        $storage = array_diff(scandir($dokumen->path_multiple, SCANDIR_SORT_ASCENDING), array('.', '..'));

        return view('dosen.kelas-diampu.tampil-dokumen-multiple', [
            'title'      => $dokumen->nama_dokumen,
            'id_dokumen' => $id_dokumen,
            'nama_files' => $storage
        ]);
    }
    
    public function readDokumenDikumpulMultiple($id_dokumen)
    {
        $nama_dokumen=request('dokumen');

        $dokumen=$this->showProfilDokumen($id_dokumen);

        // Cek apakah file ada di dalam folder
        if (file_exists($dokumen->path_multiple . '/' . $nama_dokumen)) {
            // mengembalikan response sebuah file
            return response()->file($dokumen->path_multiple . '/' . $nama_dokumen);
        } else{
            return redirect()->back()->with('failed', 'File dokumen tidak ada');
        }
    }

    public function renameDokumenDikumpulMultiple(Request $request, $id_dokumen)
    {
        $dokumen=$this->showProfilDokumen($id_dokumen);

        // Cek apakah file lama ada di dalam folder
        if (file_exists($dokumen->path_multiple . '/' . $request->old_name)) {
            // Lakukan rename file
            $storage = array_diff(scandir($dokumen->path_multiple, SCANDIR_SORT_ASCENDING), array('.', '..'));
            if(in_array($request->new_name, $storage)) {
                return redirect()->back()->with('failed', 'Nama file sudah ada');
            }
            rename($dokumen->path_multiple . '/' . $request->old_name, $dokumen->path_multiple . '/' . $request->new_name);
            
            return redirect()->back()->with('success', 'Nama file dokumen berhasil diubah');
        } else{
            return redirect()->back()->with('failed', 'File dokumen tidak ada');
        }
    }

    public function deleteDokumenDikumpulMultiple(Request $request, $id_dokumen)
    {
        $dokumen=$this->showProfilDokumen($id_dokumen);

        // Cek apakah file ada di dalam folder
        if (file_exists($dokumen->path_multiple . '/' . $request->nama_dokumen)) {
            // Hapus file
            unlink($dokumen->path_multiple . '/' . $request->nama_dokumen);

            $storage = array_diff(scandir($dokumen->path_multiple, SCANDIR_SORT_ASCENDING), array('.', '..'));
            if(count($storage) == 0) {
                $dokumen->dokumen_dikumpul->update([
                    'file_dokumen'      => null,
                    'waktu_pengumpulan' => null,
                ]);
            }
        
            return redirect()->back()->with('success', 'File dokumen berhasil dihapus');
        } else{
            return redirect()->back()->with('failed', 'File dokumen tidak ada');
        }
    }

    public function showDokumenDikumpul($id_dokumen)
    {
        $dokumen=$this->showProfilDokumen($id_dokumen);

        if($dokumen->dokumen_dikumpul->dokumen_ditugaskan->dikumpul==1) {
            if(request('dokumen')) {
                $nama_dokumen=request('dokumen');
                // Cek apakah file ada di dalam folder
                if (file_exists($dokumen->path_multiple . '/' . $nama_dokumen)) {
                    // mengembalikan response sebuah file
                    return response()->file($dokumen->path_multiple . '/' . $nama_dokumen);
                } else{
                    return redirect()->back()->with('failed', 'File dokumen tidak ada');
                }
            }
            
            $storage = array_diff(scandir($dokumen->path_multiple, SCANDIR_SORT_ASCENDING), array('.', '..'));

            if(\Request::is('kelas-diampu/*')) {
                return view('dosen.kelas-diampu.tampil-dokumen-multiple', [
                    'title'      => $dokumen->nama_dokumen,
                    'id_dokumen' => $id_dokumen,
                    'nama_files' => $storage
                ]);
            }else if(\Request::is('dokumen-perkuliahan/*')) {
                return view('dosen.dokumen.tampil-dokumen-multiple', [
                    'title'      => $dokumen->nama_dokumen,
                    'id_dokumen' => $id_dokumen,
                    'nama_files' => $storage
                ]);
            } else if(\Request::is('progres-pengumpulan/*')) {
                return view('admin.progres.tampil-dokumen-multiple', [
                    'title'      => $dokumen->nama_dokumen,
                    'id_dokumen' => $id_dokumen,
                    'nama_files' => $storage
                ]);
            }
        }
        return response()->file($dokumen->path_dokumen);
    }

    public function downloadDokumenDikumpul($id_dokumen) {
        $dokumen=$this->showProfilDokumen($id_dokumen);

        if($dokumen->dokumen_dikumpul->dokumen_ditugaskan->dikumpul==1) {
            if(request('dokumen')) {
                $nama_dokumen=request('dokumen');
                // Cek apakah file ada di dalam folder
                if (file_exists($dokumen->path_multiple . '/' . $nama_dokumen)) {
                    // mengembalikan response sebuah file
                    return response()->download($dokumen->path_multiple . '/' . $nama_dokumen);
                } else{
                    return redirect()->back()->with('failed', 'File dokumen tidak ada');
                }
            }
            return response()->download(makeArchive($dokumen->real_name, $dokumen->path_multiple));
        }
        return response()->download($dokumen->path_dokumen);
    }

    public function deleteDokumenDikumpul($id_dokumen, Request $request) {
        $dokumen=$this->showProfilDokumen($id_dokumen);
        if($request->nama_dokumen) {
            // Cek apakah file ada di dalam folder
            if (file_exists($dokumen->path_multiple . '/' . $request->nama_dokumen)) {
                // Hapus file
                unlink($dokumen->path_multiple . '/' . $request->nama_dokumen);

                $storage = array_diff(scandir($dokumen->path_multiple, SCANDIR_SORT_ASCENDING), array('.', '..'));
                if(count($storage) == 0) {
                    $dokumen->dokumen_dikumpul->update([
                        'file_dokumen'      => null,
                        'waktu_pengumpulan' => null,
                    ]);
                }
            
                return redirect()->back()->with('success', 'File dokumen berhasil dihapus');
            } else{
                return redirect()->back()->with('failed', 'File dokumen tidak ada');
            }
        }
        
        if($dokumen->dokumen_dikumpul->dokumen_ditugaskan->dikumpul==0) {
            File::delete($dokumen->path_dokumen);
        } else {
            File::deleteDirectory($dokumen->path_dokumen);
        }
        $dokumen->dokumen_dikumpul->update([
            'file_dokumen'      => null,
            'waktu_pengumpulan' => null,
        ]);

        return redirect()->back()->with('success', 'Dokumen berhasil dihapus');
    }

          
}