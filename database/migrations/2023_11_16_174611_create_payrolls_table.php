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
        Schema::create('payrolls', function (Blueprint $table) {

            $table->id();

            $table->foreignId('jamkerjaid_id');
            $table->index('jamkerjaid_id');
            $table->integer('id_karyawan');
            $table->string('nama');
            $table->string('jabatan');
            $table->string('company');
            $table->string('placement');
            $table->string('nama_bank')->nullable();
            $table->string('nomor_rekening')->nullable();
            $table->string('metode_penggajian');
            $table->double('hari_kerja',5,1);
            $table->double('jam_kerja',6,1);
            $table->double('jam_lembur',6,1)->nullable();
            $table->integer('gaji_pokok');
            $table->integer('gaji_lembur')->nullable();
            $table->integer('gaji_bpjs')->nullable();
            $table->double('subtotal',12,1);
            $table->double('bonus1x',12,1)->nullable();
            $table->double('potongan1x',12,1)->nullable();
            $table->double('denda_lupa_absen',12,1)->nullable();
            $table->double('pajak',8,1)->nullable();
            $table->double('jht',8,1)->nullable();
            $table->double('jp',8,1)->nullable();
            $table->double('jkk',8,1)->nullable();
            $table->double('jkm',8,1)->nullable();
            $table->double('kesehatan',8,1)->nullable();
            $table->double('total',12,1);
            $table->date('date');
            $table->string('status_karyawan');
            // $table->integer('uang_makan')->nullable();
            // $table->integer('denda_lupa_absen')->nullable();
            // $table->integer('bonus')->nullable();
            // $table->integer('thr')->nullable();
            // $table->integer('denda')->nullable();
            $table->integer('tambahan_shift_malam')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
