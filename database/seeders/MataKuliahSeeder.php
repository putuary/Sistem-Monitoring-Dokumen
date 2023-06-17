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
            'praktikum' => 1,
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

        MataKuliah::create([
            'kode_matkul' => 'IF3101',
            'nama_matkul' => 'Metodologi Penelitian ',
            'bobot_sks' => 2,
            'praktikum' => 0,
        ]);

        MataKuliah::create([
            'kode_matkul' => 'IF3125',
            'nama_matkul' => 'Jaringan Komputer',
            'bobot_sks' => 3,
            'praktikum' => 1,
        ]);

        MataKuliah::create([
            'kode_matkul' => 'IF3132',
            'nama_matkul' => 'Interaksi Manusia dan Komputer',
            'bobot_sks' => 2,
            'praktikum' => 0,
        ]);

        MataKuliah::create([
            'kode_matkul' => 'IF3111',
            'nama_matkul' => 'Inteligensi Buatan',
            'bobot_sks' => 3,
            'praktikum' => 0,
        ]);

        MataKuliah::create([
            'kode_matkul' => 'IF3133',
            'nama_matkul' => 'Sistem Informasi',
            'bobot_sks' => 2,
            'praktikum' => 0,
        ]);

        MataKuliah::create([
            'kode_matkul' => 'IF3002',
            'nama_matkul' => 'Kewirausahaan',
            'bobot_sks' => 2,
            'praktikum' => 0,
        ]);

        MataKuliah::create([
            'kode_matkul' => 'IF3029',
            'nama_matkul' => 'Sistem Tertanam',
            'bobot_sks' => 3,
            'praktikum' => 1,
        ]);

        MataKuliah::create([
            'kode_matkul' => 'IF3018',
            'nama_matkul' => 'Manajemen Basis Data',
            'bobot_sks' => 3,
            'praktikum' => 0,
        ]);

        MataKuliah::create([
            'kode_matkul' => 'IF3028',
            'nama_matkul' => 'Pemrograman Web',
            'bobot_sks' => 3,
            'praktikum' => 1,
        ]);

        MataKuliah::create([
            'kode_matkul' => 'IF3033',
            'nama_matkul' => 'Manajemen Proyek Teknologi Informasi',
            'bobot_sks' => 3,
            'praktikum' => 0,
        ]);

        MataKuliah::create([
            'kode_matkul' => 'IF4034',
            'nama_matkul' => 'Proyek Teknologi Informasi',
            'bobot_sks' => 4,
            'praktikum' => 0,
        ]);

        MataKuliah::create([
            'kode_matkul' => 'IF4201',
            'nama_matkul' => 'Socio Informatika dan Etika Profesi',
            'bobot_sks' => 2,
            'praktikum' => 0,
        ]);

        MataKuliah::create([
            'kode_matkul' => 'IF4202',
            'nama_matkul' => 'Kapita Selekta Informatika',
            'bobot_sks' => 3,
            'praktikum' => 0,
        ]);

        MataKuliah::create([
            'kode_matkul' => 'IF3025',
            'nama_matkul' => 'Jaringan Komputer Lanjut',
            'bobot_sks' => 3,
            'praktikum' => 0,
        ]);

        MataKuliah::create([
            'kode_matkul' => 'IF3023',
            'nama_matkul' => 'Teknologi game',
            'bobot_sks' => 3,
            'praktikum' => 0,
        ]);

        MataKuliah::create([
            'kode_matkul' => 'IF3024',
            'nama_matkul' => 'Pengolahan Sinyal Digital',
            'bobot_sks' => 3,
            'praktikum' => 1,
        ]);

        MataKuliah::create([
            'kode_matkul' => 'IF3027',
            'nama_matkul' => 'Kriptografi',
            'bobot_sks' => 3,
            'praktikum' => 0,
        ]);

        MataKuliah::create([
            'kode_matkul' => 'IF3026',
            'nama_matkul' => 'Pengembangan Aplikasi Mobile',
            'bobot_sks' => 3,
            'praktikum' => 1,
        ]);

        MataKuliah::create([
            'kode_matkul' => 'IF3017',
            'nama_matkul' => 'Data Warehouse / Data Mining',
            'bobot_sks' => 3,
            'praktikum' => 0,
        ]);

        MataKuliah::create([
            'kode_matkul' => 'IF3011',
            'nama_matkul' => 'Pengolahan Bahasa Alami',
            'bobot_sks' => 3,
            'praktikum' => 0,
        ]);

        MataKuliah::create([
            'kode_matkul' => 'IF3012',
            'nama_matkul' => 'Pembelajaran Mesin',
            'bobot_sks' => 3,
            'praktikum' => 0,
        ]);

        MataKuliah::create([
            'kode_matkul' => 'IF3015',
            'nama_matkul' => 'Representasi Pengetahuan dan Penalaran',
            'bobot_sks' => 3,
            'praktikum' => 0,
        ]);

        MataKuliah::create([
            'kode_matkul' => 'IF3016',
            'nama_matkul' => 'Information Retrieval (Perolehan Informasi)',
            'bobot_sks' => 3,
            'praktikum' => 0,
        ]);

        MataKuliah::create([
            'kode_matkul' => 'IF4021',
            'nama_matkul' => 'Sistem/Teknologi Multimedia',
            'bobot_sks' => 3,
            'praktikum' => 0,
        ]);

        MataKuliah::create([
            'kode_matkul' => 'IF4025',
            'nama_matkul' => 'Pervasive Computing',
            'bobot_sks' => 3,
            'praktikum' => 0,
        ]);

        MataKuliah::create([
            'kode_matkul' => 'IF4022',
            'nama_matkul' => 'Pemrograma Paralel',
            'bobot_sks' => 3,
            'praktikum' => 0,
        ]);

        MataKuliah::create([
            'kode_matkul' => 'IF4026',
            'nama_matkul' => 'Keamanan Jaringan',
            'bobot_sks' => 3,
            'praktikum' => 0,
        ]);

        MataKuliah::create([
            'kode_matkul' => 'IF4024',
            'nama_matkul' => 'Pemrograman Web Lanjut',
            'bobot_sks' => 3,
            'praktikum' => 1,
        ]);

        MataKuliah::create([
            'kode_matkul' => 'IF4012',
            'nama_matkul' => 'Pengolahan Citra Digital',
            'bobot_sks' => 3,
            'praktikum' => 0,
        ]);

        MataKuliah::create([
            'kode_matkul' => 'IF4013',
            'nama_matkul' => 'Teknologi Basis Data',
            'bobot_sks' => 3,
            'praktikum' => 0,
        ]);

        MataKuliah::create([
            'kode_matkul' => 'IF4014',
            'nama_matkul' => 'Visualisasi Data dan Informasi',
            'bobot_sks' => 3,
            'praktikum' => 0,
        ]);

        MataKuliah::create([
            'kode_matkul' => 'IF4036',
            'nama_matkul' => 'Sistem Informasi Geografis',
            'bobot_sks' => 3,
            'praktikum' => 0,
        ]);

        MataKuliah::create([
            'kode_matkul' => 'IF4035',
            'nama_matkul' => 'Sistem Informasi Lanjut',
            'bobot_sks' => 3,
            'praktikum' => 0,
        ]);
    }
}