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
            $table->string('id_matkul_dibuka', 10);
            $table->bigInteger('id_tahun_ajaran')->unsigned();

            $table->foreign('id_matkul_dibuka')->references('id_matkul_dibuka')->on('matkul_dibuka')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_tahun_ajaran')->references('id_tahun_ajaran')->on('tahun_ajaran')->onUpdate('cascade')->onDelete('cascade');
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