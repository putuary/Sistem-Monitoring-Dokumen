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
        Schema::create('dokumen_kelas', function (Blueprint $table) {
            $table->string('id_dokumen_kelas', 10)->primary();
            $table->string('id_dokumen_ditugaskan', 10);
            $table->bigInteger('kode_kelas')->unsigned();
            $table->string('file_dokumen')->nullable();
            $table->dateTime('waktu_pengumpulan')->nullable();
            $table->timestamps();

            $table->unique(['id_dokumen_ditugaskan', 'kode_kelas']);
            $table->foreign('id_dokumen_ditugaskan')->references('id_dokumen_ditugaskan')->on('dokumen_ditugaskan')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('kode_kelas')->references('kode_kelas')->on('kelas')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen_dikumpuls');
    }
};