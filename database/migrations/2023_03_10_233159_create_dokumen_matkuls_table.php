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
        Schema::create('dokumen_matkul', function (Blueprint $table) {
            $table->id('id_dokumen_matkul');
            $table->string('id_dokumen_ditugaskan');
            $table->string('kode_matkul');
            $table->string('file_dokumen')->nullable();
            $table->dateTime('waktu_pengumpulan')->nullable();
            $table->timestamps();

            $table->unique(['id_dokumen_ditugaskan', 'kode_matkul']);
            $table->foreign('id_dokumen_ditugaskan')->references('id_dokumen_ditugaskan')->on('dokumen_ditugaskan');
            $table->foreign('kode_matkul')->references('kode_matkul')->on('mata_kuliah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen_matkul');
    }
};