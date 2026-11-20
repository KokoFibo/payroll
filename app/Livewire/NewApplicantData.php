<?php

namespace App\Livewire;

use App\Actions\AcceptApplicant;
use App\Actions\DeleteApplicant;
use App\Exceptions\ApplicantConfigException;
use App\Exceptions\PresensiUserException;
use App\Enums\ApplicantStatus;
use App\Models\Karyawan;
use App\Models\Portal\Applicant;
use App\Models\Portal\ApplicantFile;
use App\Models\Portal\Company;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Daftar dan detail pelamar (pengganti DataApplicant).
 *
 * Sumber data: DATABASE APPLICANT (koneksi "applicant", model App\Models\Portal\*), bukan database payroll.
 * Hanya pelamar yang sudah submit (status >= 1) dari perusahaan di config applicant_admin.company_codes yang ditampilkan. Data lama di database payroll tidak dipakai.
 * Database payroll hanya dipakai untuk Karyawan dan User (fitur Terima karyawan).
 *
 * Catatan: otorisasi tombol (role, username) masih di Blade, sesuai keputusan sebelumnya.
 */
#[Title('Data Pelamar')]
class NewApplicantData extends Component
{
    use WithPagination;

    private const PER_PAGE = 10;

    /** Urutan tampil dokumen di halaman detail, dan label-nya. */
    private const DOC_LABELS = [
        'ktp'        => 'KTP / Passport',
        'kk'         => 'Kartu Keluarga',
        'ijazah'     => 'Ijazah',
        'nilai'      => 'Transkrip / SKHUN',
        'cv'         => 'CV',
        'pasfoto'    => 'Pas Foto',
        'npwp'       => 'NPWP',
        'paklaring'  => 'Paklaring',
        'bpjs'       => 'BPJS Ketenagakerjaan',
        'skck'       => 'SKCK',
        'sertifikat' => 'Sertifikat',
        'bri'        => 'Buku Tabungan BRI',
    ];

    #[Url(as: 'q', except: '')]
    public string $search = '';

    #[Url(as: 'status', except: '')]
    public string $statusFilter = '';

    #[Url(as: 'company', except: '')]
    public string $companyFilter = '';

    /** Pelamar yang sedang dilihat detailnya (null = tampilkan daftar). */
    #[Locked]
    public ?int $detailId = null;

    /** Pelamar yang statusnya sedang diubah. */
    #[Locked]
    public ?int $editId = null;

    public $status = null;

    /**
     * Konfirmasi yang menunggu jawaban: ['kind' => 'delete'|'terima', 'id' => int, 'name' => string].
     * Dirender server sebagai modal (tidak bergantung pada JavaScript/Alpine). Aksi hapus dan terima HANYA berjalan
     * bila konfirmasi dengan jenis yang sesuai sedang menunggu, jadi tidak bisa dipanggil langsung dari browser.
     */
    #[Locked]
    public ?array $confirm = null;

    /** Pesan hasil aksi terakhir (sukses, info, peringatan, error). Dirender server sebagai banner sampai ditutup. */
    #[Locked]
    public ?array $notice = null;

    // ------------------------------------------------------------------
    // Pencarian dan filter
    // ------------------------------------------------------------------

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatingCompanyFilter(): void
    {
        $this->resetPage();
    }

    // ------------------------------------------------------------------
    // Detail
    // ------------------------------------------------------------------

    public function show(int $id): void
    {
        $this->detailId = $id;
        $this->editId = null;
    }

    public function kembali(): void
    {
        $this->detailId = null;
    }

    // ------------------------------------------------------------------
    // Ubah status
    // ------------------------------------------------------------------

    public function edit(int $id): void
    {
        $applicant = $this->findApplicant($id);

        if (! $applicant) {
            $this->notify('error', 'Data pelamar tidak ditemukan.');
            return;
        }

        $this->editId = $id;
        $this->status = (int) $applicant->status;
    }

    public function cancel(): void
    {
        $this->editId = null;
        $this->status = null;
    }

    public function save(): void
    {
        $this->validate([
            'status' => ['required', Rule::in(ApplicantStatus::values())],
        ]);

        $applicant = $this->findApplicant($this->editId);

        if (! $applicant) {
            $this->cancel();
            $this->notify('error', 'Data pelamar tidak ditemukan.');
            return;
        }

        $newStatus = (int) $this->status;

        // Status "Diterima" tidak disimpan langsung: harus lewat konfirmasi dan proses penerimaan.
        if ($newStatus === ApplicantStatus::Diterima->value) {
            if (! $this->acceptEnabled) {
                $this->cancel();
                $this->notify('error', 'Fitur penerimaan karyawan tidak aktif di aplikasi ini.');
                return;
            }

            $this->confirm = ['kind' => 'terima', 'id' => (int) $applicant->id, 'name' => (string) $applicant->nama];
            $this->cancel();
            return;
        }

        try {
            $applicant->status = $newStatus;
            $applicant->save();
        } catch (\Throwable $e) {
            report($e);
            $this->cancel();
            $this->notify('error', 'Status gagal disimpan (lihat log). Periksa hak UPDATE untuk user database applicant.');
            return;
        }

        $this->cancel();
        $this->notify('success', 'Status sudah berhasil diubah.');
    }

    // ------------------------------------------------------------------
    // Hapus (satu jalur: data + dokumen + file S3)
    // ------------------------------------------------------------------

    public function confirmDelete(int $id): void
    {
        $applicant = $this->findApplicant($id);

        if (! $applicant) {
            $this->notify('error', 'Data pelamar tidak ditemukan.');
            return;
        }

        $this->confirm = ['kind' => 'delete', 'id' => (int) $applicant->id, 'name' => (string) $applicant->nama];
    }

    public function cancelConfirm(): void
    {
        $this->confirm = null;
    }

    /** Dipanggil dari tombol "Ya, hapus" pada modal. Tanpa konfirmasi hapus yang menunggu, tidak melakukan apa pun. */
    public function delete(): void
    {
        if (($this->confirm['kind'] ?? null) !== 'delete') {
            return;
        }

        $id = (int) $this->confirm['id'];
        $this->confirm = null;

        $applicant = $this->findApplicant($id);

        if (! $applicant) {
            $this->notify('error', 'Data tidak ditemukan (mungkin sudah dihapus).');
            return;
        }

        try {
            $result = app(DeleteApplicant::class)->handle($applicant);
        } catch (ApplicantConfigException $e) {
            $this->notify('error', $e->getMessage() . ' Tidak ada data yang dihapus.');
            return;
        } catch (\Throwable $e) {
            report($e);
            $this->notify('error', 'Gagal menghapus pelamar (lihat log). Periksa hak DELETE untuk user database applicant.');
            return;
        }

        if ($result['s3_ok']) {
            $this->notify('success', 'Data dan file pelamar sudah dihapus.');
        } else {
            $this->notify('warning', 'Data dihapus, tetapi sebagian file di S3 gagal dihapus. Lihat log.');
        }
    }

    // ------------------------------------------------------------------
    // Terima pelamar (tidak bisa dibatalkan)
    // ------------------------------------------------------------------

    /** Dipanggil dari tombol "Ya, terima" pada modal. Tanpa konfirmasi terima yang menunggu, tidak melakukan apa pun. */
    public function diterima(): void
    {
        if (! $this->acceptEnabled) {
            $this->notify('error', 'Fitur penerimaan karyawan tidak aktif di aplikasi ini.');
            return;
        }

        if (($this->confirm['kind'] ?? null) !== 'terima') {
            return;
        }

        $id = (int) $this->confirm['id'];
        $this->confirm = null;

        $this->withAcceptLock($id, fn () => $this->prosesTerima($id));
    }

    /**
     * Cegah klik ganda atau dua admin menerima pelamar yang sama bersamaan. Jika cache store aplikasi tidak mendukung
     * lock (mis. driver database tanpa tabel cache_locks), JANGAN gagal: lanjut tanpa kunci. Pelamar yang sudah diterima
     * tidak ditemukan lagi, jadi klik ganda tetap aman.
     */
    private function withAcceptLock(int $id, callable $work): void
    {
        $lock = null;

        try {
            $lock = Cache::lock("accept-applicant:{$id}", 60);

            if (! $lock->get()) {
                $this->notify('info', 'Penerimaan sedang diproses. Tunggu sebentar.');
                return;
            }
        } catch (\Throwable $e) {
            report($e);
            $lock = null;
        }

        try {
            $work();
        } finally {
            if ($lock) {
                try {
                    $lock->release();
                } catch (\Throwable $e) {
                    report($e);
                }
            }
        }
    }

    private function prosesTerima(int $id): void
    {
        $applicant = $this->findApplicant($id);

        if (! $applicant) {
            $this->notify('error', 'Data pelamar tidak ditemukan.');
            return;
        }

        if (Karyawan::where('id_file_karyawan', $applicant->applicant_id)->exists()) {
            $this->notify('error', 'Data karyawan ini sudah berada dalam database karyawan.');
            return;
        }

        if (User::where('email', $applicant->email)->exists()) {
            $this->notify('error', 'Email ini sudah terdaftar.');
            return;
        }

        // Akun presensi dibuat dengan email dan password dari tanggal lahir (createPresensiAccount): keduanya wajib ada.
        if (blank($applicant->email) || blank($applicant->tgl_lahir)) {
            $this->notify('error', 'Email dan tanggal lahir pelamar harus terisi (password akun presensi dibuat dari tanggal lahir).');
            return;
        }

        try {
            $result = app(AcceptApplicant::class)->handle($applicant, auth()->id());
        } catch (ApplicantConfigException $e) {
            $this->notify('error', $e->getMessage() . ' Tidak ada data yang berubah.');
            return;
        } catch (PresensiUserException $e) {
            $this->notify('error', 'Akun presensi gagal dibuat: ' . $e->getMessage() . '. Tidak ada data yang berubah.');
            return;
        } catch (\Throwable $e) {
            report($e);
            $this->notify('error', 'Gagal memproses penerimaan. Tidak ada data yang berubah.');
            return;
        }

        $idKaryawan = $result['karyawan']->id_karyawan;

        if ($result['status_marked']) {
            $this->notify('success', 'Diterima sebagai karyawan ID ' . $idKaryawan . '. Data dan dokumen sudah disalin; data pelamar tetap ada di database applicant sampai Anda menghapusnya.');
        } else {
            $this->notify('warning', 'Diterima sebagai karyawan ID ' . $idKaryawan . ', tetapi status pelamar gagal diubah menjadi Diterima (lihat log). Ubah manual.');
        }
    }

    // ------------------------------------------------------------------
    // Data untuk view
    // ------------------------------------------------------------------

    #[Computed]
    public function acceptEnabled(): bool
    {
        return (bool) config('applicant_admin.accept_enabled');
    }

    /** Peringatan konfigurasi untuk admin (tampil di atas daftar). */
    #[Computed]
    public function configWarnings(): array
    {
        $warn = [];
        $codes = config('applicant_admin.company_codes', []);

        if (! $codes) {
            $warn[] = 'APPLICANT_COMPANY_CODES belum diisi di .env, jadi tidak ada pelamar yang ditampilkan. Isi dengan kode perusahaan (kolom companies.code), pisahkan koma, atau * untuk semua perusahaan.';
        } elseif ($this->allowedCompanyIds === []) {
            $warn[] = 'Tidak ada perusahaan dengan kode ' . implode(', ', $codes) . ' di database applicant, jadi tidak ada pelamar yang ditampilkan.';
        }

        if (! $this->diskReady(config('applicant_admin.applicant_disk'))) {
            $warn[] = 'APPLICANT_FILES_DISK belum diatur atau tidak ada di config/filesystems.php: dokumen tidak bisa ditampilkan dan pelamar yang punya dokumen tidak bisa dihapus.';
        }

        if ($this->acceptEnabled && ! $this->diskReady(config('applicant_admin.karyawan_disk'))) {
            $warn[] = 'KARYAWAN_FILES_DISK belum diatur atau tidak ada di config/filesystems.php: penerimaan karyawan akan gagal.';
        }

        if ($this->acceptEnabled) {
            foreach (['getNextIdKaryawan', 'createPresensiAccount'] as $fn) {
                if (! function_exists($fn)) {
                    $warn[] = "Fungsi {$fn}() belum ada di aplikasi ini: penerimaan karyawan akan gagal. Tambahkan ke file helper (lihat folder snippets).";
                }
            }

            if (blank(config('services.presensi.url')) || blank(config('services.presensi.token'))) {
                $warn[] = 'PRESENSI_API_URL atau PRESENSI_API_TOKEN belum diisi (config/services.php entri presensi + .env): akun presensi tidak bisa dibuat, jadi penerimaan akan gagal.';
            }
        }

        return $warn;
    }

    /** Jumlah pelamar per status (untuk chip filter). */
    #[Computed]
    public function statusCounts(): array
    {
        return $this->baseQuery()
            ->when($this->companyFilter !== '', fn ($q) => $q->where('company_id', (int) $this->companyFilter))
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->all();
    }

    /** ID perusahaan yang diizinkan, dari kode di config. null = semua perusahaan. */
    #[Computed]
    public function allowedCompanyIds(): ?array
    {
        $codes = config('applicant_admin.company_codes', []);

        if (in_array('*', $codes, true)) {
            return null;        // semua perusahaan (harus ditulis sengaja)
        }

        if (! $codes) {
            return [];          // belum dikonfigurasi: tidak menampilkan apa pun
        }

        return Company::whereIn('code', $codes)->pluck('id')->all();
    }

    /** Perusahaan yang punya pelamar: [company_id => nama]. */
    #[Computed]
    public function companies(): array
    {
        $ids = $this->baseQuery()->distinct()->pluck('company_id')->all();
        $names = [];

        try {
            $names = Company::whereIn('id', $ids)->pluck('name', 'id')->all();   // tabel companies di database applicant
        } catch (\Throwable $e) {
            report($e);
        }

        $out = [];
        foreach ($ids as $id) {
            $out[$id] = $names[$id] ?? ('Perusahaan #' . $id);
        }
        asort($out);

        return $out;
    }

    #[Computed]
    public function person(): ?Applicant
    {
        return $this->findApplicant($this->detailId);
    }

    /** Dokumen pelamar yang sedang dilihat: label, url, nama file. */
    #[Computed]
    public function files(): array
    {
        $person = $this->person;

        if (! $person || blank($person->applicant_id)) {
            return [];
        }

        $order = array_flip(array_keys(self::DOC_LABELS));

        return ApplicantFile::where('id_karyawan', $person->applicant_id)
            ->get()
            ->sortBy(fn ($f) => $order[$f->type] ?? 99)
            ->values()
            ->map(fn ($f) => [
                'label' => self::DOC_LABELS[$f->type] ?? (filled($f->type) ? ucfirst($f->type) : 'Dokumen'),
                'name'  => function_exists('get_filename') ? get_filename($f->filename) : basename((string) $f->filename),
                'url'   => $this->fileUrl($f->filename),
            ])
            ->all();
    }

    public function render()
    {
        // Saat detail dibuka, daftar tidak perlu di-query.
        $applicants = $this->detailId ? null : $this->listQuery()->paginate(self::PER_PAGE);

        // Detail menunjuk data yang sudah hilang (dihapus/diterima): kembali ke daftar.
        if ($this->detailId && ! $this->person) {
            $this->detailId = null;
            $applicants = $this->listQuery()->paginate(self::PER_PAGE);
        }

        return view('livewire.new-applicant-data', [
            'applicants' => $applicants,
        ])->layout(config('applicant_admin.layout', 'components/layouts.app-tailwind'));
    }

    // ------------------------------------------------------------------
    // Helper
    // ------------------------------------------------------------------

    /**
     * Satu-satunya pintu ke data pelamar (database applicant): hanya yang sudah submit
     * dan hanya milik perusahaan yang diizinkan (config applicant_admin.company_codes).
     */
    private function baseQuery()
    {
        $query = Applicant::query()->where('status', '>=', 1);   // 0 = baru register, belum submit

        $ids = $this->allowedCompanyIds;

        if ($ids !== null) {
            $query->whereIn('company_id', $ids);   // daftar kosong = tidak ada hasil (aman jika kode salah)
        }

        return $query;
    }

    private function findApplicant(?int $id): ?Applicant
    {
        return $id ? $this->baseQuery()->find($id) : null;
    }

    private function listQuery()
    {
        $table = (new Applicant)->getTable();
        $term  = trim($this->search);
        $stat  = ApplicantStatus::tryFrom((int) $this->statusFilter);

        return $this->baseQuery()
            ->select("{$table}.*")
            // Jumlah dokumen per pelamar dalam satu query (menggantikan check_storage() per baris).
            ->selectSub(
                ApplicantFile::query()
                    ->selectRaw('count(*)')
                    ->whereColumn('id_karyawan', "{$table}.applicant_id"),
                'files_count'
            )
            ->when($term !== '', function ($q) use ($term) {
                $like = '%' . addcslashes($term, '\\%_') . '%';
                $q->where(fn ($w) => $w->where('nama', 'LIKE', $like)->orWhere('email', 'LIKE', $like));
            })
            ->when($this->statusFilter !== '' && $stat, fn ($q) => $q->where('status', $stat->value))
            ->when($this->companyFilter !== '', fn ($q) => $q->where('company_id', (int) $this->companyFilter))
            ->orderByDesc('created_at');   // terbaru di atas; ganti orderBy('created_at') untuk yang terlama dulu
    }

    /**
     * URL gambar. Bucket private memakai presigned URL (10 menit); di-cache 8 menit agar
     * URL tidak berubah di tiap render Livewire (gambar tidak dimuat ulang terus).
     */
    private function fileUrl(?string $key): ?string
    {
        $disk = config('applicant_admin.applicant_disk');

        if (blank($key) || ! $this->diskReady($disk)) {
            return null;
        }

        return Cache::remember('applicant-file-url:' . $disk . ':' . md5($key), now()->addMinutes(8), function () use ($key, $disk) {
            try {
                return Storage::disk($disk)->temporaryUrl($key, now()->addMinutes(10));
            } catch (\Throwable $e) {
                // cadangan: helper getUrl() milik aplikasi lama, jika ada
                return function_exists('getUrl') ? getUrl($key) : null;
            }
        });
    }

    private function diskReady(?string $name): bool
    {
        return filled($name) && config("filesystems.disks.{$name}") !== null;
    }

    private function notify(string $type, string $title): void
    {
        // Banner dirender server di halaman: tidak bergantung pada JavaScript/Alpine.
        $this->notice = ['type' => $type, 'title' => $title];
    }

    public function dismissNotice(): void
    {
        $this->notice = null;
    }
}
