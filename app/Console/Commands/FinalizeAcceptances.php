<?php

namespace App\Console\Commands;

use App\Models\ApplicantAcceptance;
use App\Models\Portal\ApplicantFile;
use App\Services\ApplicantFileMover;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FinalizeAcceptances extends Command
{
    protected $signature   = 'applicants:finalize-acceptances';
    protected $description = 'Setelah masa undo habis: hapus file di bucket pelamar, baris dokumen di database applicant, dan snapshot.';

    public function handle(ApplicantFileMover $files): int
    {
        $ids = ApplicantAcceptance::where('expires_at', '<', now())->pluck('id');

        foreach ($ids as $id) {
            try {
                DB::transaction(function () use ($id, $files) {
                    // Kunci baris (database payroll): jika undo sedang berjalan, tunggu sampai selesai.
                    $acc = ApplicantAcceptance::whereKey($id)->lockForUpdate()->first();

                    if (! $acc || $acc->expires_at->isFuture()) {
                        return;   // sudah di-undo, atau belum waktunya
                    }

                    $keys = $acc->files ?? [];

                    if ($keys && ! $files->deleteFromSource($keys)) {
                        // Data pribadi di snapshot tetap harus hilang; file dicoba lagi di putaran berikutnya.
                        $acc->update(['snapshot' => null]);
                        $this->warn("Gagal menghapus file lama untuk acceptance #{$id}, akan dicoba lagi.");
                        return;
                    }

                    // File asli sudah terhapus: bersihkan baris dokumennya di database applicant.
                    if (filled($acc->applicant_id)) {
                        ApplicantFile::where('id_karyawan', $acc->applicant_id)->delete();
                    }

                    $acc->delete();
                });
            } catch (\Throwable $e) {
                report($e);
                $this->warn("Acceptance #{$id} gagal diselesaikan, akan dicoba lagi.");
            }
        }

        return self::SUCCESS;
    }
}
