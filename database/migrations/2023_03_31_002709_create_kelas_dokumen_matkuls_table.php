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
        Schema::create('kelas_dokumen_matkul', function (Blueprint $table) {
            $table->bigInteger('kode_kelas')->unsigned();
            $table->string('id_dokumen_matkul', 10);

            $table->unique(['kode_kelas', 'id_dokumen_matkul']);
            $table->foreign('kode_kelas')->references('kode_kelas')->on('kelas')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_dokumen_matkul')->references('id_dokumen_matkul')->on('dokumen_matkul')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas_dokumen_matkul');
    }
};