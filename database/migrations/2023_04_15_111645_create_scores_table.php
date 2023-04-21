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
        Schema::create('scores', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_user')->unsigned();
            $table->string('id_dokumen_ditugaskan', 10);
            $table->bigInteger('kode_kelas')->unsigned();
            $table->bigInteger('id_tahun_ajaran')->unsigned();
            $table->float('poin');
            $table->tinyInteger('tepat_waktu');
            $table->tinyInteger('terlambat');
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users');
            $table->foreign('id_dokumen_ditugaskan')->references('id_dokumen_ditugaskan')->on('dokumen_ditugaskan');
            $table->foreign('kode_kelas')->references('kode_kelas')->on('kelas');
            $table->foreign('id_tahun_ajaran')->references('id_tahun_ajaran')->on('tahun_ajaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scores');
    }
};