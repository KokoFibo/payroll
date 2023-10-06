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
        Schema::create('temp_rekap_presensi', function (Blueprint $table) {
            $table->integer('user_id');
            $table->string('name');
            $table->string('department');
            $table->date('date');
            $table->integer('jml_fp');
            $table->time('first_in')->nullable();
            $table->time('first_out')->nullable();
            $table->time('second_in')->nullable();
            $table->time('second_out')->nullable();
            $table->time('overtime_in')->nullable();
            $table->time('overtime_out')->nullable();
            $table->integer('late')->nullable();
            $table->string('no_scan')->nullable();
            $table->string('shift')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temp_rekap_presensi');
    }
};
