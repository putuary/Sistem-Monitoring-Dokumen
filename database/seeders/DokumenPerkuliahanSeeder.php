<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DokumenPerkuliahan;
use Haruncpi\LaravelIdGenerator\IdGenerator;


class DokumenPerkuliahanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $id = IdGenerator::generate(['table' => 'dokumen_perkuliahan', 'field'=>'id_dokumen', 'length' => 6, 'prefix' => 'DP']);
        DokumenPerkuliahan::create([
            'id_dokumen'            => $id,
            'nama_dokumen'          => 'Rencana Pembelajaran Semester',
            'tenggat_waktu_default' => -1,
            'dikumpulkan_per'       => 0,
            'template'              => null,
        ]);

        $id = IdGenerator::generate(['table' => 'dokumen_perkuliahan', 'field'=>'id_dokumen', 'length' => 6, 'prefix' => 'DP']);
        DokumenPerkuliahan::create([
            'id_dokumen'            => $id,
            'nama_dokumen'          => 'Materi Perkuliahan',
            'tenggat_waktu_default' => 1,
            'dikumpulkan_per'       => 0,
            'template'              => null,
        ]);

        $id = IdGenerator::generate(['table' => 'dokumen_perkuliahan', 'field'=>'id_dokumen', 'length' => 6, 'prefix' => 'DP']);
        DokumenPerkuliahan::create([
            'id_dokumen'            => $id,
            'nama_dokumen'          => 'Modul Praktikum',
            'tenggat_waktu_default' => 1,
            'dikumpulkan_per'       => 0,
            'template'              => null,
        ]);

        $id = IdGenerator::generate(['table' => 'dokumen_perkuliahan', 'field'=>'id_dokumen', 'length' => 6, 'prefix' => 'DP']);
        DokumenPerkuliahan::create([
            'id_dokumen'            => $id,
            'nama_dokumen'          => 'Kontrak Kuliah',
            'tenggat_waktu_default' => 2,
            'dikumpulkan_per'       => 1,
            'template'              => null,
        ]);

        $id = IdGenerator::generate(['table' => 'dokumen_perkuliahan', 'field'=>'id_dokumen', 'length' => 6, 'prefix' => 'DP']);
        DokumenPerkuliahan::create([
            'id_dokumen'            => $id,
            'nama_dokumen'          => 'Soal Ujian Tengah Semester',
            'tenggat_waktu_default' => 7,
            'dikumpulkan_per'       => 0,
            'template'              => null,
        ]);

        $id = IdGenerator::generate(['table' => 'dokumen_perkuliahan', 'field'=>'id_dokumen', 'length' => 6, 'prefix' => 'DP']);
        DokumenPerkuliahan::create([
            'id_dokumen'            => $id,
            'nama_dokumen'          => 'Rubrik Penilaian Ujian Tengah Semester',
            'tenggat_waktu_default' => 7,
            'dikumpulkan_per'       => 0,
            'template'              => null,
        ]);

        $id = IdGenerator::generate(['table' => 'dokumen_perkuliahan', 'field'=>'id_dokumen', 'length' => 6, 'prefix' => 'DP']);
        DokumenPerkuliahan::create([
            'id_dokumen'            => $id,
            'nama_dokumen'          => 'Berita Acara Ujian Tengah Semester',
            'tenggat_waktu_default' => 8,
            'dikumpulkan_per'       => 0,
            'template'              => null,
        ]);

        $id = IdGenerator::generate(['table' => 'dokumen_perkuliahan', 'field'=>'id_dokumen', 'length' => 6, 'prefix' => 'DP']);
        DokumenPerkuliahan::create([
            'id_dokumen'            => $id,
            'nama_dokumen'          => 'Soal Ujian Akhir Semester',
            'tenggat_waktu_default' => 15,
            'dikumpulkan_per'       => 0,
            'template'              => null,
        ]);

        $id = IdGenerator::generate(['table' => 'dokumen_perkuliahan', 'field'=>'id_dokumen', 'length' => 6, 'prefix' => 'DP']);
        DokumenPerkuliahan::create([
            'id_dokumen'            => $id,
            'nama_dokumen'          => 'Rubrik Penilaian Ujian Akhir Semester',
            'tenggat_waktu_default' => 16,
            'dikumpulkan_per'       => 0,
            'template'              => null,
        ]);

        $id = IdGenerator::generate(['table' => 'dokumen_perkuliahan', 'field'=>'id_dokumen', 'length' => 6, 'prefix' => 'DP']);
        DokumenPerkuliahan::create([
            'id_dokumen'            => $id,
            'nama_dokumen'          => 'Berita Acara Ujian Akhir Semester',
            'tenggat_waktu_default' => 16,
            'dikumpulkan_per'       => 0,
            'template'              => null,
        ]);

        $id = IdGenerator::generate(['table' => 'dokumen_perkuliahan', 'field'=>'id_dokumen', 'length' => 6, 'prefix' => 'DP']);
        DokumenPerkuliahan::create([
            'id_dokumen'            => $id,
            'nama_dokumen'          => 'Berita Acara Perkuliahan',
            'tenggat_waktu_default' => 16,
            'dikumpulkan_per'       => 1,
            'template'              => null,
        ]);

        $id = IdGenerator::generate(['table' => 'dokumen_perkuliahan', 'field'=>'id_dokumen', 'length' => 6, 'prefix' => 'DP']);
        DokumenPerkuliahan::create([
            'id_dokumen'            => $id,
            'nama_dokumen'          => 'Presensi Perkuliahan',
            'tenggat_waktu_default' => 16,
            'dikumpulkan_per'       => 1,
            'template'              => null,
        ]);

        $id = IdGenerator::generate(['table' => 'dokumen_perkuliahan', 'field'=>'id_dokumen', 'length' => 6, 'prefix' => 'DP']);
        DokumenPerkuliahan::create([
            'id_dokumen'            => $id,
            'nama_dokumen'          => 'Rekap Data Nilai Akhir',
            'tenggat_waktu_default' => 18,
            'dikumpulkan_per'       => 1,
            'template'              => null,
        ]);

        $id = IdGenerator::generate(['table' => 'dokumen_perkuliahan', 'field'=>'id_dokumen', 'length' => 6, 'prefix' => 'DP']);
        DokumenPerkuliahan::create([
            'id_dokumen'            => $id,
            'nama_dokumen'          => 'Portofolio Perkuliahan',
            'tenggat_waktu_default' => 20,
            'dikumpulkan_per'       => 0,
            'template'              => null,
        ]);
    }
}