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
            $table->string('nama_matkul');
            $table->integer('bobot_sks');
            $table->boolean('praktikum');
            $table->timestamps();
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