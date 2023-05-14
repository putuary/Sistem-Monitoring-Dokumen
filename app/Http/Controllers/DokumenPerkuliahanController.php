<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\DokumenKelas;
use App\Models\DokumenMatkul;
use App\Models\TahunAjaran;


class DokumenPerkuliahanController extends Controller
{
    public function index()
    {
        $tahun_aktif = TahunAjaran::tahunAktif()->first();
        $tahun_ajaran = TahunAjaran::orderBy('id_tahun_ajaran', 'desc')->get();

        $kelas = Kelas::with(['dokumen_kelas' => function($query) {
            $query->with('dokumen_ditugaskan')->whereNotNull('file_dokumen');
            }, 'matkul','dosen_kelas','kelas_dokumen_matkul' => function($query) {
                $query->with('dokumen_ditugaskan')->whereNotNull('file_dokumen');
            }])->kelasTahun(request('tahun_ajaran'))->get();
        $dokumen_all=mergerDokumen($kelas);

        // $dokumen_matkul=DokumenMatkul::with(['dokumen_ditugaskan', 'matkul', 'kelas_dokumen_matkul' => function($query) {
        //     $query->with('dosen_kelas');
        // }])->whereNotNull('file_dokumen')->whereHas('dokumen_ditugaskan', function ($query) {
        //     $query->dokumenTahun(request('tahun_ajaran'));
        //     })->orderBy('waktu_pengumpulan', 'asc')->get();

        // $dokumen_kelas=DokumenKelas::with(['dokumen_ditugaskan','kelas' => function($query) {
        //     $query->with(['matkul', 'dosen_kelas']);
        //     }])->whereNotNull('file_dokumen')->whereHas('dokumen_ditugaskan', function ($query) {
        //     $query->dokumenTahun(request('tahun_ajaran'));
        //     })->orderBy('waktu_pengumpulan', 'asc')->get();

        // $dokumen=mergeDokumen($dokumen_kelas, $dokumen_matkul);
        // dd($dokumen_kelas);

        return view('dosen.dokumen.dokumen-perkuliahan', ['tahun_aktif' => $tahun_aktif, 'tahun_ajaran' => $tahun_ajaran, 'dokumen' => $dokumen_all]);
    }
}