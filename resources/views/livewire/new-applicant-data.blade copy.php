<div>
    @section('title', 'Data Applicant')

    {{-- Otorisasi tombol masih di Blade (sesuai keputusan sebelumnya) --}}
    @php $canManage = auth()->user()->username != '8217'; @endphp
    @php $showEtnis = auth()->user()->role == 8; @endphp

    <style>
        .applicant-table td,
        .applicant-table th { white-space: nowrap; vertical-align: middle; }
        .doc-thumb { height: 180px; background: #f8f9fa; }
        .doc-thumb img { max-height: 100%; object-fit: contain; }
    </style>

    <div class="p-3">
        <div class="card">

            {{-- ============================ DAFTAR ============================ --}}
            @if (! $this->detailId)
                <div class="card-header">
                    <h2 class="mb-0">Data Applicant</h2>
                </div>

                {{-- Pelamar yang baru diterima dan masih bisa dibatalkan --}}
                @if ($this->recentAcceptances->isNotEmpty())
                    <div class="alert alert-info mx-3 mt-3 mb-0">
                        <strong>Baru diterima</strong>, masih bisa dibatalkan:
                        @foreach ($this->recentAcceptances as $acc)
                            <div class="d-flex justify-content-between align-items-center mt-2"
                                wire:key="acc-{{ $acc->id }}">
                                <span>
                                    {{ $acc->nama }}, ID karyawan {{ $acc->id_karyawan }}
                                    <small class="text-muted">(batas {{ $acc->expires_at->diffForHumans() }})</small>
                                </span>
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                    wire:click="undoAcceptance({{ $acc->id }})"
                                    wire:confirm="Batalkan penerimaan {{ $acc->nama }}? Data karyawan dan akun login akan dihapus, dan pelamar dikembalikan ke daftar."
                                    wire:loading.attr="disabled">
                                    Batalkan
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Cari dan filter status --}}
                <div class="px-3 pt-3">
                    <div class="row g-2 align-items-center">
                        <div class="col-12 col-md-5 col-lg-4">
                            <input wire:model.live.debounce.400ms="search" type="search" class="form-control"
                                placeholder="Cari nama atau email" aria-label="Cari nama atau email">
                        </div>
                        @if (count($this->companies) > 1)
                            <div class="col-12 col-md-4 col-lg-3">
                                <select wire:model.live="companyFilter" class="form-select" aria-label="Filter perusahaan">
                                    <option value="">Semua perusahaan</option>
                                    @foreach ($this->companies as $cid => $cname)
                                        <option value="{{ $cid }}" wire:key="co-{{ $cid }}">{{ $cname }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="col-12 col-md">
                            <div class="d-flex flex-wrap gap-1">
                                <button type="button"
                                    class="btn btn-sm {{ $statusFilter === '' ? 'btn-dark' : 'btn-outline-dark' }}"
                                    wire:click="$set('statusFilter', '')">
                                    Semua ({{ array_sum($this->statusCounts) }})
                                </button>
                                @foreach (\App\Enums\ApplicantStatus::cases() as $s)
                                    <button type="button" wire:key="chip-{{ $s->value }}"
                                        class="btn btn-sm {{ (string) $s->value === $statusFilter ? 'btn-dark' : 'btn-outline-dark' }}"
                                        wire:click="$set('statusFilter', '{{ $s->value }}')">
                                        {{ $s->label() }} ({{ $this->statusCounts[$s->value] ?? 0 }})
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive" wire:loading.class="opacity-50"
                        wire:target="search,statusFilter,companyFilter,previousPage,nextPage,gotoPage">
                        <table class="table table-striped applicant-table">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Perusahaan</th>
                                    <th>Gender</th>
                                    @if ($showEtnis)
                                        <th>Etnis</th>
                                    @endif
                                    <th>Pendidikan</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Status Penerimaan</th>
                                    <th>Dokumen</th>
                                    <th>Dikirim</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($applicants as $d)
                                    @php $st = \App\Enums\ApplicantStatus::tryFrom((int) $d->status); @endphp
                                    @php $editing = $editId === (int) $d->id; @endphp
                                    <tr wire:key="applicant-{{ $d->id }}">
                                        <td>
                                            <div class="fw-semibold">{{ $d->nama }}</div>
                                            <small class="text-muted">{{ $d->email }}</small><br>
                                            <small class="text-muted">{{ $d->hp }}</small>
                                        </td>
                                        <td>{{ $this->companies[$d->company_id] ?? '-' }}</td>
                                        <td>{{ $d->gender }}</td>
                                        @if ($showEtnis)
                                            <td>{{ $d->etnis }}</td>
                                        @endif
                                        <td>
                                            {{ $d->pendidikan }}
                                            @if (filled($d->jurusan) || filled($d->nama_kampus))
                                                <br><small class="text-muted">
                                                    {{ $d->jurusan }}{{ filled($d->jurusan) && filled($d->nama_kampus) ? ', ' : '' }}{{ $d->nama_kampus }}
                                                </small>
                                            @endif
                                        </td>
                                        <td>{{ format_tgl($d->tgl_lahir) }}</td>
                                        <td>
                                            @if ($editing)
                                                <select class="form-select form-select-sm" wire:model="status"
                                                    aria-label="Status penerimaan">
                                                    @foreach (\App\Enums\ApplicantStatus::cases() as $s)
                                                        <option value="{{ $s->value }}">{{ $s->value }}. {{ $s->label() }}</option>
                                                    @endforeach
                                                </select>
                                                @error('status')
                                                    <div class="text-danger small">{{ $message }}</div>
                                                @enderror
                                            @else
                                                <span class="badge {{ $st?->color() ?? 'bg-secondary' }}">
                                                    {{ $st?->label() ?? '-' }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            {{-- 6 = jumlah dokumen wajib standar (ktp, kk, ijazah, nilai, cv, pasfoto) --}}
                                            <span class="badge {{ $d->files_count >= 6 ? 'bg-success' : 'bg-warning text-dark' }}">
                                                {{ $d->files_count }} file
                                            </span>
                                        </td>
                                        <td>{{ dateTimeFormat($d->created_at) }}</td>
                                        <td class="text-end">
                                            @if ($editing)
                                                <button type="button" class="btn btn-sm btn-primary" wire:click="save"
                                                    wire:loading.attr="disabled">Simpan</button>
                                                <button type="button" class="btn btn-sm btn-warning"
                                                    wire:click="cancel">Batal</button>
                                            @else
                                                <button type="button" class="btn btn-sm btn-success"
                                                    wire:click="show({{ $d->id }})">Lihat</button>
                                                @if ($canManage)
                                                    <button type="button" class="btn btn-sm btn-primary"
                                                        wire:click="edit({{ $d->id }})">Ubah Status</button>
                                                    <button type="button" class="btn btn-sm btn-danger"
                                                        wire:click="confirmDelete({{ $d->id }})">Hapus</button>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ $showEtnis ? 10 : 9 }}" class="text-center text-muted py-4">
                                            @if (filled($search) || $statusFilter !== '' || $companyFilter !== '')
                                                Tidak ada pelamar yang cocok. Ubah kata kunci atau pilih filter lain.
                                            @else
                                                Belum ada pelamar yang mengirim data.
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $applicants->links() }}
                </div>
            @endif

            {{-- ============================ DETAIL ============================ --}}
            @if ($this->detailId && $this->person)
                @php $p = $this->person; @endphp
                @php $st = \App\Enums\ApplicantStatus::tryFrom((int) $p->status); @endphp

                <div class="card-header">
                    <div class="d-flex flex-row justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1">{{ $p->nama }}</h2>
                            <span class="badge {{ $st?->color() ?? 'bg-secondary' }}">{{ $st?->label() ?? '-' }}</span>
                            <small class="text-muted ms-2">{{ $this->companies[$p->company_id] ?? '' }} &middot; Dikirim {{ dateTimeFormat($p->created_at) }}</small>
                        </div>
                        <button type="button" class="btn btn-dark" wire:click="kembali">Kembali</button>
                    </div>
                </div>

                @php
                    $join = fn (array $parts, string $sep = ' ') => collect($parts)->filter(fn ($v) => filled($v))->implode($sep);
                    $kontak = fn ($nama, $hubungan, $telp) => $join([$nama, filled($hubungan) ? '(' . $hubungan . ')' : null, $telp]);

                    $groups = [
                        'Kontak' => [
                            'Email' => $p->email,
                            'HP' => $p->hp,
                            'Telepon' => $p->telp,
                            'Alamat tinggal sekarang' => $p->alamat_tinggal_sekarang,
                        ],
                        'Data diri' => [
                            'Tempat / tanggal lahir' => $join([$p->tempat_lahir, format_tgl($p->tgl_lahir)], ' / '),
                            'Gender' => $p->gender,
                            'Status pernikahan' => $p->status_pernikahan,
                            'PTKP' => $p->ptkp,
                            'Golongan darah' => $p->golongan_darah,
                            'Agama' => $p->agama,
                            'Etnis' => $p->etnis,
                        ],
                        'Identitas' => [
                            'Jenis / nomor' => $join([$p->jenis_identitas, $p->no_identitas], ': '),
                            'Alamat identitas' => $p->alamat_identitas,
                        ],
                        'Pendidikan' => [
                            'Pendidikan terakhir' => $p->pendidikan,
                            'Jurusan' => $p->jurusan,
                            'Sekolah / kampus' => $p->nama_kampus,
                        ],
                        'Kontak darurat' => [
                            'Kontak 1' => $kontak($p->nama_contact_darurat, $p->hubungan_1, $p->contact_darurat_1),
                            'Kontak 2' => $kontak($p->nama_contact_darurat_2, $p->hubungan_2, $p->contact_darurat_2),
                        ],
                    ];
                @endphp

                <div class="card-body">
                    <div class="row g-3">
                        @foreach ($groups as $title => $rows)
                            <div class="col-12 col-lg-6" wire:key="group-{{ $loop->index }}">
                                <div class="card h-100">
                                    <div class="card-header fw-semibold">{{ $title }}</div>
                                    <div class="card-body">
                                        <dl class="row mb-0">
                                            @foreach ($rows as $label => $value)
                                                <dt class="col-sm-5 fw-normal text-muted">{{ $label }}</dt>
                                                <dd class="col-sm-7">{{ filled($value) ? $value : '-' }}</dd>
                                            @endforeach
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Dokumen --}}
                    <h5 class="mt-4 mb-3">Dokumen ({{ count($this->files) }})</h5>

                    @if (count($this->files) === 0)
                        <p class="text-muted">Pelamar ini belum mengunggah dokumen.</p>
                    @endif

                    <div x-data="{ src: '', title: '' }">
                        <div class="row g-4">
                            @foreach ($this->files as $f)
                                <div class="col-12 col-sm-6 col-lg-3" wire:key="file-{{ $loop->index }}">
                                    <div class="card border-0 shadow-sm h-100">
                                        @if ($f['url'])
                                            <button type="button"
                                                class="btn p-3 doc-thumb d-flex justify-content-center align-items-center"
                                                data-bs-toggle="modal" data-bs-target="#applicantImageModal"
                                                @click="src = @js($f['url']); title = @js($f['label'])"
                                                aria-label="Perbesar {{ $f['label'] }}">
                                                <img src="{{ $f['url'] }}" class="img-fluid" loading="lazy"
                                                    alt="{{ $f['label'] }}">
                                            </button>
                                        @else
                                            <div class="doc-thumb d-flex justify-content-center align-items-center text-muted">
                                                File tidak tersedia
                                            </div>
                                        @endif
                                        <div class="card-body text-center py-2">
                                            <div class="fw-semibold">{{ $f['label'] }}</div>
                                            <small class="text-muted text-truncate d-block" title="{{ $f['name'] }}">
                                                {{ $f['name'] }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Satu modal untuk semua gambar --}}
                        <div class="modal fade" id="applicantImageModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" x-text="title"></h5>
                                        <a :href="src" target="_blank" rel="noopener"
                                            class="btn btn-sm btn-outline-secondary ms-auto me-2">Buka di tab baru</a>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Tutup"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <img :src="src" class="img-fluid" alt="" style="max-height: 80vh;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        <button type="button" class="btn btn-dark px-4 py-2 shadow-sm" wire:click="kembali">
                            Kembali
                        </button>
                    </div>
                </div>
            @endif

        </div>
    </div>

    @script
        <script>
            window.addEventListener("applicant-confirm-delete", (event) => {
                Swal.fire({
                    title: "Yakin mau hapus data ini?",
                    text: event.detail.text + " dan semua dokumennya akan dihapus permanen.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#6c757d",
                    confirmButtonText: "Ya, hapus",
                    cancelButtonText: "Batal",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $wire.dispatch("applicant-delete-confirmed");
                    }
                });
            });

            window.addEventListener("applicant-confirm-terima", (event) => {
                Swal.fire({
                    title: "Pelamar ini akan diterima?",
                    text: event.detail.text,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, terima",
                    cancelButtonText: "Batal",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $wire.dispatch("applicant-terima-confirmed");
                    }
                });
            });
        </script>
    @endscript
</div>
