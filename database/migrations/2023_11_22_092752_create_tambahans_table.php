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
        Schema::create('tambahans', function (Blueprint $table) {
            $table->id();
            $table->integer('uang_makan');
            $table->integer('bonus');

            $table->integer('baju_esd');
            $table->integer('gelas');
            $table->integer('sandal');
            $table->integer('seragam');
            $table->integer('sport_bra');
            $table->integer('hijab_instan');
            $table->integer('id_card_hilang');
            $table->integer('masker_hijau');

            $table->integer('denda');
            $table->integer('lain');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tambahans');
    }
};
