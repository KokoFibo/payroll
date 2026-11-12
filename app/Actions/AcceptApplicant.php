<?php

namespace App\Actions;

use App\Models\ApplicantAcceptance;
use App\Models\Karyawan;
use App\Models\Portal\Applicant;
use App\Models\User;
use App\Services\ApplicantFileMover;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Terima pelamar. Dua database terlibat dan TIDAK bisa satu transaksi:
 *   - database APPLICANT : sumber pelamar (dihapus setelah diterima)
 *   - database PAYROLL   : Karyawan, User, catatan undo, baris dokumen
 *
 * Urutan: salin file S3 -> transaksi payroll -> (langkah terakhir di dalamnya) hapus pelamar di
 * database applicant. Jika ada yang gagal sebelum commit, payroll di-rollback; jika commit gagal
 * setelah pelamar terhapus, baris pelamar dipulihkan dari salinan di memori.
 */
class AcceptApplicant
{
    public const UNDO_MINUTES   = 60;
    public const PLACEHOLDER_ID = 100;   // company/jabatan/department/placement sementara

    public function __construct(private ApplicantFileMover $files)
    {
    }

    public function handle(Applicant $a, ?int $by = null): Karyawan
    {
        $original = $a->getAttributes();   // untuk snapshot dan pemulihan darurat
        $fileRows = $this->files->rowsFor($a->applicant_id);

        // 1) Salin file dulu. Gagal di sini = belum ada yang berubah.
        $copied = $this->files->copyAll($fileRows->pluck('filename')->filter()->unique()->values()->all());

        $applicantDeleted = false;

        try {
            return DB::transaction(function () use ($a, $by, $original, $fileRows, $copied, &$applicantDeleted) {
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

                createUser($idKaryawan);

                // DUGAAN: createUser memakai username = id_karyawan. Sesuaikan jika berbeda.
                $user = User::where('username', $idKaryawan)->first();

                // Aplikasi payroll membaca dokumen karyawan dari tabel applicantfiles di database PAYROLL.
                $this->copyFileRowsToPayroll($fileRows, $copied);

                ApplicantAcceptance::create([
                    'applicant_id' => $a->applicant_id,
                    'id_karyawan'  => $idKaryawan,
                    'nama'         => $a->nama,
                    'user_id'      => $user?->id,
                    'snapshot'     => Crypt::encryptString(json_encode($original)),
                    'files'        => $copied,
                    'files_state'  => $copied ? 'copied' : 'none',
                    'accepted_by'  => $by,
                    'expires_at'   => now()->addMinutes(self::UNDO_MINUTES),
                ]);

                // Langkah TERAKHIR: hapus pelamar di database applicant (koneksi lain, autocommit).
                // Dokumen pelamar di database applicant dibiarkan sampai masa undo habis (lihat FinalizeAcceptances).
                $a->delete();
                $applicantDeleted = true;

                return $karyawan;
            });
        } catch (\Throwable $e) {
            // 2) Gagal: buang salinan di bucket karyawan; file asli tidak pernah dihapus.
            $this->files->deleteFromTarget($copied);

            // Pelamar sempat terhapus tetapi commit payroll gagal: kembalikan barisnya.
            if ($applicantDeleted) {
                $this->restoreApplicantRow($a, $original);
            }

            throw $e;
        }
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

    private function restoreApplicantRow(Applicant $a, array $original): void
    {
        try {
            $conn = $a->getConnection();
            if (! $conn->table($a->getTable())->where('id', $original['id'])->exists()) {
                $conn->table($a->getTable())->insert($original);
            }
        } catch (\Throwable $e) {
            report($e);   // pemulihan gagal: baris asli ada di snapshot terenkripsi dan di log error
        }
    }
}
