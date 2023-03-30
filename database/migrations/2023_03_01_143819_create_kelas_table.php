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
        Schema::create('kelas', function (Blueprint $table) {
            $table->id('kode_kelas');
            $table->string('nama_kelas');
            $table->string('kode_matkul');
            $table->bigInteger('id_tahun_ajaran')->unsigned();
            $table->timestamps();

            $table->foreign('kode_matkul')->references('kode_matkul')->on('mata_kuliah');
            $table->foreign('id_tahun_ajaran')->references('id_tahun_ajaran')->on('tahun_ajaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};