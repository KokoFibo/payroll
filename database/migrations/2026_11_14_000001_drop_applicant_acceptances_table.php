<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Fitur undo penerimaan dibatalkan: tabel applicant_acceptances (berisi snapshot data pelamar terenkripsi)
 * tidak dipakai lagi. HANYA untuk database payroll yang sebelumnya sudah menjalankan migrasi lama.
 * Aplikasi yang tidak pernah punya tabel itu tidak perlu migrasi ini (otomatis dilewati).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('applicant_acceptances')) {
            return;
        }

        // Baris yang tersisa = penerimaan yang dulu masih menunggu pembersihan: file asli pelamar itu mungkin
        // masih ada di bucket portal. Jangan hapus tabel sebelum itu dibereskan.
        if (DB::table('applicant_acceptances')->exists()) {
            throw new \RuntimeException(
                'Tabel applicant_acceptances masih berisi data. File asli pelamar yang sudah diterima mungkin masih ada di bucket portal. '
                . 'Jalankan dulu `php artisan applicants:finalize-acceptances` (versi LAMA, setelah masa 60 menit lewat) sampai tabel kosong, lalu ulangi migrasi ini.'
            );
        }

        Schema::drop('applicant_acceptances');
    }

    public function down(): void
    {
        // Tidak dikembalikan: fitur undo sudah dibatalkan.
    }
};
