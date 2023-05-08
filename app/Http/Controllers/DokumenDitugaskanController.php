<?php

namespace App\Http\Controllers;

use App\Models\DokumenDitugaskan;
use App\Models\DokumenPerkuliahan;
use App\Models\Kelas;
use App\Models\MataKuliah;
use App\Models\MatkulDibuka;
use App\Models\Score;
use App\Models\TahunAjaran;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;

class DokumenDitugaskanController extends Controller
{
    public function index()
    {
        $tahun_ajaran=TahunAjaran::orderBy('id_tahun_ajaran', 'desc')->get();

        $tahun_aktif=TahunAjaran::tahunAktif()->first();
        
        $dokumen_perkuliahan=DokumenPerkuliahan::all();

        $dokumen=DokumenDitugaskan::dokumenTahun(request('tahun_ajaran'))->get();
        // dd($dokumen);

        return view('super-admin.penugasan.dokumen-ditugaskan', ['tahun_ajaran' => $tahun_ajaran, 'tahun_aktif' => $tahun_aktif, 'dokumen_perkuliahan' => $dokumen_perkuliahan, 'dokumen' => $dokumen,]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_dokumen'    => 'required',
            'tenggat_waktu' => 'required',
            'dikumpul'      => 'required',
        ]);

        $tahun_aktif=TahunAjaran::tahunAktif()->first();
        $dokumen_perkuliahan=DokumenPerkuliahan::find($request->id_dokumen);

        try {
            $dokumen=DokumenDitugaskan::create([
                'id_dokumen'            => $request->id_dokumen,
                'id_tahun_ajaran'       => $tahun_aktif->id_tahun_ajaran,
                'nama_dokumen'          => $dokumen_perkuliahan->nama_dokumen,
                'tenggat_waktu'         => $request->tenggat_waktu,
                'pengumpulan'           => 1,
                'dikumpulkan_per'       => $dokumen_perkuliahan->dikumpulkan_per,
                'dikumpul'              => $request->dikumpul,
            ]);
        } catch (\Throwable $th) {
            return redirect()->back()->with('failed', 'Dokumen sudah ditugaskan!');
        }

        $kelas=Kelas::KelasAktif()->get();

        $matkul=MatkulDibuka::with('kelas')->matkulAktif()->get();

        if($dokumen->dikumpulkan_per == 1) {
            foreach($kelas as $kls) {

                $dokumen_kelas=$kls->dokumen_kelas()->create([
                    'id_dokumen_ditugaskan' => $dokumen->id_dokumen_ditugaskan,
                    'file_dokumen' => null,
                    'waktu_pengumpulan' => null,
                ]);
                foreach($kls->dosen_kelas as $dsn) {
                    $dokumen_kelas->scores()->create([
                        'id_dosen'        => $dsn->id,
                        'id_tahun_ajaran' => $kls->id_tahun_ajaran,
                        'score'           => 0,
                    ]);
                }
            }
        } else {
            foreach($matkul as $mkl) {
                if($mkl->praktikum == 0  && $request->id_dokumen == 'DP0003') {
                    continue; 
                }
                // $id_dokumen_dikumpul=IdGenerator::generate(['table' => 'dokumen_dikumpul', 'field'=>'id_dokumen_dikumpul', 'length' => 10, 'prefix' => 'DK']);
                $dokumen_matkul=$mkl->dokumen_matkul()->create([
                    'id_dokumen_ditugaskan' => $dokumen->id_dokumen_ditugaskan,
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
                            ]);
    
                            array_push($dosen_id, $dsn->id);
                        }
                    }
                }
            }
        }
        return redirect()->back()->with('success', 'Data berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tenggat_waktu' => 'required',
            'dikumpul'      => 'required',
        ]);

        $dokumen=DokumenDitugaskan::find($id);
        $dokumen->update([
            'tenggat_waktu' => $request->tenggat_waktu,
            'dikumpul'      => $request->dikumpul,
        ]);

        return redirect()->back()->with('success', 'Data berhasil diubah');
    }

    public function destroy($id)
    {
        $dokumen=DokumenDitugaskan::find($id);
        if($dokumen->dikumpulkan_per == 1) {
            $dokumenWithKelas=$dokumen->load('dokumen_kelas');

            $id=[];
            foreach($dokumenWithKelas->dokumen_kelas as $dokumen_kelas) {
                array_push($id, $dokumen_kelas->id_dokumen_kelas);
            }
        } else {
            $dokumenWithMatkul=$dokumen->load('dokumen_matkul');

            $id=[];
            foreach($dokumenWithMatkul->dokumen_matkul as $dokumen_matkul) {
                array_push($id, $dokumen_matkul->id_dokumen_matkul);
            }
        }
        Score::whereIn('scoreable_id', $id)->delete();
        
        $dokumen->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}