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
            'deskripsi' => 'Dosen dengan memiliki poin tertinggi pertama',
        ]);

        Badge::create([
            'nama_badge'=> 'Second Rank',
            'gambar'    => 'Rank_2.png',
            'deskripsi' => 'Dosen dengan memiliki poin tertinggi kedua',
        ]);

        Badge::create([
            'nama_badge'=> 'Third Rank',
            'gambar'    => 'Rank_3.png',
            'deskripsi' => 'Dosen dengan memiliki poin tertinggi ketiga',
        ]);

        Badge::create([
            'nama_badge'=> 'Bottom One',
            'gambar'    => 'Bottom_1.png',
            'deskripsi' => 'Dosen dengan memiliki poin terendah pertama',
        ]);

        Badge::create([
            'nama_badge'=> 'Bottom Two',
            'gambar'    => 'Bottom_2.png',
            'deskripsi' => 'Dosen dengan memiliki poin terendah kedua',
        ]);

        Badge::create([
            'nama_badge'=> 'Bottom Three',
            'gambar'    => 'Bottom_3.png',
            'deskripsi' => 'Dosen dengan memiliki poin terendah ketiga',
        ]);

        Badge::create([
            'nama_badge'=> 'On Time',
            'gambar'    => 'On_Time.png',
            'deskripsi' => 'Dosen dengan semua dokumen yang ditugaskan dikumpulkan tepat waktu',
        ]);

        Badge::create([
            'nama_badge'=> 'Too Late',
            'gambar'    => 'Too_Late.png',
            'deskripsi' => 'Dosen dengan setengah atau lebih dokumen yang ditugaskan dikumpulkan terlambat',
        ]);
        
        Badge::create([
            'nama_badge'=> 'Too Bad',
            'gambar'    => 'Too_Bad.png',
            'deskripsi' => 'Dosen dengan tidak mengumpulkan satu dokumen atau lebih dari dokumen yang ditugaskan',
        ]);
    }
}