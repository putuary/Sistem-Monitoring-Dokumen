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
        Schema::create('leader_boards', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_dosen')->unsigned();
            $table->bigInteger('id_tahun_ajaran')->unsigned();
            $table->integer('tepat_waktu');
            $table->integer('terlambat');
            $table->integer('kosong');
            $table->float('skor');
            $table->timestamps();

            $table->foreign('id_dosen')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_tahun_ajaran')->references('id_tahun_ajaran')->on('tahun_ajaran')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leader_boards');
    }
};