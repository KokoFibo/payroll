<?php

namespace App\Actions;

use App\Models\ApplicantAcceptance;
use App\Models\Karyawan;
use App\Models\Portal\Applicant;
use App\Models\User;
use App\Services\ApplicantFileMover;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class UndoAcceptance
{
    public function __construct(private ApplicantFileMover $files)
    {
    }

    /** @throws \DomainException bila undo tidak boleh dilakukan */
    public function handle(ApplicantAcceptance $acc): void
    {
        $model      = new Applicant;
        $applicants = $model->getConnection()->table($model->getTable());   // DATABASE APPLICANT
        $copiedKeys = [];
        $restoredId = null;

        try {
            DB::transaction(function () use ($acc, $applicants, &$copiedKeys, &$restoredId) {
                // Kunci baris (database payroll) agar tidak berbenturan dengan command finalisasi.
                $acc = ApplicantAcceptance::whereKey($acc->id)->lockForUpdate()->first();

                if (! $acc || ! $acc->isUndoable() || $acc->snapshot === null) {
                    throw new \DomainException('Batas waktu pembatalan sudah lewat atau data sudah final.');
                }

                if ((clone $applicants)->where('applicant_id', $acc->applicant_id)->exists()) {
                    throw new \DomainException('Data pelamar ini sudah ada kembali di daftar.');
                }

                $karyawan = Karyawan::where('id_karyawan', $acc->id_karyawan)->first();

                // Jika data karyawan sudah diedit setelah diterima, jangan dihapus begitu saja.
                if ($karyawan && $karyawan->updated_at?->gt($acc->created_at->copy()->addMinute())) {
                    throw new \DomainException('Data karyawan sudah diubah setelah diterima. Hapus manual lewat menu karyawan.');
                }

                $attrs = json_decode(Crypt::decryptString($acc->snapshot), true);

                // Payroll dulu: semua yang bisa gagal ada di sini dan ikut rollback.
                if ($acc->user_id) {
                    User::whereKey($acc->user_id)->delete();
                }
                // Jika model Karyawan memakai SoftDeletes, ganti dengan forceDelete().
                $karyawan?->delete();

                // Baris dokumen yang disalin ke tabel applicantfiles payroll saat diterima.
                DB::table('applicantfiles')->where('id_karyawan', $acc->applicant_id)->delete();

                $copiedKeys = $acc->files ?? [];
                $acc->delete();

                // Langkah TERAKHIR: pulihkan pelamar di database applicant (insert mentah: nilai kembali persis).
                // Gagal (mis. email sudah dipakai pendaftaran baru) = exception = payroll di-rollback.
                $applicants->insert($attrs);
                $restoredId = $attrs['id'] ?? null;
            });
        } catch (\Throwable $e) {
            // Commit payroll gagal setelah pelamar dipulihkan: tarik kembali agar tidak ada dua versi.
            if ($restoredId !== null) {
                try {
                    (clone $applicants)->where('id', $restoredId)->delete();
                } catch (\Throwable $e2) {
                    report($e2);
                }
            }
            throw $e;
        }

        // Setelah semuanya beres: buang salinan di bucket karyawan. File asli tidak pernah dihapus.
        if ($copiedKeys) {
            $this->files->deleteFromTarget($copiedKeys);
        }
    }
}
