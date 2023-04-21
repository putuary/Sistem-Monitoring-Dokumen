<?php

namespace App\Http\Controllers;

use App\Models\DokumenDitugaskan;
use App\Models\DokumenMatkul;
use App\Models\DokumenPerkuliahan;
use App\Models\Kelas;
use App\Models\MataKuliah;
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

        $dokumen=DokumenDitugaskan::with(['dokumen_perkuliahan'])->dokumenTahun(request('tahun_ajaran'))->get();
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
            $id=IdGenerator::generate(['table' => 'dokumen_ditugaskan', 'field'=>'id_dokumen_ditugaskan', 'length' => 6, 'prefix' => 'DT']);
            $dokumen=DokumenDitugaskan::create([
                'id_dokumen_ditugaskan' => $id,
                'id_dokumen'            => $request->id_dokumen,
                'id_tahun_ajaran'       => $tahun_aktif->id_tahun_ajaran,
                'tenggat_waktu'         => $request->tenggat_waktu,
                'pengumpulan'           => 1,
                'dikumpulkan_per'       => $dokumen_perkuliahan->dikumpulkan_per,
                'dikumpul'              => $request->dikumpul,
            ]);
        } catch (\Throwable $th) {
            return redirect()->back()->with('failed', 'Data yang dimasukkan sudah ada!');
        }

        $kelas=Kelas::KelasAktif()->get();

        $matkul=MataKuliah::with(['kelas' => function($query) {
                $query->KelasAktif();
            }])->whereHas('kelas', function($query) {
                $query->KelasAktif();
            })->get();

        if($dokumen->dikumpulkan_per == 1) {
            foreach($kelas as $kls) {
                // $id_dokumen_dikumpul=IdGenerator::generate(['table' => 'dokumen_dikumpul', 'field'=>'id_dokumen_dikumpul', 'length' => 10, 'prefix' => 'DK']);
                $kls->dokumen_kelas()->create([
                    'id_dokumen_ditugaskan' => $id,
                    'file_dokumen' => null,
                    'waktu_pengumpulan' => null,
                ]);
            }
        } else {
            foreach($matkul as $mkl) {
                if($mkl->praktikum == 0  && $request->id_dokumen == 'DP0003') {
                    continue; 
                }
                // $id_dokumen_dikumpul=IdGenerator::generate(['table' => 'dokumen_dikumpul', 'field'=>'id_dokumen_dikumpul', 'length' => 10, 'prefix' => 'DK']);
                $dokumen_matkul=$mkl->dokumen_matkul()->create([
                    'id_dokumen_ditugaskan' => $id,
                    'file_dokumen' => null,
                    'waktu_pengumpulan' => null,
                ]);
                foreach($mkl->kelas as $kls) {
                    $kls->kelas_dokumen_matkul()->attach($dokumen_matkul->id_dokumen_matkul);
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
        $dokumen=DokumenDitugaskan::with(['dokumen_matkul', 'dokumen_kelas'])->find($id);
        if($dokumen->dikumpulkan_per ==0) {
            foreach($dokumen->dokumen_matkul as $dokumen_matkul) {
                $dokumen_matkul->kelas_dokumen_matkul()->detach();
            }
            $dokumen->dokumen_matkul()->delete();
        } else {
            $dokumen->dokumen_kelas()->delete();
        }
        $dokumen->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}