<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DokumenDitugaskan;
use App\Models\DokumenKelas;
use App\Models\DokumenMatkul;
use App\Models\TahunAjaran;


class DokumenPerkuliahanController extends Controller
{
    public function index()
    {
        $tahun_aktif = TahunAjaran::tahunAktif()->first();
        $tahun_ajaran = TahunAjaran::orderBy('id_tahun_ajaran', 'desc')->get();

        // $dokumen = DokumenDitugaskan::with(['dokumen_matkul' => function($query) {
        //         $query->with(['matkul', 'kelas_dokumen_matkul' => function($query) {
        //             $query->with('dosen_kelas');
        //         }]);
        //     }, 'dokumen_kelas' => function($query) {
        //         $query->with(['kelas' => function($query) {
        //             $query->with(['matkul', 'dosen_kelas']);
        //             }]);
        //     }])->whereHas('dokumen_matkul', function ($query) {
        //         $query->whereNotNull('file_dokumen');
        //     })->orWhereHas('dokumen_kelas', function ($query) {
        //         $query->whereNotNull('file_dokumen');
        //     })->dokumenTahun(request('tahun_ajaran'))->orderBy('nama_dokumen', 'asc')->get();

        $dokumen_matkul=DokumenMatkul::with(['dokumen_ditugaskan', 'matkul', 'kelas_dokumen_matkul' => function($query) {
            $query->with('dosen_kelas');
        }])->whereNotNull('file_dokumen')->whereHas('dokumen_ditugaskan', function ($query) {
            $query->dokumenTahun(request('tahun_ajaran'));
            })->orderBy('waktu_pengumpulan', 'asc')->get();

        $dokumen_kelas=DokumenKelas::with(['dokumen_ditugaskan','kelas' => function($query) {
            $query->with(['matkul', 'dosen_kelas']);
            }])->whereNotNull('file_dokumen')->whereHas('dokumen_ditugaskan', function ($query) {
            $query->dokumenTahun(request('tahun_ajaran'));
            })->orderBy('waktu_pengumpulan', 'asc')->get();

        $dokumen=mergeDokumen($dokumen_kelas, $dokumen_matkul);
        // dd($dokumen_kelas);

        return view('dosen.dokumen.dokumen-perkuliahan', ['tahun_aktif' => $tahun_aktif, 'tahun_ajaran' => $tahun_ajaran, 'dokumen' => $dokumen]);
    }

    public function showDokumenMultiple($id_dokumen) {
       
        $dokumen=DokumenDikumpulController::showProfilDokumen($id_dokumen);
        // $data=scandir('D:\Kuliah\TA\Program\scele-if - Copy\storage\app/Dokumen_Perkuliahan/2023-2024 Ganjil/Teori Bahasa Formal dan Otomata/Portofolio Perkuliahan_Teori Bahasa Formal dan Otomata', SCANDIR_SORT_ASCENDING);
        // dd($data);

        $storage = array_diff(scandir($dokumen->path_multiple, SCANDIR_SORT_ASCENDING), array('.', '..'));

        return view('dosen.dokumen.tampil-dokumen-multiple', [
            'title'      => $dokumen->nama_dokumen,
            'id_dokumen' => $id_dokumen,
            'nama_files' => $storage
        ]);
    }
}