<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DokumenDitugaskan;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\DokumenKelas;
use App\Models\DokumenMatkul;

class KelasDiampuController extends Controller
{
    public function showKelasDiampu()
    {
        $tahun_ajaran = TahunAjaran::orderBy('id_tahun_ajaran', 'desc')->get();
        $tahun_aktif = TahunAjaran::tahunAktif()->first();
        $kelas = Kelas::with(['tahun_ajaran', 'dokumen_kelas', 'matkul','kelas_dokumen_matkul'])
                ->kelasDiampu()->kelasTahun(request('tahun_ajaran'))
                ->searchKelas(request('search'))->orderBy('kode_kelas', 'asc')->get();
        
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

    public function showRiwayat() {
        
        $tahun_ajaran = TahunAjaran::orderBy('id_tahun_ajaran', 'desc')->get();
        $tahun_aktif = TahunAjaran::tahunAktif()->first();
        // union two table

        // $kelas = Kelas::with(['dokumen_kelas' => function($query) {
        //             $query->with(['dokumen_ditugaskan', 'scores' => function($query) {
        //                 $query->where('id_dosen', auth()->user()->id)->whereColumn('scores.kode_kelas', 'kelas.kode_kelas'); 
        //             }])->filter(request('filter'));
        //         }, 'matkul','dosen_kelas','kelas_dokumen_matkul' => function($query) {
        //             $query->with(['dokumen_ditugaskan', 'scores' => function($query) {
        //                 $query->where('id_dosen', auth()->user()->id)->whereColumn('scores.kode_kelas', 'kelas.kode_kelas');
        //             }])->filter(request('filter'));
        //         }])->kelasDiampu()->kelasTahun(request('tahun_ajaran'))->get();
        //                 dd($kelas);
        $kelas = Kelas::with(['dokumen_kelas' => function($query) {
            $query->with(['dokumen_ditugaskan', 'scores' => function($query) {
                $query->where('id_dosen', auth()->user()->id);
            }])->filter(request('filter'));
        }, 'matkul', 'dosen_kelas', 'kelas_dokumen_matkul' => function($query) {
            $query->with(['dokumen_ditugaskan', 'scores' => function($query) {
                $query->where('id_dosen', auth()->user()->id);
            }])->filter(request('filter'));
        }])->kelasDiampu()->kelasTahun(request('tahun_ajaran'))->get();
        // dd($kelas);
        $dokumen=mergerDokumen($kelas);

        return view('dosen.riwayat.riwayat', [
            'tahun_ajaran' => $tahun_ajaran,
            'tahun_aktif' => $tahun_aktif,
            'dokumen' => $dokumen,
        ]);

        // return view('dosen.riwayat.index', [
        //     'tahun_ajaran' => $tahun_ajaran,
        //     'tahun_aktif' => $tahun_aktif,
        //     'kelas' => $kelas,
        // ]);
    }
}