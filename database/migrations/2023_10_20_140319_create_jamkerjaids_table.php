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
        Schema::create('jamkerjaids', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->index('user_id');
            $table->foreignUuid('karyawan_id');
            $table->index('karyawan_id');
            $table->date('date')->nullable();
            $table->double('jumlah_jam_kerja',4,1)->nullable();
            $table->integer('jumlah_menit_lembur')->nullable();
            $table->integer('jumlah_jam_terlambat')->nullable();
            $table->integer('first_in_late')->nullable();
            $table->integer('first_out_late')->nullable();
            $table->integer('second_in_late')->nullable();
            $table->integer('second_out_late')->nullable();
            // $table->integer('overtime_in_late')->nullable();
            $table->integer('total_late')->nullable();
            $table->integer('total_noscan')->nullable();
            $table->integer('total_hari_kerja')->nullable();
            $table->date('last_data_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jamkerjaids');
    }
};
