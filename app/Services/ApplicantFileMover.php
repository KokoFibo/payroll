<?php

namespace App\Services;

use App\Exceptions\ApplicantConfigException;
use App\Models\Portal\ApplicantFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ApplicantFileMover
{
    private function fromDisk(): string   // bucket pelamar (bucket portal)
    {
        return $this->diskName('applicant_disk', 'APPLICANT_FILES_DISK');
    }

    private function toDisk(): string     // bucket karyawan
    {
        return $this->diskName('karyawan_disk', 'KARYAWAN_FILES_DISK');
    }

    /**
     * Nama disk wajib diisi dan wajib ada di config/filesystems.php. Storage::disk('') akan jatuh ke disk BAWAAN
     * aplikasi (mis. local), jadi nilai kosong ditolak, bukan dibiarkan lewat.
     */
    private function diskName(string $key, string $env): string
    {
        $name = (string) config("applicant_admin.{$key}");

        if ($name === '' || config("filesystems.disks.{$name}") === null) {
            throw new ApplicantConfigException("Disk {$env} belum diatur di .env atau tidak ada di config/filesystems.php.");
        }

        return $name;
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
        // Pengaman: jika bucket asal dan tujuan SAMA dan key tidak berubah, "menyalin" hanya menimpa dirinya sendiri,
        // lalu finalisasi akan menghapus satu-satunya salinan. Tolak sebelum ada yang berubah.
        $srcBucket = config('filesystems.disks.' . $this->fromDisk() . '.bucket');
        $dstBucket = config('filesystems.disks.' . $this->toDisk() . '.bucket');

        if ($keys && filled($srcBucket) && $srcBucket === $dstBucket) {
            foreach ($keys as $key) {
                if ($this->targetKey($key) === $key) {
                    throw new \RuntimeException(
                        "Bucket asal dan tujuan sama ({$srcBucket}) dengan key yang sama. "
                        . 'Periksa APPLICANT_FILES_DISK dan KARYAWAN_FILES_DISK, atau ubah targetKey().'
                    );
                }
            }
        }

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

    /** Hapus salinan di bucket karyawan (pembersihan saat penerimaan gagal). Tidak melempar error. */
    public function deleteFromTarget(array $keys): bool
    {
        if (! $keys) {
            return true;
        }

        return $this->deleteFrom($this->toDisk(), array_map([$this, 'targetKey'], $keys));
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
