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
        Schema::create('dokumen_ditugaskan', function (Blueprint $table) {
            $table->string('id_dokumen_ditugaskan', 10)->primary();
            $table->string('id_dokumen', 10);
            $table->bigInteger('id_tahun_ajaran')->unsigned();
            $table->dateTime('tenggat_waktu');
            $table->boolean('pengumpulan');
            $table->tinyInteger('dikumpulkan_per');
            $table->tinyInteger('dikumpul');
            $table->timestamps();

            $table->unique(['id_dokumen', 'id_tahun_ajaran']);
            $table->foreign('id_dokumen')->references('id_dokumen')->on('dokumen_perkuliahan');
            $table->foreign('id_tahun_ajaran')->references('id_tahun_ajaran')->on('tahun_ajaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen_ditugaskans');
    }
};