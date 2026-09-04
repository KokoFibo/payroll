<div>
    @section('title', 'Payroll')
    {{-- <p>lock_presensi: {{ $lock_presensi }}</p> --}}
    <style>
        :root {
            --pr-radius: 14px;
            --pr-border: #e7e9ee;
            --pr-muted: #6c757d;
            --pr-bg-soft: #f7f8fa;
        }

        /* ===== General card / surface polish ===== */
        .pr-page {
            max-width: 100%;
        }

        .pr-toolbar-card,
        .card {
            border: 1px solid var(--pr-border);
            border-radius: var(--pr-radius);
            box-shadow: 0 2px 10px rgba(16, 24, 40, 0.04);
        }

        .pr-toolbar-card {
            background: #fff;
            padding: 1rem 1.1rem;
        }

        .pr-header-title {
            font-weight: 700;
            letter-spacing: .2px;
            margin: 0;
        }

        .pr-total-chip {
            border: none;
            border-radius: 999px;
            padding: .55rem 1.1rem;
            font-weight: 600;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #fff;
            box-shadow: 0 4px 14px rgba(37, 99, 235, .25);
        }

        .pr-switch-group {
            display: flex;
            flex-wrap: wrap;
            gap: .5rem 1.25rem;
        }

        .pr-switch-group {
            row-gap: .6rem;
        }

        .pr-switch-group .form-check {
            background: var(--pr-bg-soft);
            border-radius: 999px;
            padding: .4rem .9rem .4rem 2.1rem;
            margin: 0;
            display: flex;
            align-items: center;
        }

        .pr-switch-group .form-check-input {
            margin-top: 0;
            flex-shrink: 0;
        }

        .pr-switch-group .form-check-label {
            margin-left: .35rem;
        }

        .pr-switch-group .form-check-label {
            font-size: .85rem;
            font-weight: 500;
            color: #344054;
        }

        /* ===== Filter bar ===== */
        .pr-filter-bar .form-select,
        .pr-filter-bar .form-control,
        .pr-filter-bar .input-group-text,
        .pr-filter-bar .btn {
            border-radius: 10px;
        }

        .pr-filter-bar label.pr-label {
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: var(--pr-muted);
            font-weight: 600;
            margin-bottom: .25rem;
            display: block;
        }

        /* ===== Action buttons ===== */
        .pr-actions .btn {
            border-radius: 10px;
            font-weight: 500;
            white-space: nowrap;
        }

        .pr-actions {
            display: flex;
            flex-wrap: wrap;
            gap: .5rem;
        }

        /* ===== Table ===== */
        .pr-table-card .card-header {
            background: #fff;
            border-bottom: 1px solid var(--pr-border);
            border-top-left-radius: var(--pr-radius);
            border-top-right-radius: var(--pr-radius);
        }

        .table-responsive {
            border-radius: 0 0 var(--pr-radius) var(--pr-radius);
        }

        td,
        th {
            white-space: nowrap;
        }

        .table th {
            background: var(--pr-bg-soft);
            font-size: .78rem;
            text-transform: uppercase;
            letter-spacing: .03em;
            color: #475467;
            font-weight: 700;
            border-bottom: 1px solid var(--pr-border);
            cursor: pointer;
            user-select: none;
            vertical-align: middle;
        }

        .table th i {
            opacity: .45;
            margin-left: 4px;
            font-size: .7rem;
        }

        .table td {
            font-size: .85rem;
            vertical-align: middle;
            color: #101828;
        }

        .table-hover tbody tr:hover {
            background-color: #f0f6ff;
        }

        .pr-total-badge {
            font-weight: 700;
        }

        @media (min-width : 600px) {

            table th {
                z-index: 2;
            }

            td:first-child,
            th:first-child {
                position: sticky;
                left: 0;
                z-index: 1;
                background: #fff;
            }

            td:nth-child(2),
            th:nth-child(2) {
                position: sticky;
                left: 56px;
                z-index: 1;
                background: #fff;
            }

            td:nth-child(3),
            th:nth-child(3) {
                position: sticky;
                left: 110px;
                z-index: 1;
                background: #fff;
            }

            td:nth-child(4),
            th:nth-child(4) {
                position: sticky;
                left: 200px;
                z-index: 1;
                background: #fff;
            }

            th:first-child,
            th:nth-child(2) {
                z-index: 3;
                background: var(--pr-bg-soft);
            }
        }

        /* Mobile tweaks */
        @media (max-width: 575.98px) {
            .pr-toolbar-card {
                padding: .85rem;
            }

            .pr-header-title {
                font-size: 1.05rem;
            }

            .pr-total-chip {
                width: 100%;
                text-align: center;
            }

            .pr-actions .btn,
            .pr-actions a {
                flex: 1 1 auto;
            }
        }
    </style>
    <div class="pr-page p-2">

        @if (check_rebuild_done())
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <strong>Congratulation!</strong> Payroll Rebuilt Succesfully.
                <button wire:click='close_succesful_rebuilt' type="button" class="btn-close" data-bs-dismiss="alert"
                    aria-label="Close"></button>
            </div>
        @endif
        @if (check_rebuilding())
            <div class="alert alert-primary d-flex align-items-center gap-2 shadow-sm" role="alert">
                <span class="spinner-border spinner-border-sm"></span>
                <div><strong>Payroll is rebuilding ...</strong> You may safely leave this page.</div>
            </div>
        @endif
        @if ($fail = check_fail_job())
            <div class="alert alert-danger shadow-sm" role="alert">
                <strong>Errror building payroll</strong>
            </div>
        @endif
        {{-- @endif --}}

        {{-- ===== Top toolbar: title / total / locks ===== --}}
        <div class="pr-toolbar-card mb-3">
            <div class="row g-3 align-items-center">
                <div class="col-12 col-lg-4 order-lg-1">
                    @if (auth()->user()->role >= 7)
                        <div class="pr-switch-group">
                            <div class="form-check form-switch">
                                <input wire:model.live="lock_slip_gaji" class="form-check-input" type="checkbox"
                                    role="switch" id="flexSwitchCheckChecked" value=1
                                    {{ $lock_slip_gaji ? 'checked' : '' }}>
                                <label class="form-check-label" for="flexSwitchCheckChecked">
                                    @if ($lock_slip_gaji)
                                        {{ __('Slip Gaji is locked') }}
                                    @else
                                        {{ __('Slip Gaji is unlocked') }}
                                    @endif
                                </label>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="col-12 col-lg-4 order-lg-2 text-center">
                    <h4 class="pr-header-title">{{ __('Yifang Payroll') }}</h4>
                </div>

                <div class="col-12 col-lg-4 order-lg-3">
                    <div class="d-flex flex-wrap justify-content-lg-end justify-content-start">
                        @if (auth()->user()->role > 6)
                            <div class="pr-switch-group">
                                <div class="form-check form-switch">
                                    <input wire:model.live="lock_data" class="form-check-input" type="checkbox"
                                        role="switch" id="flexSwitchCheckChecked" value=1
                                        {{ $lock_data ? 'checked' : '' }}>
                                    <label class="form-check-label" for="flexSwitchCheckChecked">
                                        @if ($lock_data)
                                            {{ __('Data is locked') }}
                                        @else
                                            {{ __('Data is unlocked') }}
                                        @endif
                                    </label>
                                </div>
                                <div class="form-check form-switch">
                                    <input wire:model.live="lock_presensi" class="form-check-input" type="checkbox"
                                        role="switch" id="flexSwitchCheckChecked" value=1
                                        {{ $lock_presensi ? 'checked' : '' }}>
                                    <label class="form-check-label" for="flexSwitchCheckChecked">
                                        {{-- {{ $lock_presensi ? 'Presensi is locked' : 'Presensi is unlocked' }} --}}
                                        @if ($lock_presensi)
                                            {{ __('Presensi is locked') }}
                                        @else
                                            {{ __('Presensi is unlocked') }}
                                        @endif
                                    </label>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if (!check_rebuilding())

            {{-- ===== Filters + total + period + actions ===== --}}
            <div class="pr-toolbar-card mb-3">
                {{-- <p>directorate: {{ $selected_placement }}</p>
            <p>company: {{ $selected_company }}</p>
            <p>department: {{ $selected_departemen }}</p>
            <p>search: {{ $search }}</p>
            <p>month {{ $month }}</p>
            <p>month {{ $year }}</p>
            <p>search: {{ $search }}</p> --}}

                <div class="row g-3 align-items-end mb-2 pr-filter-bar">
                    <div class="col-6 col-md-3 col-lg-2">
                        <label class="pr-label">{{ __('Total Gaji') }}</label>
                        <button class="btn pr-total-chip w-100">Rp. {{ number_format($total) }}</button>
                    </div>
                    <div class="col-6 col-md-2 col-lg-2">
                        <label class="pr-label">{{ __('Year') }}</label>
                        <select class="form-select" wire:model.live="year">
                            @foreach ($select_year as $sy)
                                <option value="{{ $sy }}">{{ $sy }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-3 col-lg-2">
                        <label class="pr-label">{{ __('Month') }}</label>
                        <select class="form-select" wire:model.live="month">
                            @foreach ($select_month as $sm)
                                <option value="{{ $sm }}">{{ monthName($sm) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-4 col-lg-6">
                        <button wire:loading wire:target='buat_payroll' class="btn btn-primary w-100" type="button"
                            disabled>
                            <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                            <span
                                role="status">{{ __('Building Data... mohon tunggu sebentar, jangan tekan apapun.') }}</span>
                        </button>
                        <button wire:loading wire:target='newRebuild' class="btn btn-primary w-100" type="button"
                            disabled>
                            <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                            <span role="status">{{ __('Building Payroll ... please wait.') }}</span>
                        </button>
                        <button wire:loading wire:target='export' class="btn btn-primary w-100" type="button" disabled>
                            <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                            <span role="status">{{ __('Building Excel ... PLease wait') }}</span>
                        </button>
                        <button wire:loading wire:target='bankexcel' class="btn btn-primary w-100" type="button"
                            disabled>
                            <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                            <span role="status">{{ __('Building Excel for bank ... PLease wait') }}</span>
                        </button>
                    </div>
                </div>

                <hr class="my-2">

                <div class="pr-actions" wire:loading.class='invisible'>
                    @if (auth()->user()->role == 8)
                        <a href="/cekabsensitanpaid"><button type="button" class="btn btn-outline-primary btn-sm">
                                <i class="fa-solid fa-magnifying-glass me-1"></i>{{ __('Cek Absensi Tanpa ID') }}
                            </button></a>

                        <button wire:click="clear_lock()" class="btn btn-outline-secondary btn-sm">
                            <i class="fa-solid fa-lock-open me-1"></i>{{ __('Clear Lock') }}
                        </button>
                        <button wire:click="buat_payroll('noQueue')" {{-- {{ is_40_days($month, $year) == true ? 'disabled' : '' }} --}}
                            class="btn btn-outline-secondary btn-sm">
                            {{ __('Rebuild wihout queue') }}
                        </button>
                        <button wire:click="rebuildOptimized" {{-- {{ is_40_days($month, $year) == true ? 'disabled' : '' }} --}}
                            class="btn btn-outline-secondary btn-sm">
                            {{ __('Quick Rebuild optimized') }}
                        </button>
                    @endif
                    <a href="/ter"><button type="button" class="btn btn-warning btn-sm">
                            <i class="fa-solid fa-table me-1"></i>{{ __('Table Ter PPh21') }}
                        </button></a>
                    <button class="btn btn-success btn-sm" wire:click="bankexcel">
                        <i class="fa-solid fa-building-columns me-1"></i>{{ __('Report for bank') }}
                    </button>
                    {{-- <a href="/headcount"><button
                        class="btn btn-warning nightowl-daylight">{{ __('Headcount') }}</button></a> --}}
                    <button wire:click='excelDetailReport' class="btn btn-warning btn-sm">
                        <i class="fa-solid fa-file-lines me-1"></i>{{ __('Detail Report') }}
                    </button>

                    <button wire:click="export" class="btn btn-success btn-sm">
                        <i class="fa-solid fa-file-excel me-1"></i>Excel
                    </button>
                    @if (auth()->user()->role == 8)
                        <button wire:click="buat_payroll('queue')"
                            {{ is_40_days($month, $year) == true || isDataUtamaLengkap() > 0 ? 'disabled' : '' }}
                            class="btn btn-primary btn-sm">{{ __('old Rebuild') }}</button>
                    @endif
                    {{-- <button wire:click="newRebuild" {{ is_40_days($month, $year) == true ? 'disabled' : '' }}
                    class="btn btn-primary nightowl-daylight">{{ __('Rebuild') }}</button> --}}
                    <button wire:click="rebuildOptimized" {{ is_40_days($month, $year) == true ? 'disabled' : '' }}
                        class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-rotate me-1"></i>{{ __('Rebuild') }}
                    </button>
                </div>
            </div>

            @if (isDataUtamaLengkap() > 0)
                <div
                    class="alert alert-danger d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2 shadow-sm">
                    <h4 class='text-danger text-center text-bold mr-3 m-0 fs-6'>
                        Ada beberapa data utama karyawan yang belum lengkap!
                    </h4>
                    <a href="/datatidaklengkap"><button class="btn btn-danger btn-sm">Silakan cek disini</button></a>
                </div>
            @endif
        @endif

        <div class="card pr-table-card">
            <div class="card-header">
                <div
                    class="d-flex flex-xl-row flex-column justify-content-between align-items-stretch align-items-xl-center gap-2 gap-xl-3 pr-filter-bar">
                    <div class="col-xl-4">
                        <div class="input-group">
                            <button class="btn btn-primary" type="button"><i
                                    class="fa-solid fa-magnifying-glass"></i></button>
                            <input type="search" wire:model.live="search" class="form-control"
                                placeholder="{{ __('Search') }} ...">
                        </div>
                    </div>
                    {{-- placement --}}
                    <div class="flex-fill">
                        <select wire:model.live="selected_placement" class="form-select"
                            aria-label="Default select example">
                            <option value="0" selected>{{ __('All Directorates') }}</option>
                            @foreach ($placements as $p)
                                <option value="{{ $p->id }}">{{ $p->placement_name }}
                            @endforeach
                        </select>
                    </div>
                    {{-- Company --}}
                    <div class="flex-fill">
                        <select wire:model.live="selected_company" class="form-select"
                            aria-label="Default select example">
                            <option value="0" selected>{{ __('All Companies') }}</option>
                            @foreach ($companies as $c)
                                <option value="{{ $c->id }}">{{ $c->company_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Departemen --}}
                    <div class="flex-fill">
                        <select wire:model.live="selected_departemen" class="form-select"
                            aria-label="Default select example">
                            <option value="0" selected>{{ __('All Department') }}</option>
                            {{-- @foreach ($departments as $department)
                            <option value="{{ nama_department($department) }}">{{ nama_department($department) }}
                            </option>
                        @endforeach --}}
                            @foreach ($departments as $d)
                                <option value="{{ $d->id }}">{{ $d->nama_department }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex-fill">
                        <select class="form-select" wire:model.live="perpage">
                            {{-- <option selected>Open this select menu</option> --}}
                            <option value="10">10 {{ __('rows perpage') }}</option>
                            <option value="15">15 {{ __('rows perpage') }}</option>
                            <option value="20">20 {{ __('rows perpage') }}</option>
                            <option value="25">25 {{ __('rows perpage') }}</option>
                        </select>
                    </div>
                    <div class="flex-fill">
                        <select class="form-select" wire:model.live="status">
                            <option value="0">{{ __('Semua') }}</option>
                            <option value="1">{{ __('Status Aktif') }}</option>
                            <option value="2">{{ __('Status Non Aktif') }}</option>
                        </select>
                    </div>

                    {{-- </div> --}}
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th></th>
                                <th wire:click="sortColumnName('id_karyawan')">{{ __('ID') }} <i
                                        class="fa-solid fa-sort"></i></th>
                                <th wire:click="sortColumnName('id_karyawan')">
                                    {{ __('Date') }} <i class="fa-solid fa-sort"></i></th>
                                <th wire:click="sortColumnName('nama')">{{ __('Nama') }} <i
                                        class="fa-solid fa-sort"></i></th>
                                {{-- <th wire:click="sortColumnName('total_noscan')">{{ __('total_noscan') }} <i
                                    class="fa-solid fa-sort"></i></th>
                            <th wire:click="sortColumnName('denda_lupa_absen')">{{ __('denda_lupa_absen') }} <i
                                    class="fa-solid fa-sort"></i></th>
                            <th wire:click="sortColumnName('denda_resigned')">{{ __('denda_resigned') }} <i
                                    class="fa-solid fa-sort"></i></th> --}}
                                <th wire:click="sortColumnName('date')">{{ __('Date') }} <i
                                        class="fa-solid fa-sort"></i></th>
                                <th wire:click="sortColumnName('status_karyawan')">{{ __('Status') }} <i
                                        class="fa-solid fa-sort"></i></th>
                                <th wire:click="sortColumnName('jabatan')">{{ __('Jabatan') }} <i
                                        class="fa-solid fa-sort"></i></th>
                                <th wire:click="sortColumnName('placement')">{{ __('Directorate') }} <i
                                        class="fa-solid fa-sort"></i>
                                </th>
                                <th wire:click="sortColumnName('company')">{{ __('Company') }} <i
                                        class="fa-solid fa-sort"></i></th>
                                <th wire:click="sortColumnName('departemen')">{{ __('Department') }} <i
                                        class="fa-solid fa-sort"></i>
                                </th>
                                <th wire:click="sortColumnName('metode_penggajian')">{{ __('Metode Penggajian') }}
                                    <i class="fa-solid fa-sort"></i>
                                </th>
                                <th wire:click="sortColumnName('hari_kerja')">{{ __('Hari Kerja') }} <i
                                        class="fa-solid fa-sort"></i>
                                </th>
                                <th wire:click="sortColumnName('jam_kerja')">{{ __('Jam Kerja Bersih') }} <i
                                        class="fa-solid fa-sort"></i>
                                </th>
                                <th wire:click="sortColumnName('jam_lembur')">{{ __('Jam Lembur') }} <i
                                        class="fa-solid fa-sort"></i>
                                </th>
                                <th wire:click="sortColumnName('jam_kerja')">{{ __('Jam Kerja Libur') }} <i
                                        class="fa-solid fa-sort"></i>
                                </th>
                                <th wire:click="sortColumnName('jam_lembur')">{{ __('Jam Lembur Libur') }} <i
                                        class="fa-solid fa-sort"></i>
                                </th>
                                <th wire:click="sortColumnName('jumlah_jam_terlambat')">{{ __('Terlambat') }} <i
                                        class="fa-solid fa-sort"></i>
                                </th>
                                <th wire:click="sortColumnName('gaji_pokok')">{{ __('Gaji Pokok') }} <i
                                        class="fa-solid fa-sort"></i>
                                </th>
                                <th wire:click="sortColumnName('gaji_lembur')">{{ __('Gaji Lembur') }} <i
                                        class="fa-solid fa-sort"></i>
                                </th>
                                <th wire:click="sortColumnName('gaji_bpjs')">{{ __('Gaji BPJS') }} <i
                                        class="fa-solid fa-sort"></i>
                                </th>
                                <th wire:click="sortColumnName('subtotal')">{{ __('Sub Gaji') }} <i
                                        class="fa-solid fa-sort"></i>
                                </th>
                                <th wire:click="sortColumnName('gaji_libur')">{{ __('Gaji Libur') }} <i
                                        class="fa-solid fa-sort"></i>
                                </th>

                                {{-- <th wire:click="sortColumnName('libur_nasional')">{{ __('Libur Nasional') }} <i
                                    class="fa-solid fa-sort"></i> --}}
                                </th>
                                <th wire:click="sortColumnName('tambahan_shift_malam')">
                                    {{ __('Tambahan Shift Malam') }} <i class="fa-solid fa-sort"></i>
                                </th>
                                <th wire:click="sortColumnName('bonus1x')">{{ __('Bonus/U.Makan') }} <i
                                        class="fa-solid fa-sort"></i>
                                </th>
                                <th wire:click="sortColumnName('thr')">{{ __('THR') }} <i
                                        class="fa-solid fa-sort"></i>
                                </th>
                                {{-- <th wire:click="sortColumnName('bonus1x')">{{ __('Bonus Karyawan') }} <i
                                    class="fa-solid fa-sort"></i>
                            </th> --}}
                                <th wire:click="sortColumnName('potongan1x')">{{ __('Potongan 1X') }}<i
                                        class="fa-solid fa-sort"></i>
                                </th>

                                <th wire:click="sortColumnName('potongan1x')">{{ __('Potongan Karyawan') }} <i
                                        class="fa-solid fa-sort"></i>
                                </th>


                                <th wire:click="sortColumnName('denda_lupa_absen')">{{ __('Lupa Absen') }} <i
                                        class="fa-solid fa-sort"></i>
                                </th>
                                <th wire:click="sortColumnName('denda_resigned')">{{ __('Denda Resigned') }} <i
                                        class="fa-solid fa-sort"></i>
                                </th>

                                <th wire:click="sortColumnName('pajak')">{{ __('Pajak') }} <i
                                        class="fa-solid fa-sort"></i></th>
                                <th wire:click="sortColumnName('jht')">JHT <i class="fa-solid fa-sort"></i></th>
                                <th wire:click="sortColumnName('jp')">JP <i class="fa-solid fa-sort"></i></th>
                                <th wire:click="sortColumnName('jkk')">JKK <i class="fa-solid fa-sort"></i></th>
                                <th wire:click="sortColumnName('jkm')">JKM <i class="fa-solid fa-sort"></i></th>
                                <th wire:click="sortColumnName('kesehatan')">Kesehatan <i
                                        class="fa-solid fa-sort"></i>
                                </th>
                                <th wire:click="sortColumnName('tanggungan')">Tanggungan <i
                                        class="fa-solid fa-sort"></i>
                                </th>
                                <th wire:click="sortColumnName('ptkp')">{{ __('PTKP') }} <i
                                        class="fa-solid fa-sort"></i></th>
                                <th>{{ __('TER') }}</th>

                                <th wire:click="sortColumnName('pph21')">{{ __('Total BPJS') }} <i
                                        class="fa-solid fa-sort"></i></th>
                                <th wire:click="sortColumnName('pph21')">{{ __('PPh21') }} <i
                                        class="fa-solid fa-sort"></i></th>
                                <th wire:click="sortColumnName('total')">{{ __('Total') }} <i
                                        class="fa-solid fa-sort"></i></th>

                            </tr>
                        </thead>
                        <tbody>
                            @if ($payroll->isNotEmpty())

                                @foreach ($payroll as $p)
                                    @if (check_bulan($p->date, $month, $year))
                                        <tr>
                                            <td>
                                                <button type="button" class="btn btn-success btn-sm"
                                                    wire:click="showDetail({{ $p->id_karyawan }})"
                                                    data-bs-toggle="modal" data-bs-target="#payroll"><i
                                                        class="fa-solid fa-magnifying-glass"></i></button>

                                            </td>


                                            <td>{{ $p->id_karyawan }}</td>
                                            {{-- <td>{{ format_tgl($p->date) }}</td> --}}
                                            <td>{{ month_year($p->date) }}</td>
                                            <td class="fw-semibold">{{ $p->nama }}</td>
                                            {{-- <td>{{ $p->total_noscan }}</td>
                                        <td>{{ $p->denda_lupa_absen }}</td>
                                        <td>{{ $p->denda_resigned }}</td> --}}
                                            <td>{{ $p->date }}</td>
                                            <td>
                                                <span
                                                    class="badge rounded-pill {{ strtolower($p->status_karyawan) == 'aktif' ? 'text-bg-success' : 'text-bg-secondary' }}">
                                                    {{ $p->status_karyawan }}
                                                </span>
                                            </td>
                                            <td>{{ nama_jabatan($p->jabatan_id) }}</td>
                                            <td>{{ nama_placement($p->placement_id) }}</td>
                                            <td>{{ nama_company($p->company_id) }}</td>
                                            <td>{{ nama_department($p->department_id) }}</td>
                                            <td>{{ $p->metode_penggajian }}</td>
                                            <td class="text-end">{{ $p->hari_kerja }}</td>
                                            <td class="text-end">{{ number_format($p->jam_kerja, 1) }}</td>
                                            <td class="text-end">{{ $p->jam_lembur }}</td>
                                            <td class="text-end">{{ number_format($p->jam_kerja_libur, 1) }}</td>
                                            <td class="text-end">{{ $p->jam_lembur_libur }}</td>
                                            <td class="text-end">{{ $p->jumlah_jam_terlambat }}</td>
                                            <td class="text-end">{{ number_format($p->gaji_pokok) }}</td>
                                            <td class="text-end">
                                                {{ $p->gaji_lembur ? number_format($p->gaji_lembur) : '' }}
                                            </td>
                                            <td class="text-end">
                                                {{ $p->gaji_bpjs ? number_format($p->gaji_bpjs) : '' }}
                                            </td>
                                            <td class="text-end">{{ number_format($p->subtotal) }}</td>
                                            <td class="text-end">{{ number_format($p->gaji_libur) }}</td>
                                            {{-- <td class="text-end">
                                            {{ $p->libur_nasional ? number_format($p->libur_nasional) : '' }}
                                        </td> --}}
                                            <td class="text-end">
                                                {{ $p->tambahan_shift_malam ? number_format($p->tambahan_shift_malam) : '' }}
                                            </td>
                                            <td class="text-end">
                                                {{ $p->bonus1x ? number_format($p->bonus1x) : '' }}
                                            </td>
                                            @php
                                                $total_potongan_dari_karyawan = 0;
                                                $total_bonus_dari_karyawan = 0;
                                                $total_potongan_dari_karyawan = $p->iuran_air + $p->iuran_locker;
                                                $total_bonus_dari_karyawan =
                                                    $p->thr +
                                                    $p->tunjangan_jabatan +
                                                    $p->tunjangan_bahasa +
                                                    $p->tunjangan_skill +
                                                    $p->tunjangan_lembur_sabtu +
                                                    $p->tunjangan_lama_kerja;

                                            @endphp
                                            <td class="text-end">
                                                {{ $p->thr ? number_format($p->thr) : '' }}
                                            </td>
                                            {{-- <td class="text-end">
                                            {{ number_format($total_bonus_dari_karyawan) }}
                                        </td> --}}
                                            <td class="text-end">
                                                {{ $p->potongan1x ? number_format($p->potongan1x) : '' }}
                                            </td>

                                            <td class="text-end">
                                                {{ $total_potongan_dari_karyawan ? number_format($total_potongan_dari_karyawan) : '' }}
                                            </td>
                                            <td class="text-end">
                                                {{ $p->denda_lupa_absen ? number_format($p->denda_lupa_absen) : '' }}
                                            </td>
                                            <td class="text-end">
                                                {{ $p->denda_resigned ? number_format($p->denda_resigned) : '' }}

                                            </td>

                                            <td class="text-end">{{ $p->pajak ? number_format($p->pajak) : '' }}
                                            </td>
                                            <td class="text-end">{{ $p->jht ? number_format($p->jht) : '' }}</td>
                                            <td class="text-end">{{ $p->jp ? number_format($p->jp) : '' }}</td>
                                            <td class="text-end">{{ $p->jkk ? 'Yes' : '' }}</td>
                                            <td class="text-end">{{ $p->jkm ? 'Yes' : '' }}</td>
                                            <td class="text-end">
                                                {{ $p->kesehatan ? number_format($p->kesehatan) : '' }}
                                            </td>
                                            <td class="text-end">
                                                {{ $p->tanggungan ? number_format($p->tanggungan) : '' }}
                                            </td>

                                            <td class="text-end">{{ $p->ptkp }}</td>

                                            @if ($p->ptkp != '')
                                                <td class="text-end">{{ get_ter($p->ptkp) }}</td>
                                            @else
                                                <td class="text-end"></td>
                                            @endif
                                            <td class="text-end">{{ number_format($p->total_bpjs) }}</td>
                                            <td class="text-end">{{ number_format($p->pph21) }}</td>
                                            <td class="text-end pr-total-badge">{{ number_format($p->total) }}</td>

                                        </tr>
                                    @endif
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="100%" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-inbox fs-3 d-block mb-2"></i>
                                        {{ __('No Data Found') }}
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="px-3 py-2">
                    {{ $payroll->onEachSide(0)->links() }}
                </div>
            </div>
            <div
                class="card-footer bg-white border-top d-flex flex-column flex-sm-row justify-content-between gap-1 py-2">
                <p class="px-1 mb-0 small text-muted">{{ __('Total : ') }} {{ getTotalWorkingDays($year, $month) }}
                    Days.
                    ( {{ getTotalWorkingDays($year, $month) - jumlah_libur_nasional($month, $year) }}
                    {{ __('working days with') }}
                    {{ jumlah_libur_nasional($month, $year) }} {{ __('Holidays') }} )
                </p>

                <p class="px-1 mb-0 small text-success">{{ __('Last update') }}: {{ $last_build }} </p>
            </div>
        </div>
        @if ($data_payroll != null && $data_karyawan != null)
            @include('modals.payroll-modal')
        @endif
    </div>
</div>
