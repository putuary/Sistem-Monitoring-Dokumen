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

class PenugasanController extends Controller
{
    public function index()
    {
        return view('penugasan.index');
    }

    public function stepOne()
    {
        $matkul=MataKuliah::all();
        // dd($matkul);
        return view('penugasan.step-one', ['matkul' => $matkul]);
    }

    public function stepTwo(Request $request)
    {
        $request->validate([
            'tahun_ajaran' => 'required',
            'kode_matkul' => 'required',
            'jumlah' => 'required',
        ]);
        $data=$request->all();
        $nama_matkul=array();
        foreach($data['kode_matkul'] as $matkul) {
            $nama_matkul[]=MataKuliah::where('kode_matkul', $matkul)->first()->nama_matkul;
        }
        $dosen=User::where('role', '!=', 'admin')->get();
        $dokumen=DokumenPerkuliahan::all();
        // dd($data);
        // dd($nama_matkul);
        return view('penugasan.step-two', ['data' => $data, 'nama_matkul' => $nama_matkul, 'dosen' => $dosen, 'dokumen' => $dokumen]);
    }

    public function storePenugasan(Request $request)
    {
        if(in_array(Auth::user()->role, nameRoles('superRole'))) {
            $request->validate([
                'kode_matkul' => 'required',
                'nama_kelas' => 'required',
                'id_dosen' => 'required',
                'tanggal_mulai_kuliah' => 'required',
                'id_dokumen'           => 'required',
            ]);

            TahunAjaran::where('status', 1)->update(['status' => 0]);

            $tahun=TahunAjaran::create([
                'tahun_ajaran' => $request->tahun_ajaran,
                'status'    => 1,
            ]);

            for($i=0; $i<count($request->nama_kelas); $i++) {
                $kelas=Kelas::create([
                    'nama_kelas' => $request->nama_kelas[$i],
                    'kode_matkul' => $request->kode_matkul[$i],
                    'id_tahun_ajaran'=> $tahun->id_tahun_ajaran,
                ]);

                for($j=0; $j<count($request->id_dosen[$i]); $j++) {
                    $kelas->dosen_kelas()->attach($request->id_dosen[$i][$j]);
                }
            }

            for($i=0; $i<count($request->id_dokumen); $i++) {
                $default=DokumenPerkuliahan::where('id_dokumen', $request->id_dokumen[$i])->first()->tenggat_waktu_default;
                
                $tenggat=createTenggat($request->tanggal_mulai_kuliah, $default);
                $id = IdGenerator::generate(['table' => 'dokumen_ditugaskan', 'field'=>'id_dokumen_ditugaskan', 'length' => 6, 'prefix' => 'DT']);
                DokumenDitugaskan::create([
                    'id_dokumen_ditugaskan' => $id,
                    'id_dokumen'            => $request->id_dokumen[$i],
                    'id_tahun_ajaran'       => $tahun->id_tahun_ajaran,
                    'tenggat_waktu'         => $tenggat,
                    'pengumpulan'           => 1,
                ]);
            }

            return redirect('/penugasan')->with('success', 'Data berhasil ditambahkan');
        }
        return abort(404);
    }

    public function showJumlahKelas()
    {
        // dd(nameRoles('midleRole'));
        if(in_array(Auth::user()->role, nameRoles('superRole'))) {
            $matkul= MataKuliah::withCount('kelas as banyak_kelas')->having('banyak_kelas', '>', 0)->get();
            // dd($matkul);

            return view('penugasan.daftar-jumlah-kelas', ['matkul' => $matkul]);
        }
        return abort(404);
    }

    public function showKelas()
    {
        // dd(nameRoles('midleRole'));
        if(in_array(Auth::user()->role, nameRoles('superRole'))) {
            $kelas= Kelas::with('dosen_kelas')->get();
            // dd($kelas);

            return view('penugasan.daftar-kelas', ['kelas' => $kelas]);
        }
        return abort(404);
    }


    public function editMatkul(Request $request, $kode_matkul)
    {
        if(in_array(Auth::user()->role, nameRoles('midleRole'))) {
            $request->validate([
                'kode_matkul' => 'required',
                'nama_matkul' => 'required',
                'bobot_sks' => 'required',
                'praktikum' => 'required',
            ]);

            MataKuliah::where('kode_matkul', $kode_matkul)->update([
                'kode_matkul' => $request->kode_matkul,
                'nama_matkul' => $request->nama_matkul,
                'bobot_sks'   => $request->bobot_sks,
                'praktikum'   => $request->praktikum,
            ]);

            return redirect('/penugasan')->with('success', 'Data berhasil diubah');
        }
        return abort(404);
    }

    public function deleteMatkul(Request $request)
    {
        if(in_array(Auth::user()->role, nameRoles('midleRole'))) {
            MataKuliah::where('kode_matkul', $request->kode_matkul)->delete();
            return redirect()->back()->with('success', 'Data berhasil dihapus');
        }
        return abort(404);
    }

    public function showDokumen()
    {
        if(in_array(Auth::user()->role, nameRoles('midleRole'))) {
            $dokumen= DokumenPerkuliahan::all();

            return view('data-management.dokumen', ['dokumen' => $dokumen]);
        }
        return abort(404);
    }

    public function storeDokumen(Request $request)
    {
        if(in_array(Auth::user()->role, nameRoles('midleRole'))) {
            // dd(request()->all());
            $request->validate([
                'nama_dokumen'          => 'required',
                'tenggat_waktu_default' => 'required',
                'dikumpulkan_per'       => 'required',
                'template'              => 'mimes:docx,doc,xls,xlsx|max:3072'
            ]);

            $id = IdGenerator::generate(['table' => 'dokumen_perkuliahan', 'field'=>'id_dokumen', 'length' => 6, 'prefix' => 'DP']);
            if($request->hasFile('template')) {
                // dd($request->file('template')->extension());
                $nama_dokumen= 'Template-'.$request->nama_dokumen.'.'.$request->file('template')->extension();
                $request->file('template')->storeAs('public/template', $nama_dokumen); // 'public' adalah nama folder di storage/app/public/template

                // dd($id);
                DokumenPerkuliahan::create([
                    'id_dokumen'            => $id,
                    'nama_dokumen'          => $request->nama_dokumen,
                    'tenggat_waktu_default' => $request->tenggat_waktu_default,
                    'dikumpulkan_per'       => $request->dikumpulkan_per,
                    'template'              => $nama_dokumen,
            ]);
            } else {
                DokumenPerkuliahan::create([
                    'id_dokumen'            => $id,
                    'nama_dokumen'          => $request->nama_dokumen,
                    'tenggat_waktu_default' => $request->tenggat_waktu_default,
                    'dikumpulkan_per'       => $request->dikumpulkan_per,
            ]);
            }
            
            return redirect()->back()->with('success', 'Data berhasil ditambahkan');
        }
        return abort(404);
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