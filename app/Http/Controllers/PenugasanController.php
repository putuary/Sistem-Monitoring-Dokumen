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

class PenugasanController extends Controller
{
    public function index()
    {
        return view('super-admin.penugasan.index');
    }

    public function stepOne()
    {
        return view('super-admin.penugasan.step-one');
    }

    public function storeStepOne(Request $request)
    {
        $request->validate([
            'tahun1' => 'required',
            'tahun2' => 'required',
            'jenis' => 'required',
            'tanggal_mulai_kuliah' => 'required',
        ]);

        $request->session()->put('tahun_ajaran', $request->all());
        
        return redirect('/penugasan/buat-penugasan-baru/form-kedua');
    }

    public function stepTwo()
    {
        $data=session('tahun_ajaran');
        // dd($data['jenis']);
        $matkul=MataKuliah::matkulDibuka($data['jenis'])->get();
        $dokumen=DokumenPerkuliahan::all();
        // dd($data);
        return view('super-admin.penugasan.step-two', ['data' => $data, 'matkul' => $matkul, 'dokumen' => $dokumen]);
    }

    public function storeStepTwo(Request $request)
    {
        $request->validate([
            'kode_matkul' => 'required',
            'nama_matkul' => 'required',
            'jumlah' => 'required',
            'dokumen' => 'required',
        ]);

        $request->session()->put('data', $request->all());

        return redirect('/penugasan/buat-penugasan-baru/form-ketiga');
    }

    public function stepThree()
    {
        $data=session('data');
        $dosen=User::where('role', '!=', 'admin')->get();
        
        return view('super-admin.penugasan.step-three', ['data' => $data, 'dosen' => $dosen]);
    }

    public function storePenugasan(Request $request)
    {
        $request->validate([
            'kode_matkul'   => 'required',
            'nama_kelas'    => 'required',
            'id_dosen'      => 'required',
            'id_dokumen'    => 'required',
        ]);

        TahunAjaran::where('status', 1)->update(['status' => 0]);

        $tahun_ajar=session('tahun_ajaran');

        $tahun=TahunAjaran::create([
            'tahun_ajaran' => $tahun_ajar['tahun1'].'/'.$tahun_ajar['tahun2'].' '.$tahun_ajar['jenis'],
            'status'    => 1,
        ]);

        for($i=0; $i<count($request->nama_kelas); $i++) {
            $kelas=Kelas::create([
                'nama_kelas' => $request->nama_kelas[$i],
                'kode_matkul' => $request->kode_matkul[$i],
                'id_tahun_ajaran'=> $tahun->id_tahun_ajaran,
            ]);

            foreach($request->id_dosen[$i] as $dosen) {
                $kelas->dosen_kelas()->attach($dosen);
            }
        }

        $kelas=Kelas::with('dosen_kelas')->KelasAktif()->get();

        $matkul=MataKuliah::with(['kelas' => function($query) {
                $query->with('dosen_kelas')->KelasAktif();
            }])->whereHas('kelas', function($query) {
                $query->KelasAktif();
            })->get();
        
        $dokumen_perkuliahan=DokumenPerkuliahan::whereIn('id_dokumen', $request->id_dokumen)->get();

        foreach($dokumen_perkuliahan as $key => $dokumen) {
            $tenggat=createTenggat($tahun_ajar['tanggal_mulai_kuliah'], $dokumen->tenggat_waktu_default);
           
            $dokumen_ditugaskan=DokumenDitugaskan::create([
                'id_dokumen'            => $dokumen->id_dokumen,
                'id_tahun_ajaran'       => $tahun->id_tahun_ajaran,
                'tenggat_waktu'         => $tenggat,
                'pengumpulan'           => 1,
                'dikumpulkan_per'       => $dokumen->dikumpulkan_per,
                'dikumpul'              => $request->dikumpul[$key],
            ]);

            if($dokumen_ditugaskan->dikumpulkan_per == 1) {
                foreach($kelas as $kls) {
                    $dokumen_kelas = $kls->dokumen_kelas()->create([
                        'id_dokumen_ditugaskan' => $dokumen_ditugaskan->id_dokumen_ditugaskan,
                        'file_dokumen' => null,
                        'waktu_pengumpulan' => null,
                    ]);
                    foreach($kls->dosen_kelas as $dsn) {
                        $dokumen_kelas->scores()->create([
                            'id_dosen'        => $dsn->id,
                            'id_tahun_ajaran' => $kls->id_tahun_ajaran,
                            'score'           => 0,
                            'status'          => 0,
                        ]);
                    }
                }
            } else {
                foreach($matkul as $mkl) {
                    if($mkl->praktikum == 0  && $dokumen->id_dokumen == 'DP00000003') {
                        continue; 
                    }

                    $dokumen_matkul = $mkl->dokumen_matkul()->create([
                        'id_dokumen_ditugaskan' => $dokumen_ditugaskan->id_dokumen_ditugaskan,
                        'file_dokumen' => null,
                        'waktu_pengumpulan' => null,
                    ]);
                    $dosen_id = array();
                    foreach($mkl->kelas as $kls) {
                        $kls->kelas_dokumen_matkul()->attach($dokumen_matkul->id_dokumen_matkul);

                        foreach($kls->dosen_kelas as $dsn) {
                            if(!in_array($dsn->id, $dosen_id)) {
                                
                               $dokumen_matkul->scores()->create([
                                    'id_dosen'        => $dsn->id,
                                    'id_tahun_ajaran' => $kls->id_tahun_ajaran,
                                    'score'           => 0,
                                    'status'          => 0,
                                ]);
        
                                array_push($dosen_id, $dsn->id);
                            }
                        }
                    }
                }
            }
        }

        $request->session()->forget('tahun_ajaran');
        $request->session()->forget('data');    
                        
        return redirect('/penugasan')->with('success', 'Data berhasil ditambahkan');
    }

    public function showJumlahKelas()
    {
        $tahun_ajaran=TahunAjaran::orderBy('id_tahun_ajaran', 'desc')->get();
        $tahun_aktif=TahunAjaran::tahunAktif()->first();

        $matkul= MataKuliah::with(['kelas'=> function($query) {
            $query->with('tahun_ajaran')->kelasTahun(request('tahun_ajaran'));
        }])->withCount(['kelas as banyak_kelas' => function($query) {
            $query->kelasTahun(request('tahun_ajaran'));
            }])->having('banyak_kelas', '>', 0)->get();

        // dd($matkul);

        return view('super-admin.penugasan.daftar-jumlah-kelas', ['matkul' => $matkul, 'tahun_ajaran' => $tahun_ajaran, 'tahun_aktif' => $tahun_aktif]);
    }
}