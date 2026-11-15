<?php

namespace App\Console\Commands;

use App\Models\Portal\Applicant;
use App\Models\Portal\ApplicantFile;
use App\Models\Portal\Company;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

/**
 * Memeriksa semua prasyarat halaman pelamar dan menunjukkan mana yang gagal, tanpa mengubah data:
 *   php artisan applicant:check            php artisan applicant:check --ping
 * Pemeriksaan hak akses database memakai pernyataan yang TIDAK mengubah baris (WHERE 1 = 0).
 * File uji di bucket memakai awalan __applicant_check__/ dan langsung dihapus lagi.
 */
class CheckApplicantSetup extends Command
{
    protected $signature   = 'applicant:check {--ping : Coba hubungi server presensi (GET ke alamat dasar, tanpa membuat akun)}';
    protected $description = 'Periksa konfigurasi halaman pelamar: database applicant, bucket S3, dan fitur Terima karyawan.';

    private int $failed = 0;

    public function handle(): int
    {
        $this->line('');
        $this->line('Konfigurasi dasar');
        $codes = config('applicant_admin.company_codes', []);
        $this->check(! empty($codes), 'APPLICANT_COMPANY_CODES', $codes ? implode(', ', $codes) : 'KOSONG: tidak ada pelamar yang akan ditampilkan');

        $this->line('');
        $this->line('Database applicant (koneksi "applicant")');
        $this->checkDatabase($codes);

        $this->line('');
        $this->line('Bucket portal (APPLICANT_FILES_DISK)');
        $this->checkApplicantDisk();

        $this->line('');
        if (config('applicant_admin.accept_enabled')) {
            $this->line('Fitur Terima karyawan (APPLICANT_ACCEPT_ENABLED=true)');
            $this->checkAccept($codes);
        } else {
            $this->line('Fitur Terima karyawan: mati (APPLICANT_ACCEPT_ENABLED). Dilewati.');
        }

        $this->line('');
        $this->line('Cache lock');
        $this->checkCache();

        $this->line('');
        if ($this->failed) {
            $this->error("{$this->failed} pemeriksaan GAGAL. Perbaiki lalu jalankan ulang: php artisan applicant:check");
            return self::FAILURE;
        }

        $this->info('Semua pemeriksaan lulus.');
        return self::SUCCESS;
    }

    // ------------------------------------------------------------------

    private function checkDatabase(array $codes): void
    {
        try {
            $conn = DB::connection('applicant');
            $conn->getPdo();
            $this->check(true, 'Koneksi', 'database ' . $conn->getDatabaseName());
        } catch (\Throwable $e) {
            $this->check(false, 'Koneksi', $this->firstLine($e));
            $this->line('        Periksa APPLICANT_DB_* di .env: user/password yang dipakai ditolak atau database tidak ada.');
            return;
        }

        $applicants = (new Applicant)->getTable();
        $files      = (new ApplicantFile)->getTable();

        try {
            $all       = Applicant::query()->count();
            $submitted = Applicant::query()->where('status', '>=', 1)->count();
            $this->check(true, "Baca {$applicants}", "{$all} baris, {$submitted} sudah submit (status >= 1)");
        } catch (\Throwable $e) {
            $this->check(false, "Baca {$applicants}", $this->firstLine($e));
        }

        // Pernyataan tanpa efek (WHERE 1 = 0): MySQL tetap memeriksa hak akses.
        $privileges = [
            "UBAH STATUS (UPDATE {$applicants})"      => "UPDATE `{$applicants}` SET status = status WHERE 1 = 0",
            "HAPUS pelamar (DELETE {$applicants})"    => "DELETE FROM `{$applicants}` WHERE 1 = 0",
            "HAPUS dokumen (DELETE {$files})"         => "DELETE FROM `{$files}` WHERE 1 = 0",
            "PEMULIHAN DARURAT (INSERT {$applicants})" => "INSERT INTO `{$applicants}` SELECT * FROM `{$applicants}` WHERE 1 = 0",
        ];

        foreach ($privileges as $label => $sql) {
            try {
                DB::connection('applicant')->statement($sql);
                $this->check(true, "Hak {$label}");
            } catch (\Throwable $e) {
                $this->check(false, "Hak {$label}", $this->firstLine($e));
            }
        }

        if ($codes && ! in_array('*', $codes, true)) {
            try {
                $found = Company::whereIn('code', $codes)->pluck('name', 'code')->all();

                foreach ($codes as $code) {
                    $this->check(isset($found[$code]), "Perusahaan kode '{$code}'", isset($found[$code]) ? "ditemukan, name = '{$found[$code]}'" : 'TIDAK ADA di tabel companies');
                }
            } catch (\Throwable $e) {
                $this->check(false, 'Baca companies', $this->firstLine($e));
            }
        }
    }

    private function checkApplicantDisk(): void
    {
        $disk = config('applicant_admin.applicant_disk');

        if (blank($disk)) {
            $this->check(false, 'APPLICANT_FILES_DISK', 'KOSONG: dokumen tidak tampil dan pelamar ber-dokumen tidak bisa dihapus');
            return;
        }

        $cfg = config("filesystems.disks.{$disk}");

        if ($cfg === null) {
            $this->check(false, "Disk '{$disk}'", 'TIDAK ADA di config/filesystems.php');
            return;
        }

        $this->check(true, "Disk '{$disk}'", 'driver ' . ($cfg['driver'] ?? '?') . (isset($cfg['bucket']) ? ", bucket {$cfg['bucket']}" : ''));

        // Menghapus key yang tidak ada tetap mensyaratkan izin DeleteObject.
        try {
            $ok = Storage::disk($disk)->delete(['__applicant_check__/none']);
            $this->check((bool) $ok, 'Izin hapus (s3:DeleteObject)', $ok ? '' : 'DITOLAK. Periksa kebijakan IAM kunci AWS untuk bucket ini');
        } catch (\Throwable $e) {
            $this->check(false, 'Izin hapus (s3:DeleteObject)', $this->firstLine($e));
        }

        if (($cfg['throw'] ?? false) !== true) {
            $this->warn('Disk ini memakai \'throw\' => false: kegagalan baca/hapus tidak melempar error. Disarankan \'throw\' => true.');
        }
    }

    private function checkAccept(array $codes): void
    {
        $karyawanDisk = config('applicant_admin.karyawan_disk');
        $applicantDisk = config('applicant_admin.applicant_disk');
        $cfg = filled($karyawanDisk) ? config("filesystems.disks.{$karyawanDisk}") : null;

        if (blank($karyawanDisk)) {
            $this->check(false, 'KARYAWAN_FILES_DISK', 'KOSONG');
        } elseif ($cfg === null) {
            $this->check(false, "Disk '{$karyawanDisk}'", 'TIDAK ADA di config/filesystems.php');
        } else {
            $this->check(true, "Disk '{$karyawanDisk}'", 'driver ' . ($cfg['driver'] ?? '?') . (isset($cfg['bucket']) ? ", bucket {$cfg['bucket']}" : ''));

            $appCfg = filled($applicantDisk) ? config("filesystems.disks.{$applicantDisk}") : null;
            if ($appCfg && isset($cfg['bucket']) && ($appCfg['bucket'] ?? null) === $cfg['bucket']) {
                $this->check(false, 'Bucket asal dan tujuan berbeda', "SAMA ({$cfg['bucket']}). Penerimaan akan ditolak karena key file tidak berubah");
            }

            try {
                $key = '__applicant_check__/ping-' . uniqid() . '.txt';
                $put = Storage::disk($karyawanDisk)->put($key, 'ok');
                $has = $put && Storage::disk($karyawanDisk)->exists($key);
                $del = Storage::disk($karyawanDisk)->delete($key);
                $this->check($put && $has && $del, 'Tulis/baca/hapus di bucket karyawan', $put ? ($has ? ($del ? '' : 'hapus DITOLAK') : 'file tidak terbaca') : 'tulis DITOLAK (s3:PutObject)');
            } catch (\Throwable $e) {
                $this->check(false, 'Tulis/baca/hapus di bucket karyawan', $this->firstLine($e));
            }
        }

        foreach (['getNextIdKaryawan', 'createPresensiAccount'] as $fn) {
            $this->check(function_exists($fn), "Fungsi {$fn}()", function_exists($fn) ? '' : 'BELUM ADA di file helper (lihat folder snippets)');
        }

        $karyawan = \App\Models\Karyawan::class;
        $this->check(class_exists($karyawan), 'Model Karyawan', class_exists($karyawan) ? '' : 'tidak ditemukan');

        try {
            $table = class_exists($karyawan) ? (new $karyawan)->getTable() : 'karyawans';
            $this->check(Schema::hasTable($table), "Tabel {$table} (database payroll)");
            $this->check(Schema::hasTable('applicantfiles'), 'Tabel applicantfiles (database payroll)');
        } catch (\Throwable $e) {
            $this->check(false, 'Tabel di database payroll', $this->firstLine($e));
        }

        $url   = config('services.presensi.url');
        $token = config('services.presensi.token');
        $this->check(filled($url), 'PRESENSI_API_URL', filled($url) ? $url : 'KOSONG (config/services.php entri presensi + .env)');
        $this->check(filled($token), 'PRESENSI_API_TOKEN', filled($token) ? 'terisi' : 'KOSONG');

        // db_code akun presensi = kolom `name` perusahaan pelamar.
        try {
            $names = Company::whereIn('code', array_values(array_diff($codes, ['*'])))->pluck('name', 'code')->all();
            foreach ($names as $code => $name) {
                $this->line("        db_code untuk perusahaan '{$code}' = '{$name}'  (harus sama dengan db_code yang dikenal presensi)");

                if (preg_match('/\s|[A-Z]/', (string) $name)) {
                    $this->warn("db_code '{$name}' mengandung spasi/huruf besar, seperti nama tampilan. Pastikan presensi memang mengenal nilai persis itu (kode seperti 'payroll' lebih lazim).");
                }
            }
        } catch (\Throwable $e) {
            // sudah dilaporkan di pemeriksaan database
        }

        if ($this->option('ping') && blank($url)) {
            $this->warn('--ping dilewati: PRESENSI_API_URL kosong.');
        }

        if ($this->option('ping') && filled($url)) {
            try {
                $resp = Http::timeout(5)->get($url);
                $this->check(true, 'Server presensi terjangkau', 'HTTP ' . $resp->status());
            } catch (\Throwable $e) {
                $this->check(false, 'Server presensi terjangkau', $this->firstLine($e));
            }
        }
    }

    private function checkCache(): void
    {
        try {
            $lock = Cache::lock('applicant-check', 5);
            $got  = $lock->get();
            if ($got) {
                $lock->release();
                $this->check(true, 'Cache::lock', 'driver ' . config('cache.default'));
            } else {
                $this->warn('Cache::lock tidak bisa diambil (kunci sedang dipakai).');
            }
        } catch (\Throwable $e) {
            // Bukan kegagalan: aplikasi lanjut tanpa kunci. Dilaporkan agar diketahui.
            $this->warn('Cache::lock tidak didukung oleh cache driver "' . config('cache.default') . '": ' . $this->firstLine($e));
            $this->warn('Aman: penerimaan tetap jalan tanpa kunci. Untuk mengaktifkannya gunakan driver file/redis atau buat tabel cache_locks.');
        }
    }

    // ------------------------------------------------------------------

    private function check(bool $ok, string $label, string $detail = ''): void
    {
        if (! $ok) {
            $this->failed++;
        }

        $this->line(sprintf('  [%s] %s%s', $ok ? ' OK  ' : 'GAGAL', $label, $detail !== '' ? '  -  ' . $detail : ''));
    }

    /**
     * Baris pertama pesan error + penyebab aslinya. Flysystem (S3) membungkus error AWS di exception sebelumnya, dan
     * pesan luarnya sering hanya "Unable to delete file located at: ..." tanpa alasan (403 AccessDenied, region salah, dll.).
     */
    private function firstLine(\Throwable $e): string
    {
        $msg  = trim((string) strtok($e->getMessage(), "\n"));
        $root = $e;

        while ($root->getPrevious()) {
            $root = $root->getPrevious();
        }

        if ($root !== $e) {
            $cause = trim((string) strtok($root->getMessage(), "\n"));

            if ($cause !== '' && $cause !== $msg) {
                $msg .= ' | penyebab: ' . $cause;
            }
        }

        return mb_strlen($msg) > 320 ? mb_substr($msg, 0, 320) . '...' : $msg;
    }
}
