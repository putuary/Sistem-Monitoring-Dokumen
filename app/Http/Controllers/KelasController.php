<?php

namespace App\Http\Controllers;

use App\Models\DokumenDitugaskan;
use App\Models\DokumenMatkul;
use App\Models\Kelas;
use App\Models\MataKuliah;
use App\Models\MatkulDibuka;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Http\Request;

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

        $kelas= Kelas::with(['tahun_ajaran', 'matkul', 'dosen_kelas'])->kelasTahun(request('tahun_ajaran'))->orderBy('id_matkul_dibuka', 'asc')->get();
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
            $query->with('matkul')->where('dokumen_matkul.id_matkul_dibuka', $kelas->id_matkul_dibuka);
        }])->dokumenAktif()->get();
        // dd($dokumen_ditugaskan);


        foreach($dokumen_ditugaskan as $key => $dokumen) {

            if($dokumen->dikumpulkan_per == 1) {
                
                $kelas->dokumen_kelas()->create([
                    'id_dokumen_ditugaskan' => $dokumen->id_dokumen_ditugaskan,
                    'file_dokumen' => null,
                    'waktu_pengumpulan' => null,
                ]);
            } else {
                if($matkul_dibuka->praktikum == 0  && $dokumen->dokumen_perkuliahan->id_dokumen == 'DP0003') {
                    continue; 
                }

                if(count($dokumen->dokumen_matkul) == 0) {
                    $dokumen_matkul=$dokumen->dokumen_matkul()->create([
                        'id_dokumen_ditugaskan' => $dokumen->id_dokumen_ditugaskan,
                        'id_matkul_dibuka' => $kelas->id_matkul_dibuka,
                        'file_dokumen' => null,
                        'waktu_pengumpulan' => null,
                    ]);
                    
                    $kelas->kelas_dokumen_matkul()->attach($dokumen_matkul->id_dokumen_matkul);
                } else {
                    $kelas->kelas_dokumen_matkul()->attach($dokumen->dokumen_matkul[0]->id_dokumen_matkul);
                }
            }
        }

        return redirect()->back()->with('success', 'Data berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kelas' => 'required',
        ]);

        $kelas=Kelas::find($id);
       
        if($request->has('id_dosen')) {
            $kelas->dosen_kelas()->detach();
            $kelas->update([
                'nama_kelas' => $request->nama_kelas
            ]);
            foreach ($request->id_dosen as $key => $value) {
                $kelas->dosen_kelas()->attach($value);
            }
        } else {
            $kelas->update([
                'nama_kelas' => $request->nama_kelas
            ]);
        }
        return redirect()->back()->with('success', 'Data berhasil diubah');
    }

    public function destroy($id)
    {
        $kelas=Kelas::find($id)->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}