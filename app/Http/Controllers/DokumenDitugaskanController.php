<?php

namespace App\Http\Controllers;

use App\Models\DokumenDitugaskan;
use App\Models\DokumenKelas;
use App\Models\DokumenMatkul;
use App\Models\DokumenPerkuliahan;
use App\Models\Gamifikasi;
use App\Models\Kelas;
use App\Models\MatkulDibuka;
use App\Models\Score;
use App\Models\TahunAjaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DokumenDitugaskanController extends Controller
{
    public function index()
    {
        $tahun_ajaran=TahunAjaran::orderBy('id_tahun_ajaran', 'desc')->get();

        $tahun_aktif=TahunAjaran::tahunAktif()->first();
        
        $dokumen_perkuliahan=DokumenPerkuliahan::all();

        $dokumen=DokumenDitugaskan::dokumenTahun(request('tahun_ajaran'))->get();
        // dd($dokumen);

        return view('super-admin.dokumen-ditugaskan.index', ['tahun_ajaran' => $tahun_ajaran, 'tahun_aktif' => $tahun_aktif, 'dokumen_perkuliahan' => $dokumen_perkuliahan, 'dokumen' => $dokumen,]);
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

        $tenggat_waktu=Carbon::parse($request->tenggat_waktu);
        if($tenggat_waktu->isPast()) {
            return redirect()->back()->with('failed', 'Tenggat waktu tidak boleh kurang dari sekarang!');
        }

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
                ]);
                foreach($kls->dosen_kelas as $dsn) {
                    $dokumen_kelas->scores()->create([
                        'id_dosen'        => $dsn->id,
                        'kode_kelas'      => $kls->kode_kelas,
                        'id_tahun_ajaran' => $kls->id_tahun_ajaran,
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
                ]);
            
                foreach($mkl->kelas as $kls) {
                    $kls->kelas_dokumen_matkul()->attach($dokumen_matkul->id_dokumen_matkul);

                    foreach($kls->dosen_kelas as $dsn) {
                        $dokumen_matkul->scores()->create([
                            'id_dosen'        => $dsn->id,
                            'kode_kelas'      => $kls->kode_kelas,
                            'id_tahun_ajaran' => $kls->id_tahun_ajaran,
                        ]);
                    }
                }
            }
        }
        return redirect()->back()->with('success', 'Dokumen berhasil ditugaskan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tenggat_waktu' => 'required',
        ]);

        $tenggat=Carbon::parse($request->tenggat_waktu);
        
        if(Carbon::now()->isAfter($tenggat)) {
            return redirect()->back()->with('failed', 'Tenggat waktu tidak boleh kurang dari sekarang!');
        }
        $dokumen=DokumenDitugaskan::with(['dokumen_matkul' => function ($query) {
            $query->with('scores')->filter('terlambat');
        }, 'dokumen_kelas'=> function($query) {
            $query->with('scores')->filter('terlambat');
        }])->find($id);
        // dd($dokumen->dokumen_matkul, $dokumen->dokumen_kelas);
        
        $dokumen->update([
            'tenggat_waktu' => $request->tenggat_waktu,
        ]);

        if($dokumen->dikumpulkan_per == 0) {
            foreach($dokumen->dokumen_matkul as $dokumen_matkul) {
                $data=Gamifikasi::submitScore($request->tenggat_waktu, $dokumen_matkul->waktu_pengumpulan, true, $dokumen_matkul->id_dokumen_matkul, $dokumen->id_dokumen_ditugaskan);
                  
                $dokumen_matkul->scores()->update([
                    'poin'       => $data['poin'],
                ]);
            }

        } else {
            foreach($dokumen->dokumen_kelas as $dokumen_kelas) {
                $data=Gamifikasi::submitScore($request->tenggat_waktu, $dokumen_kelas->waktu_pengumpulan, false, $dokumen_kelas->id_dokumen_kelas, $dokumen->id_dokumen_ditugaskan);

                $dokumen_kelas->scores()->update([
                    'poin'           => $data['poin'],
                ]);
            }
        }

        return redirect()->back()->with('success', 'Tenggat Waktu berhasil diubah');
    }

    public function editPengumpulan(Request $request) {
        $request->validate([
            'id_dokumen_ditugaskan' => 'required',
        ]);

        $dokumen=DokumenDitugaskan::find($request->id_dokumen_ditugaskan);
        // dd($dokumen->pengumpulan);
        if($dokumen->pengumpulan == 1) {
            $dokumen->update([
                'pengumpulan' => 0,
            ]);
            return response()->json([
                'pengumpulan' => false,
                'status' => 'success',
                'message' => 'Pengumpulan dimatikan!',
            ]);
        } else {
            $dokumen->update([
                'pengumpulan' => 1,
            ]);
            return response()->json([
                'pengumpulan' => true,
                'status' => 'success',
                'message' => 'Pengumpulan diaktifkan!',
            ]);
        }
    }

    public function destroy($id)
    {
        $dokumen=DokumenDitugaskan::find($id);
        if($dokumen->dikumpulkan_per == 1) {
            $dokumenWithKelas=$dokumen->load('dokumen_kelas');

            $scoreable_id=[];
            foreach($dokumenWithKelas->dokumen_kelas as $dokumen_kelas) {
                array_push($scoreable_id, $dokumen_kelas->id_dokumen_kelas);
            }
        } else {
            $dokumenWithMatkul=$dokumen->load('dokumen_matkul');

            $scoreable_id=[];
            foreach($dokumenWithMatkul->dokumen_matkul as $dokumen_matkul) {
                array_push($scoreable_id, $dokumen_matkul->id_dokumen_matkul);
            }
        }
        Score::whereIn('scoreable_id', $scoreable_id)->delete();
        $dokumen_kelas_not_null=DokumenKelas::with(['kelas' => function ($query) {
            $query->with(['matkul', 'tahun_ajaran']);
        }, 'dokumen_ditugaskan'])->where('id_dokumen_ditugaskan', $id)->whereNotNull('file_dokumen')->get();
        foreach($dokumen_kelas_not_null as $dokumen_kelas) {
            if($dokumen_kelas->dokumen_ditugaskan->dikumpul==0 ) {
                File::delete(storage_path(pathDokumen($dokumen_kelas->kelas->tahun_ajaran->tahun_ajaran, false, $dokumen_kelas->kelas->matkul->nama_matkul, $dokumen_kelas->kelas->nama_kelas).'/'.$dokumen_kelas->file_dokumen));
            } else {
                File::deleteDirectory(storage_path(pathDokumen($dokumen_kelas->kelas->tahun_ajaran->tahun_ajaran, false, $dokumen_kelas->kelas->matkul->nama_matkul, $dokumen_kelas->kelas->nama_kelas).'/'.$dokumen_kelas->file_dokumen));
            }
        }
        $dokumen_matkul_not_null=DokumenMatkul::with(['matkul', 'dokumen_ditugaskan' => function($query) {
            $query->with(['tahun_ajaran']);
        }])->where('id_dokumen_ditugaskan', $id)->whereNotNull('file_dokumen')->get();
        // dd($dokumen_matkul_not_null, $dokumen_kelas_not_null, $id);
        foreach($dokumen_matkul_not_null as $dokumen_matkul) {
            if($dokumen_matkul->dokumen_ditugaskan->dikumpul==0) {
                File::delete(storage_path(pathDokumen($dokumen_matkul->dokumen_ditugaskan->tahun_ajaran->tahun_ajaran, true, $dokumen_matkul->matkul->nama_matkul).'/'.$dokumen_matkul->file_dokumen));
            } else {
                File::deleteDirectory(storage_path(pathDokumen($dokumen_matkul->dokumen_ditugaskan->tahun_ajaran->tahun_ajaran, true, $dokumen_matkul->matkul->nama_matkul).'/'.$dokumen_matkul->file_dokumen));
            }
        }
        $dokumen->delete();

        return redirect()->back()->with('success', 'Dokumen ditugaskan berhasil dihapus');
    }
}