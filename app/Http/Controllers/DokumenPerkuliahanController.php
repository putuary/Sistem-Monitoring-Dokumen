<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DokumenDitugaskan;
use App\Models\TahunAjaran;


class DokumenPerkuliahanController extends Controller
{
    public function index()
    {
        $tahun_ajaran = TahunAjaran::orderBy('id_tahun_ajaran', 'desc')->get();
        
        $dokumen=DokumenDitugaskan::with(['dokumen_perkuliahan', 'dokumen_matkul' => function($query) {
            $query->whereNotNull('file_dokumen');
        }, 'dokumen_kelas' => function ($query) {
            $query->whereNotNull('file_dokumen');
        }])->dokumenTahun(request('tahun_ajaran'))->get();
        
        // dd($dokumen);

        return view('dosen.dokumen.dokumen-perkuliahan', ['tahun_ajaran' => $tahun_ajaran, 'dokumen' => $dokumen]);
    }

}