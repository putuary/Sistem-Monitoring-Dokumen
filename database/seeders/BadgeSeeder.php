<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Badge;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Badge::create([
            'nama_badge'=> 'First Rank',
            'gambar'    => 'Rank_1.png',
            'deskripsi' => 'Dosen yang mendapatkan peringkat pertama pada leaderboard di akhir tahun ajaran',
        ]);

        Badge::create([
            'nama_badge'=> 'Second Rank',
            'gambar'    => 'Rank_2.png',
            'deskripsi' => 'Dosen yang mendapatkan peringkat kedua pada leaderboard di akhir tahun ajaran',
        ]);

        Badge::create([
            'nama_badge'=> 'Third Rank',
            'gambar'    => 'Rank_3.png',
            'deskripsi' => 'Dosen yang mendapatkan peringkat ketiga pada leaderboard di akhir tahun ajaran',
        ]);

        Badge::create([
            'nama_badge'=> 'Bottom One',
            'gambar'    => 'Bottom_1.png',
            'deskripsi' => 'Dosen yang mendapatkan peringkat terendah pertama pada leaderboard di akhir tahun ajaran',
        ]);

        Badge::create([
            'nama_badge'=> 'Bottom Two',
            'gambar'    => 'Bottom_2.png',
            'deskripsi' => 'Dosen yang mendapatkan peringkat terendah kedua pada leaderboard di akhir tahun ajaran',
        ]);

        Badge::create([
            'nama_badge'=> 'Bottom Three',
            'gambar'    => 'Bottom_3.png',
            'deskripsi' => 'Dosen yang mendapatkan peringkat terendah ketiga pada leaderboard di akhir tahun ajaran',
        ]);

        Badge::create([
            'nama_badge'=> 'On Time',
            'gambar'    => 'On_Time.png',
            'deskripsi' => 'Dosen yang mengumpulkan semua dokumen yang ditugaskan dengan tepat waktu',
        ]);

        Badge::create([
            'nama_badge'=> 'Too Late',
            'gambar'    => 'Too_Late.png',
            'deskripsi' => 'Dosen yang mengumpulkan setengah dari dokumen yang ditugaskan dengan terlambat',
        ]);
        
        Badge::create([
            'nama_badge'=> 'Too Bad',
            'gambar'    => 'Too_Bad.png',
            'deskripsi' => 'Dosen yang tidak mengumpulkan satu dokumen atau lebih dari dokumen yang ditugaskan hingga akhir tahun ajaran',
        ]);
    }
}