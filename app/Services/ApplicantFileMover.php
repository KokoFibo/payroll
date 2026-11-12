<?php

namespace App\Services;

use App\Models\Portal\ApplicantFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ApplicantFileMover
{
    private function fromDisk(): string   // bucket pelamar (bucket portal)
    {
        return (string) config('applicant_admin.applicant_disk');
    }

    private function toDisk(): string     // bucket karyawan
    {
        return (string) config('applicant_admin.karyawan_disk');
    }

    /** Baris dokumen pelamar dari DATABASE APPLICANT. */
    public function rowsFor(?string $applicantId): Collection
    {
        // where(..., null) menjadi IS NULL dan bisa mengambil file milik data lain.
        if (blank($applicantId)) {
            return collect();
        }

        return ApplicantFile::where('id_karyawan', $applicantId)->orderBy('id')->get();
    }

    /** Key file milik satu pelamar. */
    public function keysFor(?string $applicantId): array
    {
        return $this->rowsFor($applicantId)->pluck('filename')->filter()->unique()->values()->all();
    }

    /** Lokasi file di bucket karyawan. Ubah di sini jika strukturnya berbeda. */
    public function targetKey(string $key): string
    {
        return $key;
    }

    /**
     * Salin semua file ke bucket karyawan. Jika satu gagal, salinan yang sudah
     * dibuat dihapus lagi lalu exception dilempar. Mengembalikan key yang tersalin.
     */
    public function copyAll(array $keys): array
    {
        $src = Storage::disk($this->fromDisk());
        $dst = Storage::disk($this->toDisk());
        $copied = [];

        try {
            foreach ($keys as $key) {
                // Baris ada tetapi objek S3 sudah hilang: jangan blokir penerimaan, cukup dicatat.
                if (! $src->exists($key)) {
                    Log::warning('File pelamar tidak ada di bucket, dilewati', ['key' => $key]);
                    continue;
                }

                $target = $this->targetKey($key);
                $stream = $src->readStream($key);

                if (! is_resource($stream)) {
                    throw new \RuntimeException("Gagal membaca file: {$key}");
                }

                try {
                    $ok = $dst->writeStream($target, $stream);
                } finally {
                    if (is_resource($stream)) {
                        fclose($stream);
                    }
                }

                if (! $ok || $dst->size($target) !== $src->size($key)) {
                    throw new \RuntimeException("Salinan tidak cocok: {$key}");
                }

                $copied[] = $key;
            }
        } catch (\Throwable $e) {
            $this->deleteFromTarget($copied);
            throw $e;
        }

        return $copied;
    }

    /** Hapus salinan di bucket karyawan (undo atau pembersihan). Tidak melempar error. */
    public function deleteFromTarget(array $keys): bool
    {
        return $this->deleteFrom($this->toDisk(), array_map([$this, 'targetKey'], $keys));
    }

    /** Hapus file asli di bucket pelamar (finalisasi). */
    public function deleteFromSource(array $keys): bool
    {
        return $this->deleteFrom($this->fromDisk(), $keys);
    }

    private function deleteFrom(string $disk, array $keys): bool
    {
        if (! $keys) {
            return true;
        }

        try {
            return (bool) Storage::disk($disk)->delete($keys);
        } catch (\Throwable $e) {
            report($e);
            Log::warning("Gagal menghapus file dari disk {$disk}", ['keys' => $keys]);
            return false;
        }
    }
}
