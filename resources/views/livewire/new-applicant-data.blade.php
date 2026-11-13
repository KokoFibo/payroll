<div
    x-data="{
        toasts: [], tid: 0, dialog: null,
        labels: {
            delete: { title: 'Hapus data pelamar?', body: 'dan semua dokumennya akan dihapus permanen.', yes: 'Ya, hapus', btn: 'bg-rose-600 hover:bg-rose-700', tone: 'bg-rose-100 text-rose-600' },
            terima: { title: 'Terima pelamar ini?', body: 'akan dipindahkan ke data karyawan. Masih bisa dibatalkan selama sekitar {{ \App\Actions\AcceptApplicant::UNDO_MINUTES }} menit.', yes: 'Ya, terima', btn: 'bg-indigo-600 hover:bg-indigo-700', tone: 'bg-indigo-100 text-indigo-600' },
            undo: { title: 'Batalkan penerimaan?', body: 'Data karyawan dan akun login akan dihapus, lalu pelamar dikembalikan ke daftar.', yes: 'Ya, batalkan', btn: 'bg-amber-600 hover:bg-amber-700', tone: 'bg-amber-100 text-amber-600' },
        },
        get info() { return this.dialog ? this.labels[this.dialog.kind] : null },
        toast(d) {
            const id = ++this.tid;
            this.toasts.push({ id: id, type: d.type || 'info', title: d.title || '' });
            setTimeout(() => this.dismiss(id), d.type === 'error' ? 7000 : 4500);
        },
        dismiss(id) { this.toasts = this.toasts.filter(t => t.id !== id) },
        yes() {
            if (!this.dialog) return;
            const d = this.dialog;
            this.dialog = null;
            if (d.kind === 'delete') this.$wire.dispatch('applicant-delete-confirmed');
            else if (d.kind === 'terima') this.$wire.dispatch('applicant-terima-confirmed');
            else if (d.kind === 'undo') this.$wire.undoAcceptance(d.id);
        },
    }"
    @applicant-toast.window="toast($event.detail)"
    @applicant-confirm-delete.window="dialog = { kind: 'delete', name: $event.detail.text }"
    @applicant-confirm-terima.window="dialog = { kind: 'terima', name: $event.detail.text }"
    @keydown.escape.window="dialog = null"
    class="text-slate-800">

    <style>
        [x-cloak] { display: none !important; }
        .ap-noscroll { scrollbar-width: none; }
        .ap-noscroll::-webkit-scrollbar { display: none; }
    </style>

    {{-- Otorisasi tombol masih di Blade (sesuai keputusan sebelumnya) --}}
    @php
        $canManage = auth()->user()->username != '8217';
        $showEtnis = auth()->user()->role == 8;
        $showCompany = count($this->companies) > 1;

        // Ikon (gaya Feather, MIT). Dipakai lewat {{ $svg('nama', 'kelas') }}
        $paths = [
            'eye' => '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>',
            'pencil' => '<path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>',
            'trash' => '<polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>',
            'search' => '<circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>',
            'back' => '<line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>',
            'check' => '<polyline points="20 6 9 17 4 12"/>',
            'x' => '<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>',
            'minus' => '<line x1="5" y1="12" x2="19" y2="12"/>',
            'plus' => '<line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>',
            'rotl' => '<polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/>',
            'rotr' => '<polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/>',
            'external' => '<path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/>',
            'zoom' => '<path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"/>',
            'users' => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
            'inbox' => '<polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/>',
            'clock' => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
            'badge' => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>',
            'phone' => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>',
            'user' => '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
            'card' => '<rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/>',
            'book' => '<path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>',
            'shield' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
            'file' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>',
            'warn' => '<path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>',
        ];
        $svg = fn (string $name, string $cls = 'h-4 w-4') => new \Illuminate\Support\HtmlString(
            '<svg class="' . $cls . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" '
            . 'stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . ($paths[$name] ?? '') . '</svg>'
        );

        // Warna status (kelas ditulis utuh di sini agar terbaca Tailwind)
        $tones = [
            1 => ['badge' => 'bg-slate-100 text-slate-700 ring-slate-200',   'dot' => 'bg-slate-400'],
            2 => ['badge' => 'bg-sky-50 text-sky-700 ring-sky-200',          'dot' => 'bg-sky-500'],
            3 => ['badge' => 'bg-violet-50 text-violet-700 ring-violet-200', 'dot' => 'bg-violet-500'],
            4 => ['badge' => 'bg-amber-50 text-amber-700 ring-amber-200',    'dot' => 'bg-amber-500'],
            5 => ['badge' => 'bg-rose-50 text-rose-700 ring-rose-200',       'dot' => 'bg-rose-500'],
            6 => ['badge' => 'bg-zinc-100 text-zinc-700 ring-zinc-200',      'dot' => 'bg-zinc-500'],
            7 => ['badge' => 'bg-teal-50 text-teal-700 ring-teal-200',       'dot' => 'bg-teal-500'],
            8 => ['badge' => 'bg-emerald-50 text-emerald-700 ring-emerald-200', 'dot' => 'bg-emerald-500'],
        ];

        // Avatar berwarna konsisten per nama
        $meta = function ($d) {
            $hue = crc32((string) $d->nama) % 360;
            $initials = collect(preg_split('/\s+/', trim((string) $d->nama)))->filter()->take(2)
                ->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode('');
            $age = filled($d->tgl_lahir) ? \Carbon\Carbon::parse($d->tgl_lahir)->age : null;

            return [
                'st' => \App\Enums\ApplicantStatus::tryFrom((int) $d->status),
                'hue' => $hue,
                'initials' => $initials ?: '?',
                'age' => $age,
            ];
        };
    @endphp

    <div class="px-4 py-6 sm:px-6 lg:px-8">

        {{-- ============================ DAFTAR ============================ --}}
        @if (! $this->detailId)
            @php
                $c = $this->statusCounts;
                $stats = [
                    ['label' => 'Total pelamar', 'value' => array_sum($c), 'icon' => 'users', 'tone' => 'bg-indigo-50 text-indigo-600'],
                    ['label' => 'Baru masuk', 'value' => $c[1] ?? 0, 'icon' => 'inbox', 'tone' => 'bg-sky-50 text-sky-600'],
                    ['label' => 'Dalam proses', 'value' => ($c[2] ?? 0) + ($c[3] ?? 0) + ($c[4] ?? 0), 'icon' => 'clock', 'tone' => 'bg-amber-50 text-amber-600'],
                    ['label' => 'Cadangan & onboarding', 'value' => ($c[6] ?? 0) + ($c[7] ?? 0), 'icon' => 'badge', 'tone' => 'bg-emerald-50 text-emerald-600'],
                ];
            @endphp

            <div class="mb-6">
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Data Pelamar</h1>
                <p class="mt-1 text-sm text-slate-500">Pantau dan kelola pelamar yang sudah mengirim data lamaran.</p>
            </div>

            {{-- Ringkasan --}}
            <div class="mb-6 grid grid-cols-2 gap-3 lg:grid-cols-4">
                @foreach ($stats as $stat)
                    <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm" wire:key="stat-{{ $loop->index }}">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $stat['tone'] }}">
                            {{ $svg($stat['icon'], 'h-5 w-5') }}
                        </span>
                        <div class="min-w-0">
                            <div class="text-2xl font-bold leading-none tabular-nums text-slate-900">{{ $stat['value'] }}</div>
                            <div class="mt-1 truncate text-xs text-slate-500">{{ $stat['label'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pelamar yang baru diterima dan masih bisa dibatalkan --}}
            @if ($this->recentAcceptances->isNotEmpty())
                <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50/60 p-4">
                    <div class="mb-2 flex items-center gap-2 text-sm font-semibold text-emerald-800">
                        {{ $svg('badge', 'h-4 w-4') }}
                        Baru diterima <span class="font-normal text-emerald-700">&middot; masih bisa dibatalkan</span>
                    </div>
                    <div class="divide-y divide-emerald-100">
                        @foreach ($this->recentAcceptances as $acc)
                            <div class="flex flex-wrap items-center justify-between gap-2 py-2" wire:key="acc-{{ $acc->id }}">
                                <div class="text-sm text-slate-700">
                                    <span class="font-medium">{{ $acc->nama }}</span>
                                    <span class="text-slate-500">&rarr; ID karyawan {{ $acc->id_karyawan }}</span>
                                    <span class="text-xs text-slate-500">(batas {{ $acc->expires_at->diffForHumans() }})</span>
                                </div>
                                <button type="button"
                                    @click="dialog = { kind: 'undo', name: @js($acc->nama), id: {{ $acc->id }} }"
                                    class="rounded-lg border border-rose-200 bg-white px-3 py-1.5 text-xs font-semibold text-rose-600 transition hover:bg-rose-50">
                                    Batalkan
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Cari dan filter --}}
            <div class="mb-4 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="flex flex-col gap-3 sm:flex-row">
                    <div class="relative flex-1">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            {{ $svg('search', 'h-4 w-4') }}
                        </span>
                        <input wire:model.live.debounce.400ms="search" type="search"
                            placeholder="Cari nama atau email" aria-label="Cari nama atau email"
                            class="block w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-10 pr-3 text-sm text-slate-800 placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                    </div>
                    @if ($showCompany)
                        <select wire:model.live="companyFilter" aria-label="Filter perusahaan"
                            class="rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 sm:w-60">
                            <option value="">Semua perusahaan</option>
                            @foreach ($this->companies as $cid => $cname)
                                <option value="{{ $cid }}" wire:key="co-{{ $cid }}">{{ $cname }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>

                <div class="ap-noscroll -mx-1 mt-3 flex gap-2 overflow-x-auto px-1 pb-1">
                    <button type="button" wire:click="$set('statusFilter', '')"
                        class="inline-flex shrink-0 items-center gap-2 rounded-full px-3.5 py-1.5 text-sm font-medium transition {{ $statusFilter === '' ? 'bg-slate-900 text-white' : 'bg-white text-slate-600 ring-1 ring-inset ring-slate-200 hover:bg-slate-50' }}">
                        Semua
                        <span class="rounded-full px-1.5 text-xs tabular-nums {{ $statusFilter === '' ? 'bg-white/20' : 'bg-slate-100 text-slate-500' }}">{{ array_sum($c) }}</span>
                    </button>
                    @foreach (\App\Enums\ApplicantStatus::cases() as $s)
                        @php $active = (string) $s->value === $statusFilter; @endphp
                        <button type="button" wire:key="chip-{{ $s->value }}" wire:click="$set('statusFilter', '{{ $s->value }}')"
                            class="inline-flex shrink-0 items-center gap-2 rounded-full px-3.5 py-1.5 text-sm font-medium transition {{ $active ? 'bg-slate-900 text-white' : 'bg-white text-slate-600 ring-1 ring-inset ring-slate-200 hover:bg-slate-50' }}">
                            <span class="h-2 w-2 rounded-full {{ $tones[$s->value]['dot'] }}"></span>
                            {{ $s->label() }}
                            <span class="rounded-full px-1.5 text-xs tabular-nums {{ $active ? 'bg-white/20' : 'bg-slate-100 text-slate-500' }}">{{ $c[$s->value] ?? 0 }}</span>
                        </button>
                    @endforeach
                </div>
            </div>

            <div wire:loading.class="opacity-60" wire:target="search,statusFilter,companyFilter,previousPage,nextPage,gotoPage"
                class="transition-opacity">

                @if ($applicants->isEmpty())
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center">
                        <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                            {{ $svg('inbox', 'h-6 w-6') }}
                        </span>
                        <p class="mt-4 text-sm font-medium text-slate-700">
                            @if (filled($search) || $statusFilter !== '' || $companyFilter !== '')
                                Tidak ada pelamar yang cocok
                            @else
                                Belum ada pelamar yang mengirim data
                            @endif
                        </p>
                        @if (filled($search) || $statusFilter !== '' || $companyFilter !== '')
                            <p class="mt-1 text-sm text-slate-500">Ubah kata kunci atau pilih filter lain.</p>
                        @endif
                    </div>
                @else
                    {{-- Tabel (layar medium ke atas) --}}
                    <div class="hidden overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm md:block">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-100 text-sm">
                                <thead class="bg-slate-50/80">
                                    <tr class="text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                        <th class="px-5 py-3.5">Pelamar</th>
                                        @if ($showCompany)
                                            <th class="px-5 py-3.5">Perusahaan</th>
                                        @endif
                                        <th class="px-5 py-3.5">Pendidikan</th>
                                        <th class="px-5 py-3.5">Data diri</th>
                                        <th class="px-5 py-3.5">Status</th>
                                        <th class="px-5 py-3.5">Dokumen</th>
                                        <th class="px-5 py-3.5">Dikirim</th>
                                        <th class="px-5 py-3.5 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach ($applicants as $d)
                                        @php
                                            $m = $meta($d);
                                            $tone = $tones[$m['st']?->value ?? 1];
                                            $editing = $editId === (int) $d->id;
                                            // 6 = jumlah dokumen wajib standar (ktp, kk, ijazah, nilai, cv, pasfoto)
                                            $pct = min(100, (int) round($d->files_count / 6 * 100));
                                        @endphp
                                        <tr wire:key="applicant-{{ $d->id }}" class="transition-colors hover:bg-slate-50/70">
                                            <td class="px-5 py-4">
                                                <div class="flex items-center gap-3">
                                                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-xs font-bold"
                                                        style="background: hsl({{ $m['hue'] }} 70% 92%); color: hsl({{ $m['hue'] }} 50% 30%);">{{ $m['initials'] }}</span>
                                                    <div class="min-w-0 max-w-xs">
                                                        <div class="truncate font-semibold text-slate-900">{{ $d->nama }}</div>
                                                        <div class="truncate text-xs text-slate-500">{{ $d->email }}</div>
                                                        <div class="text-xs text-slate-500">{{ $d->hp }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            @if ($showCompany)
                                                <td class="px-5 py-4 text-slate-600">{{ $this->companies[$d->company_id] ?? '-' }}</td>
                                            @endif
                                            <td class="px-5 py-4">
                                                <div class="max-w-[15rem] text-slate-800">{{ $d->pendidikan ?: '-' }}</div>
                                                @if (filled($d->jurusan) || filled($d->nama_kampus))
                                                    <div class="max-w-[15rem] truncate text-xs text-slate-500">
                                                        {{ $d->jurusan }}{{ filled($d->jurusan) && filled($d->nama_kampus) ? ', ' : '' }}{{ $d->nama_kampus }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="whitespace-nowrap px-5 py-4">
                                                <div class="text-slate-800">{{ $d->gender }}</div>
                                                <div class="text-xs text-slate-500">
                                                    {{ format_tgl($d->tgl_lahir) }}{{ $m['age'] ? ' · ' . $m['age'] . ' th' : '' }}
                                                </div>
                                                @if ($showEtnis)
                                                    <div class="text-xs text-slate-500">Etnis: {{ $d->etnis }}</div>
                                                @endif
                                            </td>
                                            <td class="px-5 py-4">
                                                @if ($editing)
                                                    <select wire:model="status" aria-label="Status penerimaan"
                                                        class="w-44 rounded-lg border border-slate-300 bg-white px-2.5 py-1.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                                                        @foreach (\App\Enums\ApplicantStatus::cases() as $s)
                                                            <option value="{{ $s->value }}">{{ $s->value }}. {{ $s->label() }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('status')
                                                        <div class="mt-1 text-xs text-rose-600">{{ $message }}</div>
                                                    @enderror
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 whitespace-nowrap rounded-full px-2.5 py-1 text-xs font-medium ring-1 ring-inset {{ $tone['badge'] }}">
                                                        <span class="h-1.5 w-1.5 rounded-full {{ $tone['dot'] }}"></span>
                                                        {{ $m['st']?->label() ?? '-' }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-5 py-4">
                                                <div class="mb-1 text-xs text-slate-600">{{ $d->files_count }} file</div>
                                                <div class="h-1.5 w-24 overflow-hidden rounded-full bg-slate-100">
                                                    <div class="h-full rounded-full {{ $d->files_count >= 6 ? 'bg-emerald-500' : 'bg-amber-400' }}" style="width: {{ $pct }}%;"></div>
                                                </div>
                                            </td>
                                            <td class="whitespace-nowrap px-5 py-4">
                                                <div class="text-slate-700">{{ $d->created_at?->diffForHumans() }}</div>
                                                <div class="text-xs text-slate-400">{{ dateTimeFormat($d->created_at) }}</div>
                                            </td>
                                            <td class="whitespace-nowrap px-5 py-4 text-right">
                                                @if ($editing)
                                                    <button type="button" wire:click="save" wire:loading.attr="disabled"
                                                        class="rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-indigo-700 disabled:opacity-60">Simpan</button>
                                                    <button type="button" wire:click="cancel"
                                                        class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-600 transition hover:bg-slate-50">Batal</button>
                                                @else
                                                    <div class="inline-flex items-center gap-1.5">
                                                        <button type="button" title="Lihat detail" aria-label="Lihat detail" wire:click="show({{ $d->id }})"
                                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 transition hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-600">
                                                            {{ $svg('eye') }}
                                                        </button>
                                                        @if ($canManage)
                                                            <button type="button" title="Ubah status" aria-label="Ubah status" wire:click="edit({{ $d->id }})"
                                                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 transition hover:border-amber-200 hover:bg-amber-50 hover:text-amber-600">
                                                                {{ $svg('pencil') }}
                                                            </button>
                                                            <button type="button" title="Hapus" aria-label="Hapus" wire:click="confirmDelete({{ $d->id }})"
                                                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 transition hover:border-rose-200 hover:bg-rose-50 hover:text-rose-600">
                                                                {{ $svg('trash') }}
                                                            </button>
                                                        @endif
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Kartu (layar kecil) --}}
                    <div class="space-y-3 md:hidden">
                        @foreach ($applicants as $d)
                            @php
                                $m = $meta($d);
                                $tone = $tones[$m['st']?->value ?? 1];
                                $editing = $editId === (int) $d->id;
                                $pct = min(100, (int) round($d->files_count / 6 * 100));
                            @endphp
                            <div wire:key="card-{{ $d->id }}" class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                                <div class="flex items-start gap-3">
                                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full text-sm font-bold"
                                        style="background: hsl({{ $m['hue'] }} 70% 92%); color: hsl({{ $m['hue'] }} 50% 30%);">{{ $m['initials'] }}</span>
                                    <div class="min-w-0 flex-1">
                                        <div class="truncate font-semibold text-slate-900">{{ $d->nama }}</div>
                                        <div class="truncate text-xs text-slate-500">{{ $d->email }}</div>
                                        <div class="text-xs text-slate-500">{{ $d->hp }}</div>
                                    </div>
                                    @unless ($editing)
                                        <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium ring-1 ring-inset {{ $tone['badge'] }}">
                                            <span class="h-1.5 w-1.5 rounded-full {{ $tone['dot'] }}"></span>
                                            {{ $m['st']?->label() ?? '-' }}
                                        </span>
                                    @endunless
                                </div>

                                <dl class="mt-3 grid grid-cols-2 gap-x-3 gap-y-2 text-xs">
                                    <div>
                                        <dt class="text-slate-400">Pendidikan</dt>
                                        <dd class="text-slate-700">{{ $d->pendidikan ?: '-' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-slate-400">Data diri</dt>
                                        <dd class="text-slate-700">{{ $d->gender }}{{ $m['age'] ? ' · ' . $m['age'] . ' th' : '' }}</dd>
                                    </div>
                                    @if ($showCompany)
                                        <div>
                                            <dt class="text-slate-400">Perusahaan</dt>
                                            <dd class="text-slate-700">{{ $this->companies[$d->company_id] ?? '-' }}</dd>
                                        </div>
                                    @endif
                                    <div>
                                        <dt class="text-slate-400">Dikirim</dt>
                                        <dd class="text-slate-700">{{ $d->created_at?->diffForHumans() }}</dd>
                                    </div>
                                    <div class="col-span-2">
                                        <dt class="mb-1 text-slate-400">Dokumen &middot; {{ $d->files_count }} file</dt>
                                        <dd class="h-1.5 w-full overflow-hidden rounded-full bg-slate-100">
                                            <div class="h-full rounded-full {{ $d->files_count >= 6 ? 'bg-emerald-500' : 'bg-amber-400' }}" style="width: {{ $pct }}%;"></div>
                                        </dd>
                                    </div>
                                </dl>

                                @if ($editing)
                                    <div class="mt-3 flex items-center gap-2">
                                        <select wire:model="status" aria-label="Status penerimaan"
                                            class="min-w-0 flex-1 rounded-lg border border-slate-300 bg-white px-2.5 py-1.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                                            @foreach (\App\Enums\ApplicantStatus::cases() as $s)
                                                <option value="{{ $s->value }}">{{ $s->value }}. {{ $s->label() }}</option>
                                            @endforeach
                                        </select>
                                        <button type="button" wire:click="save" wire:loading.attr="disabled"
                                            class="rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-indigo-700 disabled:opacity-60">Simpan</button>
                                        <button type="button" wire:click="cancel"
                                            class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-600">Batal</button>
                                    </div>
                                    @error('status')
                                        <div class="mt-1 text-xs text-rose-600">{{ $message }}</div>
                                    @enderror
                                @else
                                    <div class="mt-3 flex items-center gap-2 border-t border-slate-100 pt-3">
                                        <button type="button" wire:click="show({{ $d->id }})"
                                            class="inline-flex flex-1 items-center justify-center gap-2 rounded-lg bg-slate-900 px-3 py-2 text-sm font-medium text-white transition hover:bg-slate-700">
                                            {{ $svg('eye') }} Lihat detail
                                        </button>
                                        @if ($canManage)
                                            <button type="button" title="Ubah status" aria-label="Ubah status" wire:click="edit({{ $d->id }})"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 hover:bg-amber-50 hover:text-amber-600">
                                                {{ $svg('pencil') }}
                                            </button>
                                            <button type="button" title="Hapus" aria-label="Hapus" wire:click="confirmDelete({{ $d->id }})"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 hover:bg-rose-50 hover:text-rose-600">
                                                {{ $svg('trash') }}
                                            </button>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        {{ $applicants->links() }}
                    </div>
                @endif
            </div>
        @endif

        {{-- ============================ DETAIL ============================ --}}
        @if ($this->detailId && $this->person)
            @php
                $p = $this->person;
                $m = $meta($p);
                $tone = $tones[$m['st']?->value ?? 1];
                $hue2 = ($m['hue'] + 40) % 360;
                $companyName = $this->companies[$p->company_id] ?? null;

                $join = fn (array $parts, string $sep = ' ') => collect($parts)->filter(fn ($v) => filled($v))->implode($sep);
                $kontak = fn ($nama, $hubungan, $telp) => $join([$nama, filled($hubungan) ? '(' . $hubungan . ')' : null, $telp]);

                $facts = [
                    ['Usia', $m['age'] ? $m['age'] . ' tahun' : '-'],
                    ['Gender', filled($p->gender) ? $p->gender : '-'],
                    ['Pendidikan', filled($p->pendidikan) ? $p->pendidikan : '-'],
                    ['Dokumen', count($this->files) . ' file'],
                ];

                $groups = [
                    'Kontak' => ['icon' => 'phone', 'rows' => [
                        'Email' => $p->email,
                        'HP' => $p->hp,
                        'Telepon' => $p->telp,
                        'Alamat tinggal sekarang' => $p->alamat_tinggal_sekarang,
                    ]],
                    'Data diri' => ['icon' => 'user', 'rows' => [
                        'Tempat / tanggal lahir' => $join([$p->tempat_lahir, format_tgl($p->tgl_lahir)], ' / '),
                        'Gender' => $p->gender,
                        'Status pernikahan' => $p->status_pernikahan,
                        'PTKP' => $p->ptkp,
                        'Golongan darah' => $p->golongan_darah,
                        'Agama' => $p->agama,
                        'Etnis' => $p->etnis,
                    ]],
                    'Identitas' => ['icon' => 'card', 'rows' => [
                        'Jenis / nomor' => $join([$p->jenis_identitas, $p->no_identitas], ': '),
                        'Alamat identitas' => $p->alamat_identitas,
                    ]],
                    'Pendidikan' => ['icon' => 'book', 'rows' => [
                        'Pendidikan terakhir' => $p->pendidikan,
                        'Jurusan' => $p->jurusan,
                        'Sekolah / kampus' => $p->nama_kampus,
                    ]],
                    'Kontak darurat' => ['icon' => 'shield', 'rows' => [
                        'Kontak 1' => $kontak($p->nama_contact_darurat, $p->hubungan_1, $p->contact_darurat_1),
                        'Kontak 2' => $kontak($p->nama_contact_darurat_2, $p->hubungan_2, $p->contact_darurat_2),
                    ]],
                ];

                // Alur seleksi. 5 (Ditolak) dan 6 (Cadangan) adalah cabang di luar alur.
                $flow = [1, 2, 3, 4, 7];
                $cur = array_search((int) $p->status, $flow, true);
            @endphp

            <button type="button" wire:click="kembali"
                class="mb-4 inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition hover:text-slate-900">
                {{ $svg('back') }} Kembali ke daftar
            </button>

            {{-- Profil --}}
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="h-24 sm:h-28"
                    style="background: linear-gradient(120deg, hsl({{ $m['hue'] }} 70% 58%), hsl({{ $hue2 }} 70% 46%));"></div>
                <div class="px-5 pb-6 sm:px-8">
                    <div class="-mt-10 flex flex-wrap items-end gap-4">
                        <span class="flex h-20 w-20 shrink-0 items-center justify-center rounded-2xl border-4 border-white text-2xl font-bold shadow-md"
                            style="background: hsl({{ $m['hue'] }} 70% 92%); color: hsl({{ $m['hue'] }} 50% 30%);">{{ $m['initials'] }}</span>
                        <div class="min-w-0 flex-1 pb-1">
                            <h1 class="truncate text-2xl font-bold tracking-tight text-slate-900">{{ $p->nama }}</h1>
                            <div class="mt-1.5 flex flex-wrap items-center gap-x-3 gap-y-1.5 text-sm text-slate-500">
                                <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium ring-1 ring-inset {{ $tone['badge'] }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $tone['dot'] }}"></span>
                                    {{ $m['st']?->label() ?? '-' }}
                                </span>
                                @if (filled($companyName))
                                    <span>{{ $companyName }}</span>
                                @endif
                                <span>Dikirim {{ $p->created_at?->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-2 gap-3 sm:grid-cols-4">
                        @foreach ($facts as [$label, $value])
                            <div class="rounded-xl bg-slate-50 px-4 py-3" wire:key="fact-{{ $loop->index }}">
                                <div class="text-xs font-medium uppercase tracking-wider text-slate-400">{{ $label }}</div>
                                <div class="mt-0.5 truncate text-sm font-semibold text-slate-800">{{ $value }}</div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Alur seleksi --}}
                    <div class="mt-6">
                        <div class="mb-3 text-xs font-medium uppercase tracking-wider text-slate-400">Tahap seleksi</div>
                        <ol class="ap-noscroll flex items-center overflow-x-auto pb-1">
                            @foreach ($flow as $idx => $val)
                                @php
                                    $step = \App\Enums\ApplicantStatus::from($val);
                                    $done = $cur !== false && $idx < $cur;
                                    $now = $cur !== false && $idx === $cur;
                                @endphp
                                <li class="flex shrink-0 items-center" wire:key="step-{{ $val }}">
                                    <span class="flex h-7 w-7 items-center justify-center rounded-full text-xs font-semibold {{ $done ? 'bg-emerald-500 text-white' : ($now ? 'bg-indigo-600 text-white ring-4 ring-indigo-100' : 'bg-slate-100 text-slate-400') }}">
                                        @if ($done)
                                            {{ $svg('check', 'h-3.5 w-3.5') }}
                                        @else
                                            {{ $idx + 1 }}
                                        @endif
                                    </span>
                                    <span class="ml-2 whitespace-nowrap text-sm {{ $now ? 'font-semibold text-slate-900' : ($done ? 'text-slate-600' : 'text-slate-400') }}">{{ $step->label() }}</span>
                                    @if (! $loop->last)
                                        <span class="mx-3 h-px w-6 sm:w-10 {{ $done ? 'bg-emerald-300' : 'bg-slate-200' }}"></span>
                                    @endif
                                </li>
                            @endforeach
                        </ol>
                        @if ($cur === false)
                            <p class="mt-3 text-sm text-slate-500">
                                Status saat ini <span class="font-semibold text-slate-700">{{ $m['st']?->label() ?? '-' }}</span>
                                berada di luar alur seleksi utama.
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Data pelamar --}}
            <div class="mt-4 grid gap-4 lg:grid-cols-2">
                @foreach ($groups as $title => $group)
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm" wire:key="group-{{ $loop->index }}">
                        <div class="mb-3 flex items-center gap-3">
                            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                                {{ $svg($group['icon'], 'h-[18px] w-[18px]') }}
                            </span>
                            <h2 class="text-sm font-semibold text-slate-900">{{ $title }}</h2>
                        </div>
                        <dl class="divide-y divide-slate-100 text-sm">
                            @foreach ($group['rows'] as $label => $value)
                                <div class="grid grid-cols-5 gap-3 py-2.5">
                                    <dt class="col-span-2 text-slate-500">{{ $label }}</dt>
                                    <dd class="col-span-3 break-words font-medium text-slate-800">{{ filled($value) ? $value : '-' }}</dd>
                                </div>
                            @endforeach
                        </dl>
                    </div>
                @endforeach
            </div>

            {{-- Dokumen --}}
            <div class="mb-4 mt-8 flex items-center gap-3">
                <h2 class="text-lg font-bold tracking-tight text-slate-900">Dokumen</h2>
                <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-600">{{ count($this->files) }} file</span>
            </div>

            @if (count($this->files) === 0)
                <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-12 text-center">
                    <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                        {{ $svg('file', 'h-6 w-6') }}
                    </span>
                    <p class="mt-3 text-sm text-slate-500">Pelamar ini belum mengunggah dokumen.</p>
                </div>
            @endif

            {{-- Galeri dan pratinjau. Pratinjau dipindah ke <body> (x-teleport) agar selalu pas di layar,
                 berapa pun posisi scroll, dan tidak terpengaruh transform/overflow elemen induk layout. --}}
            <div wire:key="gallery-{{ $p->id }}"
                x-data="{
                    open: false, i: 0, items: [], zoom: 1, rot: 0, natW: 0, natH: 0, stageW: 0, stageH: 0, pan: null,

                    get current() { return this.items[this.i] || null },
                    get currentUrl() { return this.current ? this.current.url : null },
                    get swapped() { return Math.round(Math.abs(this.rot) / 90) % 2 === 1 },
                    get fit() {
                        const w = this.swapped ? this.natH : this.natW;
                        const h = this.swapped ? this.natW : this.natH;
                        if (!w || !h || !this.stageW || !this.stageH) return 1;
                        return Math.min(this.stageW / w, this.stageH / h);
                    },
                    get imgW() { return this.natW * this.fit * this.zoom },
                    get imgH() { return this.natH * this.fit * this.zoom },
                    get boxW() { return this.swapped ? this.imgH : this.imgW },
                    get boxH() { return this.swapped ? this.imgW : this.imgH },

                    init() {
                        this.$watch('open', v => document.body.classList.toggle('overflow-hidden', v));
                        this.$watch('currentUrl', () => { this.natW = 0; this.natH = 0; });
                    },
                    stage() { return document.getElementById('applicant-stage') },
                    measure() {
                        const s = this.stage();
                        if (s) { this.stageW = s.clientWidth - 24; this.stageH = s.clientHeight - 24; }
                    },
                    show(el) {
                        // Daftar dibaca dari DOM saat dibuka: URL selalu yang terbaru.
                        this.items = [...this.$root.querySelectorAll('[data-viewable]')]
                            .map(b => ({ url: b.dataset.url, label: b.dataset.label }));
                        this.i = Math.max(0, this.items.findIndex(x => x.url === el.dataset.url));
                        this.zoom = 1; this.rot = 0;
                        this.open = true;
                        this.$nextTick(() => this.measure());
                    },
                    close() { this.open = false; this.pan = null },
                    next() { this.i = (this.i + 1) % this.items.length; this.zoom = 1; this.rot = 0 },
                    prev() { this.i = (this.i - 1 + this.items.length) % this.items.length; this.zoom = 1; this.rot = 0 },
                    onLoad(e) {
                        this.natW = e.target.naturalWidth;
                        this.natH = e.target.naturalHeight;
                        this.measure();
                    },
                    setZoom(z) {
                        const s = this.stage();
                        const next = Math.min(8, Math.max(1, +z.toFixed(3)));
                        if (!s || next === this.zoom) return;
                        // Pertahankan titik tengah tampilan saat zoom
                        const fx = s.scrollWidth ? (s.scrollLeft + s.clientWidth / 2) / s.scrollWidth : 0.5;
                        const fy = s.scrollHeight ? (s.scrollTop + s.clientHeight / 2) / s.scrollHeight : 0.5;
                        this.zoom = next;
                        this.$nextTick(() => {
                            s.scrollLeft = fx * s.scrollWidth - s.clientWidth / 2;
                            s.scrollTop = fy * s.scrollHeight - s.clientHeight / 2;
                        });
                    },
                    zoomIn() { this.setZoom(this.zoom * 1.25) },
                    zoomOut() { this.setZoom(this.zoom / 1.25) },
                    fitScreen() { this.setZoom(1); const s = this.stage(); if (s) { s.scrollLeft = 0; s.scrollTop = 0; } },
                    actualSize() { this.setZoom(1 / this.fit) },
                    rotate(deg) { this.rot += deg; this.$nextTick(() => this.measure()) },
                    onWheel(e) {
                        if (e.ctrlKey || e.metaKey) { e.preventDefault(); e.deltaY < 0 ? this.zoomIn() : this.zoomOut(); }
                    },
                    startPan(e) {
                        const s = this.stage();
                        if (this.zoom <= 1 || e.pointerType !== 'mouse' || e.button !== 0 || !s) return;
                        this.pan = { x: e.clientX, y: e.clientY, l: s.scrollLeft, t: s.scrollTop };
                    },
                    movePan(e) {
                        const s = this.stage();
                        if (!this.pan || !s) return;
                        s.scrollLeft = this.pan.l - (e.clientX - this.pan.x);
                        s.scrollTop = this.pan.t - (e.clientY - this.pan.y);
                    },
                    endPan() { this.pan = null },
                    onKey(e) {
                        if (!this.open) return;
                        switch (e.key) {
                            case 'Escape': this.close(); break;
                            case 'ArrowRight': if (this.zoom === 1 && this.items.length > 1) { e.preventDefault(); this.next(); } break;
                            case 'ArrowLeft': if (this.zoom === 1 && this.items.length > 1) { e.preventDefault(); this.prev(); } break;
                            case '+': case '=': e.preventDefault(); this.zoomIn(); break;
                            case '-': e.preventDefault(); this.zoomOut(); break;
                            case '0': this.fitScreen(); break;
                            case 'r': case 'R': this.rotate(90); break;
                        }
                    },
                }"
                @keydown.window="onKey($event)"
                @resize.window="open && measure()">

                <div class="grid grid-cols-2 gap-4 md:grid-cols-3 xl:grid-cols-4">
                    @foreach ($this->files as $f)
                        <div class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg"
                            wire:key="file-{{ $loop->index }}">
                            @if ($f['url'])
                                <button type="button" data-viewable data-url="{{ $f['url'] }}" data-label="{{ $f['label'] }}"
                                    @click="show($el)" aria-label="Perbesar {{ $f['label'] }}"
                                    class="relative block aspect-[4/3] w-full cursor-zoom-in bg-slate-100">
                                    <img src="{{ $f['url'] }}" loading="lazy" alt="{{ $f['label'] }}"
                                        class="absolute inset-0 h-full w-full object-contain">
                                    <span class="absolute inset-0 flex items-center justify-center bg-slate-900/0 text-white opacity-0 transition group-hover:bg-slate-900/40 group-hover:opacity-100">
                                        <span class="inline-flex items-center gap-2 rounded-full bg-slate-900/70 px-3 py-1.5 text-xs font-medium">
                                            {{ $svg('zoom', 'h-4 w-4') }} Perbesar
                                        </span>
                                    </span>
                                </button>
                            @else
                                <div class="flex aspect-[4/3] w-full items-center justify-center bg-slate-100 text-sm text-slate-400">File tidak tersedia</div>
                            @endif
                            <div class="px-4 py-3">
                                <div class="text-sm font-semibold text-slate-800">{{ $f['label'] }}</div>
                                <div class="truncate text-xs text-slate-400" title="{{ $f['name'] }}">{{ $f['name'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <template x-teleport="body">
                    <div x-show="open" x-cloak x-transition.opacity.duration.150ms
                        class="fixed inset-0 z-[3000] flex flex-col bg-slate-900/95 text-white"
                        role="dialog" aria-modal="true" aria-label="Pratinjau dokumen">

                        <div class="flex flex-wrap items-center gap-x-4 gap-y-2 border-b border-white/10 bg-white/5 px-4 py-2.5">
                            <div class="font-semibold" x-text="current ? current.label : ''"></div>
                            <div class="text-sm text-slate-400" x-text="items.length > 1 ? (i + 1) + ' / ' + items.length : ''"></div>

                            <div class="ml-auto flex flex-wrap items-center gap-1.5">
                                <button type="button" @click="zoomOut()" title="Perkecil (-)" aria-label="Perkecil"
                                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-white/10 bg-white/10 transition hover:bg-white/20">{{ $svg('minus') }}</button>
                                <span class="w-14 text-center text-sm tabular-nums text-slate-300" x-text="Math.round(fit * zoom * 100) + '%'"></span>
                                <button type="button" @click="zoomIn()" title="Perbesar (+)" aria-label="Perbesar"
                                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-white/10 bg-white/10 transition hover:bg-white/20">{{ $svg('plus') }}</button>
                                <button type="button" @click="fitScreen()" title="Pas layar (0)"
                                    class="inline-flex h-9 items-center rounded-lg border border-white/10 bg-white/10 px-3 text-sm transition hover:bg-white/20">Pas layar</button>
                                <button type="button" @click="actualSize()" title="Ukuran asli"
                                    class="inline-flex h-9 items-center rounded-lg border border-white/10 bg-white/10 px-3 text-sm transition hover:bg-white/20">1:1</button>
                                <button type="button" @click="rotate(-90)" title="Putar kiri" aria-label="Putar kiri"
                                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-white/10 bg-white/10 transition hover:bg-white/20">{{ $svg('rotl') }}</button>
                                <button type="button" @click="rotate(90)" title="Putar kanan (R)" aria-label="Putar kanan"
                                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-white/10 bg-white/10 transition hover:bg-white/20">{{ $svg('rotr') }}</button>
                                <a :href="currentUrl" target="_blank" rel="noopener" title="Buka di tab baru" aria-label="Buka di tab baru"
                                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-white/10 bg-white/10 transition hover:bg-white/20">{{ $svg('external') }}</a>
                                <button type="button" @click="close()" title="Tutup (Esc)" aria-label="Tutup"
                                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-white/10 bg-white/10 transition hover:bg-white/20">{{ $svg('x') }}</button>
                            </div>
                        </div>

                        {{-- m-auto pada kotak (bukan items-center) agar gambar tinggi bisa di-scroll sampai ujung atas --}}
                        <div id="applicant-stage" class="flex min-h-0 flex-1 overflow-auto p-3"
                            :class="{ 'cursor-grab': zoom > 1 && !pan, 'cursor-grabbing select-none': pan }"
                            style="overscroll-behavior: contain;"
                            @click.self="zoom === 1 && close()"
                            @wheel="onWheel($event)"
                            @dblclick="zoom > 1 ? fitScreen() : setZoom(2)"
                            @pointerdown="startPan($event)" @pointermove="movePan($event)"
                            @pointerup="endPan()" @pointercancel="endPan()" @pointerleave="endPan()">
                            <div class="relative m-auto shrink-0" :style="{ width: boxW + 'px', height: boxH + 'px' }">
                                <img :src="currentUrl" :alt="current ? current.label : ''" draggable="false" @load="onLoad($event)"
                                    class="absolute left-1/2 top-1/2 max-h-none max-w-none select-none rounded bg-white shadow-2xl transition-transform duration-200"
                                    :style="{ width: imgW + 'px', height: imgH + 'px', transform: 'translate(-50%, -50%) rotate(' + rot + 'deg)' }">
                            </div>
                        </div>

                        <div x-show="open && !natW" class="pointer-events-none absolute inset-0 flex items-center justify-center text-slate-400">
                            Memuat dokumen...
                        </div>

                        <template x-if="items.length > 1">
                            <div>
                                <button type="button" @click="prev()" aria-label="Dokumen sebelumnya"
                                    class="absolute bottom-4 left-4 flex h-11 w-11 items-center justify-center rounded-full bg-white/90 text-2xl text-slate-900 shadow-lg transition hover:bg-white sm:bottom-auto sm:top-1/2 sm:-translate-y-1/2">&lsaquo;</button>
                                <button type="button" @click="next()" aria-label="Dokumen berikutnya"
                                    class="absolute bottom-4 right-4 flex h-11 w-11 items-center justify-center rounded-full bg-white/90 text-2xl text-slate-900 shadow-lg transition hover:bg-white sm:bottom-auto sm:top-1/2 sm:-translate-y-1/2">&rsaquo;</button>
                            </div>
                        </template>
                    </div>
                </template>
            </div>
        @endif

    </div>

    {{-- Notifikasi dan dialog konfirmasi (mandiri, tidak bergantung SweetAlert atau handler layout) --}}
    <template x-teleport="body">
        <div>
            <div class="pointer-events-none fixed right-4 top-4 z-[4000] flex w-full max-w-sm flex-col gap-2">
                <template x-for="t in toasts" :key="t.id">
                    <div x-transition
                        class="pointer-events-auto flex items-start gap-3 rounded-xl border bg-white p-4 shadow-lg"
                        :class="{
                            'border-emerald-200': t.type === 'success',
                            'border-rose-200': t.type === 'error',
                            'border-amber-200': t.type === 'warning',
                            'border-sky-200': t.type === 'info'
                        }">
                        <span class="mt-1 h-2.5 w-2.5 shrink-0 rounded-full"
                            :class="{
                                'bg-emerald-500': t.type === 'success',
                                'bg-rose-500': t.type === 'error',
                                'bg-amber-500': t.type === 'warning',
                                'bg-sky-500': t.type === 'info'
                            }"></span>
                        <p class="flex-1 text-sm text-slate-700" x-text="t.title"></p>
                        <button type="button" @click="dismiss(t.id)" aria-label="Tutup notifikasi"
                            class="text-slate-400 transition hover:text-slate-600">{{ $svg('x') }}</button>
                    </div>
                </template>
            </div>

            <div x-show="dialog" x-cloak x-transition.opacity
                class="fixed inset-0 z-[3500] flex items-center justify-center bg-slate-900/50 p-4"
                @click.self="dialog = null" role="dialog" aria-modal="true">
                <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl" x-show="dialog" x-transition>
                    <div class="flex items-start gap-4">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full"
                            :class="info ? info.tone : ''">{{ $svg('warn', 'h-5 w-5') }}</span>
                        <div class="min-w-0">
                            <h3 class="text-base font-semibold text-slate-900" x-text="info ? info.title : ''"></h3>
                            <p class="mt-1.5 text-sm text-slate-600">
                                <span class="font-semibold text-slate-800" x-text="dialog ? dialog.name : ''"></span>
                                <span x-text="info ? info.body : ''"></span>
                            </p>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-2">
                        <button type="button" @click="dialog = null"
                            class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50">Batal</button>
                        <button type="button" @click="yes()"
                            class="rounded-lg px-4 py-2 text-sm font-semibold text-white transition"
                            :class="info ? info.btn : ''" x-text="info ? info.yes : 'Ya'"></button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
