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
        Schema::create('dokumen_perkuliahan', function (Blueprint $table) {
            $table->string('id_dokumen')->primary();
            $table->string('nama_dokumen');
            $table->integer('tenggat_waktu_default');
            $table->string('dikumpulkan_per');
            $table->string('template')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen_perkuliahan');
    }
};