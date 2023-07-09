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
            $table->bigInteger('id_dosen')->unsigned();
            $table->bigInteger('kode_kelas')->unsigned();
            $table->bigInteger('id_tahun_ajaran')->unsigned();
            $table->string('scoreable_id', 10);
            $table->string('scoreable_type');
            $table->integer('poin')->nullable()->default(null);
            $table->integer('bonus')->nullable()->default(null);
            $table->timestamps();
            
            $table->foreign('id_dosen')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('kode_kelas')->references('kode_kelas')->on('kelas')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_tahun_ajaran')->references('id_tahun_ajaran')->on('tahun_ajaran')->onUpdate('cascade')->onDelete('cascade');
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