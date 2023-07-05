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
            }, 'matkul','dosen_kelas'=>function($query) {
                $query->withTrashed();
            }, 'kelas_dokumen_matkul' => function($query) {
                $query->with('dokumen_ditugaskan')->whereNotNull('file_dokumen');
            }])->kelasTahun(request('tahun_ajaran'))->get();
        $dokumen_all=mergerDokumen($kelas);

        return view('dosen.dokumen.dokumen-perkuliahan', ['tahun_aktif' => $tahun_aktif, 'tahun_ajaran' => $tahun_ajaran, 'dokumen' => $dokumen_all]);
    }
}