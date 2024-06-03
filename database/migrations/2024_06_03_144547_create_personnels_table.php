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
        Schema::create('personnels', function (Blueprint $table) {
            $table->id();
            $table->string('departemen_pemohon');
            $table->string('posisi');
            $table->string('jumlah');
            $table->string('level_posisi');
            $table->string('manpower_posisi');
            $table->string('jumlah_manpower_saat_ini');
            $table->string('pemohon');
            $table->date('tanggal');
            $table->string('waktu_masuk_kerja');
            $table->string('alasan_permohonan');
            $table->string('job_desc');
            $table->string('usia');
            $table->string('pendidikan');
            $table->string('gender');
            $table->string('lama_pengalaman_kerja');
            $table->string('skill_wajib');
            $table->string('kualifikasi_lain');
            $table->string('kisaran_gaji');
            $table->string('approved_1_by');
            $table->date('approved_1_date');
            $table->string('approved_2_by');
            $table->date('approved_2_date');
            $table->string('handle_by');
            $table->integer('status');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personnels');
    }
};
