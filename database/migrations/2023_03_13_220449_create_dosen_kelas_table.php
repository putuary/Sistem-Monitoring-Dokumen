<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dosen_kelas', function (Blueprint $table) {
            $table->bigInteger('id_dosen')->unsigned();
            $table->bigInteger('kode_kelas')->unsigned();

            $table->unique(['id_dosen', 'kode_kelas']);
            $table->foreign('id_dosen')->references('id')->on('users');
            $table->foreign('kode_kelas')->references('kode_kelas')->on('kelas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen_kelas');
    }
};