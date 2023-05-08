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
        Schema::create('matkul_dibuka', function (Blueprint $table) {
            $table->string('id_matkul_dibuka', 10)->primary();
            $table->string('kode_matkul', 10);
            $table->bigInteger('id_tahun_ajaran')->unsigned();
            $table->string('nama_matkul');
            $table->integer('bobot_sks');
            $table->boolean('praktikum');
            $table->timestamps();

            $table->unique(['kode_matkul', 'id_tahun_ajaran']);
            $table->foreign('id_tahun_ajaran')->references('id_tahun_ajaran')->on('tahun_ajaran')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matkul_dibuka');
    }
};