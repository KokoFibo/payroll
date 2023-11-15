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
            $table->foreignUuid('karyawan_id');
            $table->foreignId('jamkerjaid_id');
            $table->double('subtotal',12,1);
            $table->double('pajak',8,1);
            $table->double('jht',8,1);
            $table->double('jp',8,1);
            $table->double('jkk',8,1);
            $table->double('jkm',8,1);
            $table->double('kesehatan',8,1);
            $table->double('total',12,1);
            $table->date('date');
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
