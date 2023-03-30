<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user=User::create([
            'nama'      => 'Kaprodi',
            'email'     => 'kaprodi@if.itera.ac.id',
            'password'  => Hash::make('password'),
            'avatar'    => 'default.png',
            'role'      => 'kaprodi',
        ]);
        $user->aktif_role()->create([
            'is_dosen'  => 0,
        ]);

        $user=User::create([
            'nama'      => 'GKMP',
            'email'     => 'gkmp@if.itera.ac.id',
            'password'  => Hash::make('password'),
            'avatar'    => 'default.png',
            'role'      => 'gkmp',
        ]);
        $user->aktif_role()->create([
            'is_dosen'  => 0,
        ]);

        User::create([
            'nama'      => 'Admin',
            'email'     => 'admin@if.itera.ac.id',
            'password'  => Hash::make('password'),
            'avatar'    => 'default.png',
            'role'      => 'admin',
        ]);

        User::create([
            'nama'      => 'Dosen',
            'email'     => 'dosen@if.itera.ac.id',
            'password'  => Hash::make('password'),
            'avatar'    => 'default.png',
            'role'      => 'dosen',
        ]);
    }
}