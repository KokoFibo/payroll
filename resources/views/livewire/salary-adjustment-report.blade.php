<div class="sar">
    <style>
        .sar {
            --sar-primary: #4f46e5;
            --sar-primary-dark: #4338ca;
            --sar-success: #16a34a;
            --sar-success-bg: #dcfce7;
            --sar-danger: #dc2626;
            --sar-danger-bg: #fee2e2;
            --sar-muted: #6b7280;
            --sar-border: #e5e7eb;
            --sar-bg-soft: #f8fafc;
        }

        .sar-card {
            position: relative;
            background: #fff;
            border: 1px solid var(--sar-border);
            border-radius: 1rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, .04), 0 1px 10px rgba(0, 0, 0, .03);
            overflow: hidden;
        }

        .sar-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--sar-border);
        }

        .sar-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #111827;
            margin: 0;
        }

        .sar-subtitle {
            font-size: .8125rem;
            color: var(--sar-muted);
            margin: .125rem 0 0;
        }

        .sar-btn-export {
            background: var(--sar-primary);
            border: none;
            color: #fff;
            font-weight: 600;
            font-size: .875rem;
            padding: .6rem 1.15rem;
            border-radius: .65rem;
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            text-decoration: none;
            white-space: nowrap;
            transition: background .15s ease;
        }

        .sar-btn-export:hover {
            background: var(--sar-primary-dark);
            color: #fff;
        }

        .sar-section-label {
            font-size: .6875rem;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
            color: var(--sar-muted);
            margin: 0 0 .5rem;
        }

        .sar-filter-card {
            background: var(--sar-bg-soft);
            border: 1px solid var(--sar-border);
            border-radius: .85rem;
            padding: 1rem;
        }

        .sar-select {
            border-radius: .6rem;
            border: 1px solid var(--sar-border);
            font-size: .875rem;
        }

        .sar-select:disabled {
            background-color: #f3f4f6;
            opacity: .7;
        }

        .sar-table thead th {
            font-size: .6875rem;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: var(--sar-muted);
            font-weight: 700;
            background: var(--sar-bg-soft);
            border-bottom: 2px solid var(--sar-border);
            white-space: nowrap;
        }

        .sar-table tbody td {
            font-size: .875rem;
            vertical-align: middle;
            border-color: var(--sar-border);
        }

        .sar-table tbody tr:hover {
            background: var(--sar-bg-soft);
        }

        .sar-amount-badge {
            display: inline-block;
            font-weight: 700;
            color: var(--sar-success);
            background: var(--sar-success-bg);
            border-radius: .5rem;
            padding: .2rem .55rem;
            font-size: .8125rem;
        }

        .sar-diff-up {
            color: var(--sar-success);
            background: var(--sar-success-bg);
            border-radius: .5rem;
            padding: .15rem .5rem;
            font-weight: 700;
            font-size: .8125rem;
            display: inline-block;
        }

        .sar-diff-down {
            color: var(--sar-danger);
            background: var(--sar-danger-bg);
            border-radius: .5rem;
            padding: .15rem .5rem;
            font-weight: 700;
            font-size: .8125rem;
            display: inline-block;
        }

        .sar-diff-flat {
            color: var(--sar-muted);
            font-size: .8125rem;
        }

        .sar-empty {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--sar-muted);
        }

        .sar-empty svg {
            opacity: .35;
            margin-bottom: .5rem;
        }

        .sar-empty-title {
            font-weight: 600;
            color: #374151;
            font-size: .9375rem;
            margin-bottom: .125rem;
        }

        .sar-empty-sub {
            font-size: .8125rem;
        }

        .sar-emp-card {
            border: 1px solid var(--sar-border);
            border-radius: .85rem;
            padding: .9rem 1rem;
            margin-bottom: .65rem;
            background: #fff;
        }

        .sar-emp-name {
            font-weight: 700;
            font-size: .9375rem;
            color: #111827;
        }

        .sar-emp-meta {
            font-size: .75rem;
            color: var(--sar-muted);
        }

        .sar-stat-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: .45rem 0;
            border-top: 1px dashed var(--sar-border);
            font-size: .8125rem;
        }

        .sar-stat-label {
            color: var(--sar-muted);
            font-weight: 600;
        }

        .sar-stat-values {
            color: #374151;
        }

        /* ===== Loading state ===== */
        .sar-progress-track {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: transparent;
            overflow: hidden;
            z-index: 20;
        }

        .sar-progress-bar {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 40%;
            background: linear-gradient(90deg, var(--sar-primary), #818cf8);
            border-radius: 999px;
            animation: sar-progress-slide 1.1s ease-in-out infinite;
        }

        @keyframes sar-progress-slide {
            0% { left: -40%; }
            100% { left: 100%; }
        }

        .sar-section-loading {
            position: relative;
            min-height: 6rem;
        }

        .sar-section-loading-overlay {
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, .82);
            backdrop-filter: blur(1px);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: .6rem;
            z-index: 10;
            border-radius: inherit;
        }

        .sar-spinner {
            width: 1.9rem;
            height: 1.9rem;
            border-radius: 50%;
            border: 3px solid var(--sar-border);
            border-top-color: var(--sar-primary);
            animation: sar-spin .7s linear infinite;
        }

        @keyframes sar-spin {
            to { transform: rotate(360deg); }
        }

        .sar-loading-text {
            font-size: .8125rem;
            font-weight: 600;
            color: var(--sar-primary);
            letter-spacing: .02em;
        }

        .sar-inline-spinner {
            display: inline-block;
            width: .9rem;
            height: .9rem;
            border-radius: 50%;
            border: 2px solid rgba(79, 70, 229, .25);
            border-top-color: var(--sar-primary);
            animation: sar-spin .6s linear infinite;
            vertical-align: middle;
            margin-left: .4rem;
        }

        @media (max-width: 767.98px) {
            .sar-header {
                padding: 1rem;
                flex-direction: column;
                align-items: stretch !important;
            }

            .sar-btn-export {
                justify-content: center;
            }
        }
    </style>

    {{-- ================= HEADER + FILTER PERIODE ================= --}}
    <div class="sar-card mb-3">
        {{-- progress bar tipis di atas, muncul tiap ada request Livewire --}}
        <div wire:loading.delay class="sar-progress-track">
            <div class="sar-progress-bar"></div>
        </div>

        <div class="sar-header d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <p class="sar-title">Laporan Salary Adjustment</p>
                <p class="sar-subtitle">
                    Monitoring kenaikan gaji pokok, lembur &amp; bonus karyawan outsource (OS)
                </p>
            </div>

            <a href="{{ route('salary-adjustment.export', ['month' => $month, 'year' => $year]) }}"
               class="sar-btn-export">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 3v12" />
                    <path d="M7 10l5 5 5-5" />
                    <path d="M4 20h16" />
                </svg>
                Generate Excel
            </a>
        </div>

        <div class="p-3 p-md-4">
            <div class="sar-filter-card">
                <p class="sar-section-label">Periode</p>
                <div class="row g-3">
                    <div class="col-6 col-md-3">
                        <label class="form-label small fw-semibold mb-1">Bulan</label>
                        <select wire:model.live="month"
                                wire:loading.attr="disabled"
                                wire:target="month,year"
                                class="form-select sar-select">
                            @foreach (range(1, 12) as $m)
                                <option value="{{ $m }}">
                                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label small fw-semibold mb-1">
                            Tahun
                            <span wire:loading wire:target="month,year" class="sar-inline-spinner"></span>
                        </label>
                        <select wire:model.live="year"
                                wire:loading.attr="disabled"
                                wire:target="month,year"
                                class="form-select sar-select">
                            @foreach (range(now()->year - 2, now()->year) as $y)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= SUMMARY ================= --}}
    <div class="sar-card mb-3">
        <div class="p-3 p-md-4 pb-0">
            <p class="sar-section-label mb-3">Summary</p>
        </div>

        <div class="sar-section-loading" wire:target="month,year">
            <div wire:loading.delay wire:target="month,year" class="sar-section-loading-overlay">
                <div class="sar-spinner"></div>
                <span class="sar-loading-text">Memuat data periode terpilih…</span>
            </div>

            {{-- Desktop / tablet: tabel --}}
            <div class="table-responsive d-none d-md-block">
                <table class="table sar-table mb-0">
                    <thead>
                        <tr>
                            <th>Directorate</th>
                            <th>Departemen</th>
                            <th>Adjustment Type</th>
                            <th class="text-end">Jumlah Karyawan</th>
                            <th class="text-end">Total Adjustment</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rows as $row)
                            <tr>
                                <td>{{ $row['directorate'] }}</td>
                                <td>{{ $row['departemen'] }}</td>
                                <td>{{ $row['adjustment_type'] }}</td>
                                <td class="text-end">{{ $row['jumlah_karyawan'] }}</td>
                                <td class="text-end">
                                    <span class="sar-amount-badge">
                                        Rp {{ number_format($row['total_adjustment'], 0, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="border-0">
                                    <div class="sar-empty">
                                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none"
                                             stroke="currentColor" stroke-width="1.5">
                                            <rect x="4" y="3" width="16" height="18" rx="2" />
                                            <path d="M8 8h8M8 12h8M8 16h5" />
                                        </svg>
                                        <div class="sar-empty-title">Tidak ada data kenaikan</div>
                                        <div class="sar-empty-sub">Coba pilih periode bulan/tahun yang lain.</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile: kartu --}}
            <div class="d-md-none p-3 pt-0">
                @forelse ($rows as $row)
                    <div class="sar-emp-card">
                        <div class="d-flex justify-content-between align-items-start gap-2">
                            <div>
                                <div class="sar-emp-name">{{ $row['adjustment_type'] }}</div>
                                <div class="sar-emp-meta">{{ $row['directorate'] }} &middot; {{ $row['departemen'] }}</div>
                            </div>
                            <div class="text-end">
                                <span class="sar-amount-badge">
                                    Rp {{ number_format($row['total_adjustment'], 0, ',', '.') }}
                                </span>
                                <div class="sar-emp-meta mt-1">{{ $row['jumlah_karyawan'] }} karyawan</div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="sar-empty">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.5">
                            <rect x="4" y="3" width="16" height="18" rx="2" />
                            <path d="M8 8h8M8 12h8M8 16h5" />
                        </svg>
                        <div class="sar-empty-title">Tidak ada data kenaikan</div>
                        <div class="sar-empty-sub">Coba pilih periode bulan/tahun yang lain.</div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ================= DETAIL PER KARYAWAN ================= --}}
    <div class="sar-card">
        <div class="p-3 p-md-4 pb-0">
            <p class="sar-section-label mb-3">Detail per Karyawan</p>

            <div class="sar-filter-card mb-3">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label small fw-semibold mb-1">Directorate</label>
                        <select wire:model.live="filterDirectorate"
                                wire:loading.attr="disabled"
                                wire:target="month,year,filterDirectorate,filterDepartment"
                                class="form-select sar-select">
                            <option value="">Semua Directorate</option>
                            @foreach ($directorateOptions as $d)
                                <option value="{{ $d }}">{{ $d }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label small fw-semibold mb-1">
                            Departemen
                            <span wire:loading wire:target="filterDirectorate,filterDepartment" class="sar-inline-spinner"></span>
                        </label>
                        <select wire:model.live="filterDepartment"
                                wire:loading.attr="disabled"
                                wire:target="month,year,filterDirectorate,filterDepartment"
                                class="form-select sar-select">
                            <option value="">Semua Departemen</option>
                            @foreach ($departmentOptions as $d)
                                <option value="{{ $d }}">{{ $d }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="sar-section-loading" wire:target="month,year,filterDirectorate,filterDepartment">
            <div wire:loading.delay wire:target="month,year,filterDirectorate,filterDepartment"
                 class="sar-section-loading-overlay">
                <div class="sar-spinner"></div>
                <span class="sar-loading-text">Memuat detail karyawan…</span>
            </div>

            {{-- Desktop / tablet: tabel --}}
            <div class="table-responsive d-none d-md-block">
                <table class="table sar-table mb-0">
                    <thead>
                        <tr>
                            <th rowspan="2" class="align-middle">Nama</th>
                            <th rowspan="2" class="align-middle">Directorate</th>
                            <th rowspan="2" class="align-middle">Departemen</th>
                            <th colspan="3" class="text-center">Gaji Pokok</th>
                            <th colspan="3" class="text-center">Gaji Lembur</th>
                            <th rowspan="2" class="align-middle text-end">Bonus</th>
                        </tr>
                        <tr>
                            <th class="text-end">Lama</th>
                            <th class="text-end">Baru</th>
                            <th class="text-end">Selisih</th>
                            <th class="text-end">Lama</th>
                            <th class="text-end">Baru</th>
                            <th class="text-end">Selisih</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($details as $d)
                            <tr wire:key="detail-{{ $d['id_karyawan'] }}">
                                <td class="fw-semibold">{{ $d['nama'] }}</td>
                                <td>{{ $d['directorate'] }}</td>
                                <td>{{ $d['departemen'] }}</td>

                                <td class="text-end">{{ number_format($d['gaji_pokok_lama'], 0, ',', '.') }}</td>
                                <td class="text-end">{{ number_format($d['gaji_pokok_baru'], 0, ',', '.') }}</td>
                                <td class="text-end">
                                    @if ($d['gaji_pokok_diff'] > 0)
                                        <span class="sar-diff-up">+{{ number_format($d['gaji_pokok_diff'], 0, ',', '.') }}</span>
                                    @elseif ($d['gaji_pokok_diff'] < 0)
                                        <span class="sar-diff-down">{{ number_format($d['gaji_pokok_diff'], 0, ',', '.') }}</span>
                                    @else
                                        <span class="sar-diff-flat">0</span>
                                    @endif
                                </td>

                                <td class="text-end">{{ number_format($d['gaji_lembur_lama'], 0, ',', '.') }}</td>
                                <td class="text-end">{{ number_format($d['gaji_lembur_baru'], 0, ',', '.') }}</td>
                                <td class="text-end">
                                    @if ($d['gaji_lembur_diff'] > 0)
                                        <span class="sar-diff-up">+{{ number_format($d['gaji_lembur_diff'], 0, ',', '.') }}</span>
                                    @elseif ($d['gaji_lembur_diff'] < 0)
                                        <span class="sar-diff-down">{{ number_format($d['gaji_lembur_diff'], 0, ',', '.') }}</span>
                                    @else
                                        <span class="sar-diff-flat">0</span>
                                    @endif
                                </td>

                                <td class="text-end align-middle">
                                    @if ($d['bonus'] > 0)
                                        <span class="sar-diff-up">{{ number_format($d['bonus'], 0, ',', '.') }}</span>
                                    @else
                                        <span class="sar-diff-flat">0</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="border-0">
                                    <div class="sar-empty">
                                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none"
                                             stroke="currentColor" stroke-width="1.5">
                                            <circle cx="11" cy="11" r="7" />
                                            <path d="M21 21l-4-4" />
                                        </svg>
                                        <div class="sar-empty-title">Tidak ada data untuk filter ini</div>
                                        <div class="sar-empty-sub">Coba ubah Directorate atau Departemen.</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile: kartu per karyawan --}}
            <div class="d-md-none p-3 pt-0">
                @forelse ($details as $d)
                    <div class="sar-emp-card" wire:key="detail-mobile-{{ $d['id_karyawan'] }}">
                        <div class="sar-emp-name">{{ $d['nama'] }}</div>
                        <div class="sar-emp-meta mb-1">{{ $d['directorate'] }} &middot; {{ $d['departemen'] }}</div>

                        <div class="sar-stat-row">
                            <span class="sar-stat-label">Gaji Pokok</span>
                            <span class="sar-stat-values">
                                {{ number_format($d['gaji_pokok_lama'], 0, ',', '.') }}
                                &rarr;
                                {{ number_format($d['gaji_pokok_baru'], 0, ',', '.') }}
                            </span>
                            @if ($d['gaji_pokok_diff'] > 0)
                                <span class="sar-diff-up">+{{ number_format($d['gaji_pokok_diff'], 0, ',', '.') }}</span>
                            @elseif ($d['gaji_pokok_diff'] < 0)
                                <span class="sar-diff-down">{{ number_format($d['gaji_pokok_diff'], 0, ',', '.') }}</span>
                            @else
                                <span class="sar-diff-flat">0</span>
                            @endif
                        </div>

                        <div class="sar-stat-row">
                            <span class="sar-stat-label">Gaji Lembur</span>
                            <span class="sar-stat-values">
                                {{ number_format($d['gaji_lembur_lama'], 0, ',', '.') }}
                                &rarr;
                                {{ number_format($d['gaji_lembur_baru'], 0, ',', '.') }}
                            </span>
                            @if ($d['gaji_lembur_diff'] > 0)
                                <span class="sar-diff-up">+{{ number_format($d['gaji_lembur_diff'], 0, ',', '.') }}</span>
                            @elseif ($d['gaji_lembur_diff'] < 0)
                                <span class="sar-diff-down">{{ number_format($d['gaji_lembur_diff'], 0, ',', '.') }}</span>
                            @else
                                <span class="sar-diff-flat">0</span>
                            @endif
                        </div>

                        <div class="sar-stat-row">
                            <span class="sar-stat-label">Bonus</span>
                            @if ($d['bonus'] > 0)
                                <span class="sar-diff-up">{{ number_format($d['bonus'], 0, ',', '.') }}</span>
                            @else
                                <span class="sar-diff-flat">0</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="sar-empty">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.5">
                            <circle cx="11" cy="11" r="7" />
                            <path d="M21 21l-4-4" />
                        </svg>
                        <div class="sar-empty-title">Tidak ada data untuk filter ini</div>
                        <div class="sar-empty-sub">Coba ubah Directorate atau Departemen.</div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
