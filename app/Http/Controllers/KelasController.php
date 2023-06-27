<?php

namespace App\Http\Controllers;

use App\Models\DokumenDitugaskan;
use App\Models\DokumenMatkul;
use App\Models\Kelas;
use App\Models\MataKuliah;
use App\Models\MatkulDibuka;
use App\Models\Score;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class KelasController extends Controller
{
    public function index()
    {
        $tahun_ajaran=TahunAjaran::orderBy('id_tahun_ajaran', 'desc')->get();

        $tahun_aktif=TahunAjaran::tahunAktif()->first();
        if(isset($tahun_aktif)) {
            if(like_match('%Ganjil', $tahun_aktif->tahun_ajaran)) {
                $matkul= MataKuliah::matkulDibuka('Ganjil')->get();
            } else if(like_match('%Genap', $tahun_aktif->tahun_ajaran)) {
                $matkul= MataKuliah::matkulDibuka('Genap')->get();
            } else {
                $matkul= MataKuliah::matkulDibuka('Pendek')->get();
            }
        }
        
        $dosen=User::where('role', '!=', 'admin')->get();
        // dd($dosen);

        $kelas= Kelas::with(['tahun_ajaran', 'matkul', 'dosen_kelas' => function($query){
            $query->withTrashed();
        }])->kelasTahun(request('tahun_ajaran'))->orderBy('id_matkul_dibuka', 'asc')->get();
        // dd($kelas);

        return view('super-admin.penugasan.daftar-kelas', ['tahun_ajaran' => $tahun_ajaran, 'tahun_aktif' => $tahun_aktif, 'matkul' => ($matkul ?? [] ), 'dosen' => $dosen, 'kelas' => $kelas,]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_matkul' => 'required',
            'nama_kelas' => 'required',
            'id_dosen' => 'required',
        ]);

        $tahun_aktif=TahunAjaran::tahunAktif()->first();

        $matkul_dibuka= MatkulDibuka::where('kode_matkul', $request->kode_matkul)->where('id_tahun_ajaran', $tahun_aktif->id_tahun_ajaran)->first();
        if(!isset($matkul_dibuka)) {
            $matkul=MataKuliah::where('kode_matkul', $request->kode_matkul)->first();
            $matkul_dibuka=MatkulDibuka::create([
                'kode_matkul'       => $request->kode_matkul,
                'id_tahun_ajaran'   => $tahun_aktif->id_tahun_ajaran,
                'nama_matkul'       => $matkul->nama_matkul,
                'bobot_sks'         => $matkul->bobot_sks,
                'praktikum'         => $matkul->praktikum,
            ]);
        }
        
        // dd($matkul_dibuka);

        $kelas=Kelas::create([
            'nama_kelas'        => $request->nama_kelas,
            'id_matkul_dibuka'  => $matkul_dibuka->id_matkul_dibuka,
            'id_tahun_ajaran'   => $tahun_aktif->id_tahun_ajaran,
        ]);

        for($i=0; $i<count($request->id_dosen); $i++) {
            $kelas->dosen_kelas()->attach($request->id_dosen[$i]);
        }
        
        $dokumen_ditugaskan=DokumenDitugaskan::with(['dokumen_perkuliahan', 'dokumen_matkul' => function($query) use ($kelas) {
            $query->with('scores')->where('dokumen_matkul.id_matkul_dibuka', $kelas->id_matkul_dibuka);
        }])->dokumenAktif()->get();
        // dd($dokumen_ditugaskan);


        foreach($dokumen_ditugaskan as $key => $dokumen) {
            if($matkul_dibuka->praktikum == 0  && $dokumen->id_dokumen == 'DP00000003') {
                continue; 
            }

            if($dokumen->dikumpulkan_per == 1) {
                
                $dokumen_kelas=$kelas->dokumen_kelas()->create([
                    'id_dokumen_ditugaskan' => $dokumen->id_dokumen_ditugaskan,
                    'file_dokumen' => null,
                    'waktu_pengumpulan' => null,
                ]);
                for($i=0; $i<count($request->id_dosen); $i++) {
                    $dokumen_kelas->scores()->create([
                        'id_dosen'        => $request->id_dosen[$i],
                        'kode_kelas'      => $kelas->kode_kelas,
                        'id_tahun_ajaran' => $kelas->id_tahun_ajaran,
                        'poin'            => null,
                    ]);
                }
            } else {
                if(count($dokumen->dokumen_matkul) == 0) {
                    $dokumen_matkul=$dokumen->dokumen_matkul()->create([
                        'id_dokumen_ditugaskan' => $dokumen->id_dokumen_ditugaskan,
                        'id_matkul_dibuka'      => $kelas->id_matkul_dibuka,
                        'file_dokumen'          => null,
                        'waktu_pengumpulan'     => null,
                    ]);
                    
                    $kelas->kelas_dokumen_matkul()->attach($dokumen_matkul->id_dokumen_matkul);

                    for($i=0; $i<count($request->id_dosen); $i++) {
                        $dokumen_matkul->scores()->create([
                            'id_dosen'        => $request->id_dosen[$i],
                            'kode_kelas'      => $kelas->kode_kelas,
                            'id_tahun_ajaran' => $kelas->id_tahun_ajaran,
                            'poin'            => null,
                        ]);
                    }

                } else {
                    $kelas->kelas_dokumen_matkul()->attach($dokumen->dokumen_matkul[0]->id_dokumen_matkul);

                    for($i=0; $i<count($request->id_dosen); $i++) {
                        $dokumen->dokumen_matkul[0]->scores()->create([
                            'id_dosen'        => $request->id_dosen[$i],
                            'kode_kelas'      => $kelas->kode_kelas,
                            'id_tahun_ajaran' => $kelas->id_tahun_ajaran,
                            'poin'            => $dokumen->dokumen_matkul[0]->scores[0]->poin,
                        ]);
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Kelas berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $kelas=Kelas::with(['dosen_kelas' => function($query) {
            $query->withTrashed();
        },'kelas_dokumen_matkul', 'dokumen_kelas'])->find($id);
        // dd($kelas);
        $dokumen_kelas_not_null=$kelas->dokumen_kelas->where('file_dokumen', '!=', null)->count();
        $kelas_dokumen_matkul_not_null=$kelas->kelas_dokumen_matkul->where('file_dokumen', '!=', null)->count();
       
        if($request->has('nama_kelas') && $request->has('id_dosen')) {
            if($dokumen_kelas_not_null > 0 || $kelas_dokumen_matkul_not_null > 0) {
                return redirect()->back()->with('failed', 'Kelas sudah memiliki dokumen, tidak dapat mengubah nama');
            }
            $kelas->update([
                'nama_kelas' => $request->nama_kelas
            ]);

            $score_kelas=Score::where('kode_kelas', $kelas->kode_kelas)->where('id_dosen', $kelas->dosen_kelas[0]->id)->get();
            // dd($score_kelas);

            $dosenInClass=[];
            foreach ($kelas->dosen_kelas as $key => $dosen) {
                if(!in_array($dosen->id, $request->id_dosen)) {
                    $kelas->dosen_kelas()->detach($dosen->id);
                    Score::where('kode_kelas', $kelas->kode_kelas)->where('id_dosen', $dosen->id)->delete();
                } else{
                    $dosenInClass[]=$dosen->id;
                }
            }
           
            // dd($score_kelas, $dosenInClass);

            foreach ($request->id_dosen as $key => $value) {
                if(!in_array($value, $dosenInClass)) {
                    $kelas->dosen_kelas()->attach($value);
                    foreach ($score_kelas as $key => $score) {
                        Score::create([
                            'id_dosen'        => $value,
                            'kode_kelas'      => $kelas->kode_kelas,
                            'id_tahun_ajaran' => $score->id_tahun_ajaran,
                            'scoreable_id'    => $score->scoreable_id,
                            'scoreable_type'  => $score->scoreable_type,
                            'poin'            => $score->poin,
                        ]);
                    }
                }
            }
        } else if($request->has('nama_kelas')) {
            if($dokumen_kelas_not_null > 0 || $kelas_dokumen_matkul_not_null > 0) {
                return redirect()->back()->with('failed', 'Kelas sudah memiliki dokumen, tidak dapat mengubah nama');
            }
            $kelas->update([
                'nama_kelas' => $request->nama_kelas
            ]);
        } else if($request->has('id_dosen')) {
            $score_kelas=Score::where('kode_kelas', $kelas->kode_kelas)->where('id_dosen', $kelas->dosen_kelas[0]->id)->get();
            // dd($score_kelas);

            $dosenInClass=[];
            foreach ($kelas->dosen_kelas as $key => $dosen) {
                if(!in_array($dosen->id, $request->id_dosen)) {
                    $kelas->dosen_kelas()->detach($dosen->id);
                    Score::where('kode_kelas', $kelas->kode_kelas)->where('id_dosen', $dosen->id)->delete();
                } else{
                    $dosenInClass[]=$dosen->id;
                }
            }
           
            // dd($score_kelas, $dosenInClass);

            foreach ($request->id_dosen as $key => $value) {
                if(!in_array($value, $dosenInClass)) {
                    $kelas->dosen_kelas()->attach($value);
                    foreach ($score_kelas as $key => $score) {
                        Score::create([
                            'id_dosen'        => $value,
                            'kode_kelas'      => $kelas->kode_kelas,
                            'id_tahun_ajaran' => $score->id_tahun_ajaran,
                            'scoreable_id'    => $score->scoreable_id,
                            'scoreable_type'  => $score->scoreable_type,
                            'poin'            => $score->poin,
                        ]);
                    }
                }
            }
        }
        return redirect()->back()->with('success', 'Kelas berhasil diubah');
    }

    public function destroy($id)
    {
        $kelas=Kelas::find($id);
        $count_same_matkul=Kelas::where('id_matkul_dibuka', $kelas->id_matkul_dibuka)->count();
        $kelas->load(['matkul','tahun_ajaran']);
        if($count_same_matkul == 1) {
            DokumenMatkul::where('id_matkul_dibuka', $kelas->id_matkul_dibuka)->delete();
            File::deleteDirectory(storage_path(pathDirectory($kelas->tahun_ajaran->tahun_ajaran, false, $kelas->matkul->nama_matkul)));
        } else {
            // dd(pathDirectory($kelas->tahun_ajaran->tahun_ajaran, true, $kelas->matkul->nama_matkul, $kelas->nama_kelas));
            File::deleteDirectory(storage_path(pathDirectory($kelas->tahun_ajaran->tahun_ajaran, true, $kelas->matkul->nama_matkul, $kelas->nama_kelas)));
        }
        $kelas->delete();

        return redirect()->back()->with('success', 'Kelas berhasil dihapus');
    }
}