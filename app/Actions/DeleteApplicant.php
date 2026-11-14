<?php

namespace App\Actions;

use App\Exceptions\ApplicantConfigException;
use App\Models\Portal\Applicant;
use App\Models\Portal\ApplicantFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DeleteApplicant
{
    /**
     * Hapus pelamar (di DATABASE APPLICANT), baris dokumennya, dan file fisik di S3.
     *
     * @return array{files:int, s3_ok:bool}
     */
    public function handle(Applicant $applicant): array
    {
        $applicantId = $applicant->applicant_id;

        // Jangan query file jika applicant_id kosong (IS NULL bisa kena data lain).
        $paths = filled($applicantId)
            ? ApplicantFile::where('id_karyawan', $applicantId)->pluck('filename')->filter()->all()
            : [];

        // Ada file yang harus dihapus tetapi disk belum diatur: tolak SEBELUM data apa pun dihapus. Tanpa ini data
        // pelamar hilang sementara dokumen (KTP, dll.) tertinggal di bucket dan terlihat seolah berhasil dihapus.
        if ($paths) {
            $disk = (string) config('applicant_admin.applicant_disk');

            if ($disk === '' || config("filesystems.disks.{$disk}") === null) {
                throw new ApplicantConfigException('APPLICANT_FILES_DISK belum diatur di .env atau tidak ada di config/filesystems.php.');
            }
        }

        // 1) Database dulu, satu transaksi di koneksi applicant: gagal = tidak ada yang terhapus.
        $applicant->getConnection()->transaction(function () use ($applicant, $applicantId) {
            if (filled($applicantId)) {
                ApplicantFile::where('id_karyawan', $applicantId)->delete();
            }
            $applicant->delete();
        });

        // 2) S3 setelah database berhasil, satu panggilan untuk semua file.
        //    Disk dengan 'throw' => false (bawaan Laravel) TIDAK melempar exception saat gagal, hanya
        //    mengembalikan false, jadi nilai kembaliannya harus diperiksa. Menghapus key yang sudah tidak ada
        //    di S3 bukan kegagalan.
        $s3Ok = true;
        if ($paths) {
            try {
                $s3Ok = (bool) Storage::disk(config('applicant_admin.applicant_disk'))->delete($paths);
            } catch (\Throwable $e) {
                $s3Ok = false;
                report($e);
            }

            if (! $s3Ok) {
                Log::warning('Gagal menghapus file S3 pelamar', [
                    'applicant_id' => $applicantId,
                    'paths'        => $paths,   // simpan agar bisa dibersihkan manual
                ]);
            }
        }

        return ['files' => count($paths), 's3_ok' => $s3Ok];
    }
}
