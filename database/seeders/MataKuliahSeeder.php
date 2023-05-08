<?php

namespace Database\Seeders;

use App\Models\MataKuliah;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class MataKuliahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MataKuliah::create([
            'kode_matkul' => 'IF1101',
            'nama_matkul' => 'Pengenalan Program Studi Teknik Informatika',
            'bobot_sks' => 2,
            'praktikum' => 0,
        ]);
        
        MataKuliah::create([
            'kode_matkul' => 'IF1121',
            'nama_matkul' => 'Algoritma Pemrograman 1',
            'bobot_sks' => 3,
            'praktikum' => 1,
        ]);

        MataKuliah::create([
            'kode_matkul' => 'IF1222',
            'nama_matkul' => 'Algoritma Pemrograman 2',
            'bobot_sks' => 3,
            'praktikum' => 1,
        ]);

        MataKuliah::create([
            'kode_matkul' => 'IF2116',
            'nama_matkul' => 'Matematika Diskrit',
            'bobot_sks' => 4,
            'praktikum' => 0,
        ]);

        MataKuliah::create([
            'kode_matkul' => 'IF2114',
            'nama_matkul' => 'Matriks dan Ruang vektor',
            'bobot_sks' => 3,
            'praktikum' => 0,
        ]);

        MataKuliah::create([
            'kode_matkul' => 'IF2121',
            'nama_matkul' => 'Algoritma dan Struktur Data',
            'bobot_sks' => 4,
            'praktikum' => 1,
        ]);

        MataKuliah::create([
            'kode_matkul' => 'IF2122',
            'nama_matkul' => 'Organisasi dan Arsitektur Komputer',
            'bobot_sks' => 3,
            'praktikum' => 0,
        ]);

        MataKuliah::create([
            'kode_matkul' => 'IF2117',
            'nama_matkul' => 'Teori Bahasa Formal dan Otomata',
            'bobot_sks' => 3,
            'praktikum' => 0,
        ]);

        MataKuliah::create([
            'kode_matkul' => 'IF2222',
            'nama_matkul' => 'Pemrograman Berorientasi Objek',
            'bobot_sks' => 4,
            'praktikum' => 1,
        ]);

        MataKuliah::create([
            'kode_matkul' => 'IF2211',
            'nama_matkul' => 'Strategi Algoritma',
            'bobot_sks' => 3,
            'praktikum' => 0,
        ]);

        MataKuliah::create([
            'kode_matkul' => 'IF2223',
            'nama_matkul' => 'Sistem Operasi',
            'bobot_sks' => 3,
            'praktikum' => 1,
        ]);

        MataKuliah::create([
            'kode_matkul' => 'IF2215',
            'nama_matkul' => 'Basis Data',
            'bobot_sks' => 3,
            'praktikum' => 0,
        ]);

        MataKuliah::create([
            'kode_matkul' => 'IF2232',
            'nama_matkul' => 'Dasar Rekayasa Perangkat Lunak',
            'bobot_sks' => 2,
            'praktikum' => 0,
        ]);

        MataKuliah::create([
            'kode_matkul' => 'IF2214',
            'nama_matkul' => 'Probabilitas dan Statistika',
            'bobot_sks' => 3,
            'praktikum' => 0,
        ]);
    }
}