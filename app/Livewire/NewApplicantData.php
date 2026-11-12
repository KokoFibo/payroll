<?php

namespace App\Livewire;

use App\Actions\AcceptApplicant;
use App\Actions\DeleteApplicant;
use App\Actions\UndoAcceptance;
use App\Enums\ApplicantStatus;
use App\Models\ApplicantAcceptance;
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
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Daftar dan detail pelamar (pengganti DataApplicant).
 *
 * Sumber data: DATABASE APPLICANT (koneksi "applicant", model App\Models\Portal\*), bukan database payroll.
 * Hanya pelamar yang sudah submit (status >= 1) yang ditampilkan. Data lama di database payroll tidak dipakai.
 * Database payroll hanya dipakai untuk Karyawan, User, dan catatan undo (ApplicantAcceptance).
 *
 * Catatan: otorisasi tombol (role, username) masih di Blade, sesuai keputusan sebelumnya.
 */
class NewApplicantData extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

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

    #[Locked]
    public ?int $deleteId = null;

    #[Locked]
    public ?int $terimaId = null;

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
            $this->terimaId = $applicant->id;
            $this->dispatch('applicant-confirm-terima', text: $applicant->nama);
            $this->cancel();
            return;
        }

        $applicant->status = $newStatus;
        $applicant->save();

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

        $this->deleteId = $id;
        $this->dispatch('applicant-confirm-delete', text: $applicant->nama);
    }

    #[On('applicant-delete-confirmed')]
    public function delete(): void
    {
        $applicant = $this->findApplicant($this->deleteId);

        if (! $applicant) {
            $this->deleteId = null;
            $this->notify('error', 'Data tidak ditemukan (mungkin sudah dihapus).');
            return;
        }

        $result = app(DeleteApplicant::class)->handle($applicant);
        $this->deleteId = null;

        if ($result['s3_ok']) {
            $this->notify('success', 'Data dan file pelamar sudah dihapus.');
        } else {
            $this->notify('warning', 'Data dihapus, tetapi sebagian file di S3 gagal dihapus. Lihat log.');
        }
    }

    // ------------------------------------------------------------------
    // Terima pelamar (+ undo)
    // ------------------------------------------------------------------

    #[On('applicant-terima-confirmed')]
    public function diterima(): void
    {
        $id = $this->terimaId;

        if (! $id) {
            return;
        }

        // Cegah klik ganda atau dua admin menerima pelamar yang sama bersamaan.
        $lock = Cache::lock("accept-applicant:{$id}", 60);

        if (! $lock->get()) {
            $this->notify('info', 'Penerimaan sedang diproses. Tunggu sebentar.');
            return;
        }

        try {
            $this->prosesTerima($id);
        } finally {
            $lock->release();
        }
    }

    private function prosesTerima(int $id): void
    {
        // check_resigned_blacklist() dibuat untuk pelamar di database payroll. Ia menerima id pelamar, dan id itu
        // kini berasal dari database applicant: jika helper mencari Applicant lama berdasarkan id, ia memeriksa
        // ORANG YANG SALAH. Diblokir sampai Anda memastikan helper-nya (APPLICANT_SCREENING_VERIFIED=true).
        if (! config('applicant_admin.screening_verified')) {
            $this->notify('error', 'Pemeriksaan resign/blacklist belum disesuaikan untuk data baru, penerimaan ditahan.');
            return;
        }

        [$hasil, $refId] = array_pad(check_resigned_blacklist($id), 2, null);

        $pesan = match ($hasil) {
            1 => 'Karyawan ini sudah pernah RESIGNED, ID : ' . $refId,
            2 => 'Karyawan ini sudah pernah Blacklist, ID : ' . $refId,
            3 => 'Karyawan ini sudah pernah terdaftar dalam database, ID : ' . $refId,
            default => null,
        };

        if ($pesan) {
            $this->notify('error', $pesan);
            return;
        }

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

        try {
            $karyawan = app(AcceptApplicant::class)->handle($applicant, auth()->id());
        } catch (\Throwable $e) {
            report($e);
            $this->notify('error', 'Gagal memproses penerimaan. Tidak ada data yang berubah.');
            return;
        }

        $this->terimaId = null;
        $this->notify(
            'success',
            'Diterima sebagai karyawan ID ' . $karyawan->id_karyawan
            . '. Bisa dibatalkan selama ' . AcceptApplicant::UNDO_MINUTES . ' menit; setelah itu file dipindahkan permanen.'
        );
    }

    public function undoAcceptance(int $id): void
    {
        $acc = ApplicantAcceptance::find($id);

        if (! $acc) {
            $this->notify('error', 'Data pembatalan tidak ditemukan.');
            return;
        }

        try {
            app(UndoAcceptance::class)->handle($acc);
        } catch (\DomainException $e) {
            $this->notify('error', $e->getMessage());
            return;
        } catch (\Throwable $e) {
            report($e);
            $this->notify('error', 'Pembatalan gagal (mungkin email sudah dipakai pendaftaran baru). Tidak ada data yang berubah.');
            return;
        }

        $this->notify('success', 'Penerimaan dibatalkan. Pelamar kembali ke daftar.');
    }

    // ------------------------------------------------------------------
    // Data untuk view
    // ------------------------------------------------------------------

    #[Computed]
    public function recentAcceptances()
    {
        return ApplicantAcceptance::undoable()->latest()->get();
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
                'name'  => get_filename($f->filename),
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
        ]);
    }

    // ------------------------------------------------------------------
    // Helper
    // ------------------------------------------------------------------

    /** Satu-satunya pintu ke data pelamar (database applicant): hanya yang sudah submit. */
    private function baseQuery()
    {
        return Applicant::query()->where('status', '>=', 1);   // 0 = baru register, belum submit
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
        if (blank($key)) {
            return null;
        }

        return Cache::remember('applicant-file-url:' . md5($key), now()->addMinutes(8), function () use ($key) {
            try {
                return Storage::disk(config('applicant_admin.applicant_disk'))->temporaryUrl($key, now()->addMinutes(10));
            } catch (\Throwable $e) {
                return getUrl($key);   // helper lama sebagai cadangan
            }
        });
    }

    private function notify(string $type, string $title): void
    {
        $this->dispatch('message', type: $type, title: $title);
    }
}
