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

            $kelas=Kelas::KelasAktif()->get();

            $matkul=MataKuliah::with(['kelas' => function($query) {
                    $query->KelasAktif();
                }])->whereHas('kelas', function($query) {
                    $query->KelasAktif();
                })->get();


            for($i=0; $i<count($request->id_dokumen); $i++) {
                $dokumen=DokumenPerkuliahan::where('id_dokumen', $request->id_dokumen[$i])->first();
                
                $tenggat=createTenggat($request->tanggal_mulai_kuliah, $dokumen->tenggat_waktu_default);
                $id = IdGenerator::generate(['table' => 'dokumen_ditugaskan', 'field'=>'id_dokumen_ditugaskan', 'length' => 6, 'prefix' => 'DT']);
                DokumenDitugaskan::create([
                    'id_dokumen_ditugaskan' => $id,
                    'id_dokumen'            => $request->id_dokumen[$i],
                    'id_tahun_ajaran'       => $tahun->id_tahun_ajaran,
                    'tenggat_waktu'         => $tenggat,
                    'pengumpulan'           => 1,
                ]);

                if($dokumen->dikumpulkan_per == 1) {
                    foreach($kelas as $kls) {
                        // $id_dokumen_dikumpul=IdGenerator::generate(['table' => 'dokumen_dikumpul', 'field'=>'id_dokumen_dikumpul', 'length' => 10, 'prefix' => 'DK']);
                        $kls->dokumen_dikumpul()->create([
                            // 'id_dokumen_dikumpul' => $id_dokumen_dikumpul,
                            'id_dokumen_ditugaskan' => $id,
                            'file_dokumen' => null,
                            'waktu_pengumpulan' => null,
                        ]);
                    }
                } else {
                    foreach($matkul as $mkl) {
                        if($mkl->praktikum == 0  && $request->id_dokumen[$i] == 'DP0003') {
                            continue; 
                        }
                        foreach($mkl->kelas as $kls) {
                            // $id_dokumen_dikumpul=IdGenerator::generate(['table' => 'dokumen_dikumpul', 'field'=>'id_dokumen_dikumpul', 'length' => 10, 'prefix' => 'DK']);
                            $kls->dokumen_dikumpul()->create([
                                // 'id_dokumen_dikumpul' => $id_dokumen_dikumpul,
                                'id_dokumen_ditugaskan' => $id,
                                'file_dokumen' => null,
                                'waktu_pengumpulan' => null,
                            ]);
                        }
                    }
                }
            }
                        
        return redirect('/penugasan')->with('success', 'Data berhasil ditambahkan');
    }

    public function showJumlahKelas()
    {
            $matkul= MataKuliah::with('kelas')->withCount(['kelas as banyak_kelas' => function($query) {
                $query->KelasAktif();
             }])->having('banyak_kelas', '>', 0)->get();

            // dd($matkul);

            return view('penugasan.daftar-jumlah-kelas', ['matkul' => $matkul]);
    }

    public function showKelas()
    {
            $kelas= Kelas::with(['tahun_ajaran', 'matkul', 'dosen_kelas'])->KelasAktif()->get();
            // dd($kelas);

            return view('penugasan.daftar-kelas', ['kelas' => $kelas]);
    }
}