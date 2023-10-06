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
        Schema::create('karyawans', function (Blueprint $table) {
            $table->id();
            $table->integer('id_karyawan');
            $table->string('nama');
            $table->string('email');
            $table->string('hp');
            $table->string('telepon');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('gender');
            $table->string('status_pernikahan');
            $table->string('golongan_darah');
            $table->string('agama');

            $table->string('jenis_identitas');
            $table->string('no_identitas');
            $table->string('alamat_identitas');
            $table->string('alamat_tinggal');

            $table->string('status_karyawan');
            $table->date('tanggal_bergabung');
            $table->string('branch');
            $table->string('departemen');
            $table->string('jabatan');
            $table->string('level_jabatan');

            $table->integer('gaji_pokok');
            $table->integer('gaji_perjam');
            $table->integer('gaji_overtime');
            $table->integer('gaji_harian');
            $table->integer('gaji_bulanan');
            $table->string('metode_penggajian');
            $table->integer('uang_makan');
            $table->integer('bonus');
            $table->integer('tunjangan_jabatan');
            $table->integer('tunjangan_bahasa');
            $table->integer('tunjangan_skill');
            $table->integer('tunjangan_lembur_sabtu');
            $table->integer('tunjangan_lama_kerja');

            $table->integer('hutang');
            $table->integer('potongan_hutang');
            $table->integer('iuran_air');
            $table->integer('potongan_seragam');
            $table->integer('denda');
            $table->integer('potongan_pph21');
            $table->integer('potongan_bpjs');
            $table->integer('potongan_ijin_alpa');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawans');
    }
};
