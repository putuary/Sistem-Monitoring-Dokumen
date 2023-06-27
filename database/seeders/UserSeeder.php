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
            'nama'      => 'Andika Setiawan S.Kom., M.Cs.',
            'email'     => 'andika.setiawan@if.itera.ac.id',
            'password'  => Hash::make('password'),
            'role'      => 'kaprodi',
        ]);
        $user->aktif_role()->create([
            'is_dosen'  => 0,
        ]);

        $user=User::create([
            'nama'      => 'Eko Dwi Nugroho, S.Kom., M.Cs.',
            'email'     => 'eko.nugroho@if.itera.ac.id',
            'password'  => Hash::make('password'),
            'role'      => 'gkmp',
        ]);
        $user->aktif_role()->create([
            'is_dosen'  => 0,
        ]);

        User::create([
            'nama'      => 'Ade Setiawan, S.Si',
            'email'     => 'ade.setiawan@staff.itera.ac.id',
            'password'  => Hash::make('password'),
            'role'      => 'admin',
        ]);

        User::create([
            'nama'      => 'Ir. Hira Laksmiwati Soemitro M.Sc.',
            'email'     => 'hira@informatika.org',
            'password'  => Hash::make('password'),
            'role'      => 'dosen',
        ]);

        User::create([
            'nama'      => 'Rajif Agung Yunmar S.Kom., M.Cs.',
            'email'     => 'rajif@if.itera.ac.id',
            'password'  => Hash::make('password'),
            'role'      => 'dosen',
        ]);

        User::create([
            'nama'      => 'Raidah Hanifah S.T., M.T.',
            'email'     => 'raidah.hanifah@if.itera.ac.id',
            'password'  => Hash::make('password'),
            'role'      => 'dosen',
        ]);

        User::create([
            'nama'      => 'Rahman Indra Kesuma S.Kom., M.Cs.',
            'email'     => 'rahman.indra@if.itera.ac.id',
            'password'  => Hash::make('password'),
            'role'      => 'dosen',
        ]);

        User::create([
            'nama'      => 'Arkham Zahri Rakhman S.Kom., M.Eng.',
            'email'     => 'arkham@if.itera.ac.id',
            'password'  => Hash::make('password'),
            'role'      => 'dosen',
        ]);

        User::create([
            'nama'      => 'Hafiz Budi Firmansyah S.Kom., M.Sc.',
            'email'     => 'hafiz.budi@if.itera.ac.id',
            'password'  => Hash::make('password'),
            'role'      => 'dosen',
        ]);

        User::create([
            'nama'      => 'Imam Ekowicaksono S.Si., M.Si.',
            'email'     => 'imam.wicaksono@if.itera.ac.id',
            'password'  => Hash::make('password'),
            'role'      => 'dosen',
        ]);

        User::create([
            'nama'      => 'I Wayan Wiprayoga Wisesa S.Kom., M.Kom.',
            'email'     => 'wayan.wisesa@if.itera.ac.id',
            'password'  => Hash::make('password'),
            'role'      => 'dosen',
        ]);

        User::create([
            'nama'      => 'Hartanto Tantriawan S.Kom., M.Kom',
            'email'     => 'hartanto.tantriawan@if.itera.ac.id',
            'password'  => Hash::make('password'),
            'role'      => 'dosen',
        ]);

        User::create([
            'nama'      => 'Angga Wijaya S.Si., M.Si.',
            'email'     => 'angga.wijaya@if.itera.ac.id',
            'password'  => Hash::make('password'),
            'role'      => 'dosen',
        ]);

        User::create([
            'nama'      => 'Amirul Iqbal S.Kom., M.Eng.',
            'email'     => 'amirul.iqbal@if.itera.ac.id',
            'password'  => Hash::make('password'),
            'role'      => 'dosen',
        ]);

        User::create([
            'nama'      => 'Mohamad Idris S.Si., M.Sc.',
            'email'     => 'mohamad.idris@if.itera.ac.id',
            'password'  => Hash::make('password'),
            'role'      => 'dosen',
        ]);

        User::create([
            'nama'      => 'Arief Ichwani S.Kom., M.Cs.',
            'email'     => 'arief.ichwani@if.itera.ac.id',
            'password'  => Hash::make('password'),
            'role'      => 'dosen',
        ]);

        User::create([
            'nama'      => 'Martin C.T. Manullang S.T., M.T.',
            'email'     => 'martin.manullang@if.itera.ac.id',
            'password'  => Hash::make('password'),
            'role'      => 'dosen',
        ]);

        User::create([
            'nama'      => 'Ir. Mugi Praseptiawan S.T., M.Kom',
            'email'     => 'mugi.praseptiawan@if.itera.ac.id',
            'password'  => Hash::make('password'),
            'role'      => 'dosen',
        ]);

        User::create([
            'nama'      => 'Meida Cahyo Untoro S.Kom., M.Kom',
            'email'     => 'cahyo.untoro@if.itera.ac.id',
            'password'  => Hash::make('password'),
            'role'      => 'dosen',
        ]);

        User::create([
            'nama'      => 'Aidil Afriansyah S.Kom., M.Kom',
            'email'     => 'aidil.afriansyah@if.itera.ac.id',
            'password'  => Hash::make('password'),
            'role'      => 'dosen',
        ]);

        User::create([
            'nama'      => 'Andre Febrianto, S.Kom., M.Eng.',
            'email'     => 'andre.febrianto@if.itera.ac.id',
            'password'  => Hash::make('password'),
            'role'      => 'dosen',
        ]);

        User::create([
            'nama'      => 'Ilham Firman Ashari, S.Kom., M.T',
            'email'     => 'firman.ashari@if.itera.ac.id',
            'password'  => Hash::make('password'),
            'role'      => 'dosen',
        ]);

        User::create([
            'nama'      => 'Muhammad Habib Algifari, S.Kom., M.T.I.',
            'email'     => 'muhammad.algifari@if.itera.ac.id',
            'password'  => Hash::make('password'),
            'role'      => 'dosen',
        ]);

        User::create([
            'nama'      => 'Radhinka Bagaskara, S.Si.Kom., M.Si., M.Sc.',
            'email'     => 'radhinka.bagaskara@if.itera.ac.id',
            'password'  => Hash::make('password'),
            'role'      => 'dosen',
        ]);

        User::create([
            'nama'      => 'Winda Yulita, M.Cs. ',
            'email'     => 'winda.yulita@if.itera.ac.id',
            'password'  => Hash::make('password'),
            'role'      => 'dosen',
        ]);

        User::create([
            'nama'      => 'Miranti Verdiana, M.Si.',
            'email'     => 'miranti.verdiana@if.itera.ac.id',
            'password'  => Hash::make('password'),
            'role'      => 'dosen',
        ]);
    }
}