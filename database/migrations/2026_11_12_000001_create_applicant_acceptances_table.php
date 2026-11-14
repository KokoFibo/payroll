<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('applicant_acceptances', function (Blueprint $t) {
            $t->id();
            $t->string('applicant_id', 64)->index();
            $t->string('id_karyawan', 64)->index();
            $t->string('nama');                             // hanya untuk tampilan
            $t->unsignedBigInteger('user_id')->nullable();
            $t->longText('snapshot')->nullable();           // JSON terenkripsi; null setelah final
            $t->json('files')->nullable();                  // key S3 yang disalin
            $t->string('files_state', 20)->default('none'); // none | copied
            $t->unsignedBigInteger('accepted_by')->nullable();
            $t->timestamp('expires_at')->index();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applicant_acceptances');
    }
};
