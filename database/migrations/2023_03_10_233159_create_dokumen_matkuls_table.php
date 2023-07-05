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
            $table->string('id_dokumen_matkul', 10)->primary();
            $table->string('id_dokumen_ditugaskan', 10);
            $table->string('id_matkul_dibuka', 10);
            $table->string('file_dokumen')->nullable();
            $table->dateTime('waktu_pengumpulan')->nullable();

            $table->unique(['id_dokumen_ditugaskan', 'id_matkul_dibuka']);
            $table->foreign('id_dokumen_ditugaskan')->references('id_dokumen_ditugaskan')->on('dokumen_ditugaskan')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_matkul_dibuka')->references('id_matkul_dibuka')->on('matkul_dibuka')->onUpdate('cascade')->onDelete('cascade');
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