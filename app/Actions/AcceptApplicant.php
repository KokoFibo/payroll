<?php

namespace App\Actions;

use App\Enums\ApplicantStatus;
use App\Exceptions\ApplicantConfigException;
use App\Exceptions\PresensiUserException;
use App\Models\Karyawan;
use App\Models\Portal\Applicant;
use App\Models\Portal\Company;
use App\Services\ApplicantFileMover;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Terima pelamar jadi karyawan: data DISALIN ke tabel karyawan, dokumen DISALIN ke bucket karyawan.
 *
 * Data pelamar di database applicant dan file di bucket portal TIDAK dihapus: hanya statusnya diubah menjadi
 * "Diterima". Menghapusnya dilakukan manual lewat tombol Hapus. Penerimaan tidak bisa dibatalkan dari halaman ini
 * (karyawan yang terlanjur dibuat dan akun presensinya dihapus manual).
 *
 * Tiga sistem terlibat dan TIDAK bisa satu transaksi:
 *   - database APPLICANT : sumber pelamar (hanya dibaca; status diubah di akhir)
 *   - database PAYROLL   : Karyawan dan baris dokumen
 *   - API PRESENSI       : akun login presensi (dibuat lewat createPresensiAccount(), server lain)
 *
 * Urutan, dari yang paling mudah gagal ke yang paling kecil kemungkinan gagalnya:
 *   salin file ke bucket karyawan -> [transaksi payroll: Karyawan, dokumen, akun presensi] -> commit
 *   -> (setelah commit) ubah status pelamar menjadi Diterima.
 * Gagal sebelum commit: payroll di-rollback dan salinan file dibuang; data pelamar tidak pernah berubah.
 */
class AcceptApplicant
{
    public const PLACEHOLDER_ID = 100;   // company/jabatan/department/placement sementara

    public function __construct(private ApplicantFileMover $files)
    {
    }

    /**
     * @return array{karyawan: Karyawan, status_marked: bool}  status_marked=false: karyawan sudah jadi, tetapi status
     *         pelamar gagal diubah menjadi Diterima (dicatat di log; ubah manual).
     * @throws ApplicantConfigException bila prasyarat aplikasi kurang (tidak ada data yang berubah)
     * @throws PresensiUserException    bila akun presensi gagal dibuat (tidak ada data yang berubah)
     */
    public function handle(Applicant $a, ?int $by = null): array
    {
        // Fungsi helper milik aplikasi payroll harus ada. Dicek paling awal agar jika kurang belum ada yang berubah,
        // dan pesannya jelas (bukan error "undefined function" di tengah transaksi).
        foreach (['getNextIdKaryawan', 'createPresensiAccount'] as $fn) {
            if (! function_exists($fn)) {
                throw new ApplicantConfigException("Fungsi {$fn}() belum ada di aplikasi ini. Tambahkan ke file helper (lihat folder snippets).");
            }
        }

        // db_code untuk akun presensi = kolom `code` perusahaan pelamar (database applicant), mis. 'payroll'. Ditentukan
        // paling awal agar jika gagal belum ada yang disalin atau diubah.
        $dbCode = $this->presensiDbCode($a);

        $fileRows = $this->files->rowsFor($a->applicant_id);

        // 1) Salin file dulu. Gagal di sini = belum ada yang berubah.
        $copied = $this->files->copyAll($fileRows->pluck('filename')->filter()->unique()->values()->all());

        $presensiCreated = false;

        try {
            $karyawan = DB::transaction(function () use ($a, $fileRows, $copied, $dbCode, &$presensiCreated) {
                $idKaryawan = getNextIdKaryawan();
                $p = self::PLACEHOLDER_ID;

                $karyawan = Karyawan::create([
                    'id_karyawan'       => $idKaryawan,
                    'nama'              => $a->nama,
                    'email'             => trim($a->email, ' '),
                    'hp'                => $a->hp,
                    'telepon'           => $a->telp,
                    'tempat_lahir'      => $a->tempat_lahir,
                    'tanggal_lahir'     => $a->tgl_lahir,
                    'gender'            => $a->gender,
                    'status_pernikahan' => $a->status_pernikahan,
                    'golongan_darah'    => $a->golongan_darah,
                    'agama'             => $a->agama,
                    'etnis'             => $a->etnis,
                    'ptkp'              => $a->ptkp,
                    'kontak_darurat'    => $a->nama_contact_darurat,
                    'kontak_darurat2'   => $a->nama_contact_darurat_2,
                    'hp1'               => $a->contact_darurat_1,
                    'hp2'               => $a->contact_darurat_2,
                    'hubungan1'         => $a->hubungan_1,
                    'hubungan2'         => $a->hubungan_2,
                    'jenis_identitas'   => $a->jenis_identitas,
                    'no_identitas'      => $a->no_identitas,
                    'alamat_identitas'  => $a->alamat_identitas,
                    'alamat_tinggal'    => $a->alamat_tinggal_sekarang,
                    'id_file_karyawan'  => $a->applicant_id,
                    'status_karyawan'   => 'PKWT',
                    'jabatan_id'        => $p,
                    'company_id'        => $p,
                    'department_id'     => $p,
                    'placement_id'      => $p,
                    'tanggal_bergabung' => now()->toDateString(),
                    'pendidikan'        => $a->pendidikan,
                    'jurusan'           => $a->jurusan,
                    'nama_kampus'       => $a->nama_kampus,
                ]);

                // Aplikasi payroll membaca dokumen karyawan dari tabel applicantfiles di database PAYROLL.
                $this->copyFileRowsToPayroll($fileRows, $copied);

                // API presensi: efek samping eksternal yang tidak bisa di-rollback. Dikerjakan paling akhir di dalam
                // transaksi: gagal di sini = exception = payroll di-rollback dan salinan file dibuang. Bersih.
                $this->createPresensiUser($idKaryawan, $dbCode);
                $presensiCreated = true;

                return $karyawan;
            });
        } catch (\Throwable $e) {
            // Gagal: buang salinan di bucket karyawan. Data dan file pelamar tidak pernah disentuh.
            $this->files->deleteFromTarget($copied);

            // Akun presensi sudah terbuat tetapi commit payroll gagal (sangat jarang): tidak ada API hapus yang
            // dipakai di sini, jadi catat agar bisa dihapus manual.
            if ($presensiCreated) {
                Log::critical('Akun presensi sudah dibuat tetapi penerimaan pelamar gagal. Hapus akun presensi secara manual.', [
                    'applicant_id' => $a->applicant_id,
                    'email'        => $a->email,
                    'error'        => $e->getMessage(),
                ]);
            }

            throw $e;
        }

        // Commit berhasil: penerimaan final. Tandai pelamar sebagai Diterima (tidak fatal bila gagal: dobel penerimaan
        // tetap dicegah oleh pengecekan Karyawan.id_file_karyawan).
        $statusMarked = $this->markAccepted($a);

        Log::info('Pelamar diterima sebagai karyawan', [
            'applicant_id'  => $a->applicant_id,
            'id_karyawan'   => $karyawan->id_karyawan,
            'by_user_id'    => $by,
            'status_marked' => $statusMarked,
        ]);

        return ['karyawan' => $karyawan, 'status_marked' => $statusMarked];
    }

    /** Ubah status pelamar menjadi Diterima di database applicant. Tidak melempar exception: penerimaan sudah final. */
    private function markAccepted(Applicant $a): bool
    {
        try {
            $a->status = ApplicantStatus::Diterima->value;
            $a->save();

            return true;
        } catch (\Throwable $e) {
            report($e);
            Log::warning('Penerimaan selesai, tetapi status pelamar gagal diubah menjadi Diterima. Ubah manual.', [
                'applicant_id' => $a->applicant_id,
            ]);

            return false;
        }
    }

    /**
     * db_code = kolom `code` pada tabel companies di database applicant, sesuai company_id pelamar (mis. 'payroll').
     * Kode ini sama dengan nilai di APPLICANT_COMPANY_CODES, dan tidak berubah saat nama tampilan perusahaan diganti.
     * Ditolak (bukan diam-diam memakai nilai bawaan) bila perusahaan tidak ditemukan atau kodenya kosong,
     * supaya akun tidak terbuat di database presensi yang salah.
     *
     * @throws PresensiUserException
     */
    private function presensiDbCode(Applicant $a): string
    {
        $code = trim((string) Company::whereKey($a->company_id)->value('code'));

        if ($code === '') {
            throw new PresensiUserException('db_code tidak dapat ditentukan: perusahaan pelamar tidak ditemukan atau kodenya kosong');
        }

        return $code;
    }

    /** Memanggil helper createPresensiAccount() dan memeriksa hasilnya (helper mengembalikan array, tidak melempar exception). */
    private function createPresensiUser(string|int $idKaryawan, string $dbCode): void
    {
        try {
            $result = createPresensiAccount($idKaryawan, $dbCode);
        } catch (\Throwable $e) {
            report($e);
            throw new PresensiUserException('server presensi tidak dapat dihubungi', 0, $e);
        }

        if (is_array($result) && ! empty($result['status'])) {
            return;
        }

        $err    = is_array($result) ? ($result['error'] ?? null) : null;
        $detail = is_array($err) ? ($err['message'] ?? json_encode($err)) : (string) ($err ?? '');
        $head   = is_array($result) ? ($result['message'] ?? 'Gagal create user') : 'Gagal create user';

        throw new PresensiUserException(Str::limit(trim($head . ($detail !== '' ? ': ' . $detail : '')), 200));
    }

    private function copyFileRowsToPayroll($fileRows, array $copied): void
    {
        $rows = $fileRows->filter(fn ($r) => in_array($r->filename, $copied, true));

        if ($rows->isEmpty()) {
            return;
        }

        // Kolom di tabel payroll bisa berbeda dengan portal (sekarang: id_karyawan, originalName, filename).
        $hasCategory = Schema::hasColumn('applicantfiles', 'file_category');
        $hasType     = Schema::hasColumn('applicantfiles', 'type');

        DB::table('applicantfiles')->insert($rows->map(function ($r) use ($hasCategory, $hasType) {
            $row = [
                'id_karyawan'  => $r->id_karyawan,
                'originalName' => $r->originalName,
                'filename'     => $this->files->targetKey($r->filename),
                'created_at'   => $r->created_at?->toDateTimeString() ?? now()->toDateTimeString(),
                'updated_at'   => $r->updated_at?->toDateTimeString() ?? now()->toDateTimeString(),
            ];
            if ($hasCategory) {
                $row['file_category'] = $r->type;
            }
            if ($hasType) {
                $row['type'] = $r->type;
            }
            return $row;
        })->values()->all());
    }
}
