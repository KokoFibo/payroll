@extends('layouts.app4')

@section('title', 'Dashboard')

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.0.0-rc.1/chartjs-plugin-datalabels.min.js"
        integrity="sha512-+UYTD5L/bU1sgAfWA0ELK5RlQ811q8wZIocqI7+K0Lhh8yVdIoAMEs96wJAIbgFvzynPm36ZCXtkydxu1cs27w=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endsection

@section('content')

    <div class="dashboard-page">

        {{-- =========================================================
        DASHBOARD HEADER
    ========================================================== --}}
        <div class="dashboard-header">
            <div>
                <div class="dashboard-eyebrow">
                    <i class="fas fa-chart-line"></i>
                    {{ __('Overview') }}
                </div>

                <h1 class="dashboard-title">
                    {{ __('Dashboard OS') }}
                </h1>

                <p class="dashboard-subtitle">
                    {{ __('Employee overview and workforce activity') }}
                </p>
            </div>

            <div class="dashboard-date">
                <i class="far fa-calendar-alt"></i>
                {{ now()->translatedFormat('d F Y') }}
            </div>
        </div>


        {{-- =========================================================
        ADMIN ALERTS
    ========================================================== --}}
        @if (auth()->user()->role == 8)
            <div class="dashboard-alerts">

                <div class="dashboard-alert alert-blue">
                    <div class="alert-icon">
                        <i class="fas fa-user-tag"></i>
                    </div>

                    <div class="alert-content">
                        <span class="alert-label">
                            Tanpa Etnis
                        </span>

                        <strong>
                            {{ $belum_isi_etnis }}
                        </strong>
                    </div>
                </div>

                <div class="dashboard-alert alert-violet">
                    <div class="alert-icon">
                        <i class="fas fa-phone-slash"></i>
                    </div>

                    <div class="alert-content">
                        <span class="alert-label">
                            Tanpa Kontak Darurat
                        </span>

                        <strong>
                            {{ $belum_isi_kontak_darurat }}
                        </strong>
                    </div>
                </div>

            </div>
        @endif


        {{-- =========================================================
        TODAY
    ========================================================== --}}
        <section class="dashboard-section">

            <div class="section-heading">
                <div class="section-title-wrapper">
                    <div class="section-icon today-icon">
                        <i class="fas fa-calendar-day"></i>
                    </div>

                    <div>
                        <h2>{{ __('Hari Ini') }}</h2>
                        <p>{{ __('Aktivitas karyawan hari ini') }}</p>
                    </div>
                </div>
            </div>


            <div class="dashboard-grid dashboard-grid-3">

                {{-- Karyawan Baru --}}
                <div class="stat-card stat-green">

                    <div class="stat-card-top">
                        <div class="stat-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>

                        <span class="stat-period">
                            TODAY
                        </span>
                    </div>

                    <div class="stat-card-content">

                        <div class="stat-label">
                            {{ __('Karyawan Baru Hari Ini') }}
                        </div>

                        <div class="stat-value">
                            {{ $jumlah_karyawan_baru_hari_ini }}
                        </div>

                    </div>

                    <div class="stat-footer">
                        <span>
                            <i class="fas fa-arrow-up"></i>
                            New Employee
                        </span>
                    </div>

                </div>


                {{-- Resigned --}}
                <div class="stat-card stat-blue">

                    <div class="stat-card-top">
                        <div class="stat-icon">
                            <i class="fas fa-user-minus"></i>
                        </div>

                        <span class="stat-period">
                            TODAY
                        </span>
                    </div>

                    <div class="stat-card-content">

                        <div class="stat-label">
                            {{ __('Karyawan Resigned Hari Ini') }}
                        </div>

                        <div class="stat-value">
                            {{ $jumlah_karyawan_Resigned_hari_ini }}
                        </div>

                    </div>

                    <div class="stat-footer">
                        <span>
                            <i class="fas fa-sign-out-alt"></i>
                            Resigned
                        </span>
                    </div>

                </div>


                {{-- Blacklist --}}
                <div class="stat-card stat-red">

                    <div class="stat-card-top">
                        <div class="stat-icon">
                            <i class="fas fa-user-shield"></i>
                        </div>

                        <span class="stat-period">
                            TODAY
                        </span>
                    </div>

                    <div class="stat-card-content">

                        <div class="stat-label">
                            {{ __('Karyawan Blacklist Hari Ini') }}
                        </div>

                        <div class="stat-value">
                            {{ $jumlah_karyawan_blacklist_hari_ini }}
                        </div>

                    </div>

                    <div class="stat-footer">
                        <span>
                            <i class="fas fa-ban"></i>
                            Blacklist
                        </span>
                    </div>

                </div>

            </div>

        </section>


        {{-- =========================================================
        LAST WEEK
    ========================================================== --}}
        <section class="dashboard-section">

            <div class="section-heading">

                <div class="section-title-wrapper">

                    <div class="section-icon week-icon">
                        <i class="fas fa-history"></i>
                    </div>

                    <div>
                        <h2>{{ __('Minggu Lalu') }}</h2>
                        <p>{{ __('Ringkasan aktivitas minggu sebelumnya') }}</p>
                    </div>

                </div>

            </div>


            <div class="dashboard-grid dashboard-grid-3">

                {{-- Karyawan Baru --}}
                <div class="stat-card stat-green">

                    <div class="stat-card-top">

                        <div class="stat-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>

                        <span class="stat-period">
                            LAST WEEK
                        </span>

                    </div>

                    <div class="stat-card-content">

                        <div class="stat-label">
                            {{ __('Karyawan Baru Minggu Lalu') }}
                        </div>

                        <div class="stat-value">
                            {{ $jumlah_karyawan_baru_minggu_lalu }}
                        </div>

                    </div>

                    <div class="stat-footer">
                        <span>
                            <i class="fas fa-users"></i>
                            New Employee
                        </span>
                    </div>

                </div>


                {{-- Resigned --}}
                <div class="stat-card stat-blue">

                    <div class="stat-card-top">

                        <div class="stat-icon">
                            <i class="fas fa-user-minus"></i>
                        </div>

                        <span class="stat-period">
                            LAST WEEK
                        </span>

                    </div>

                    <div class="stat-card-content">

                        <div class="stat-label">
                            {{ __('Karyawan Resigned Minggu Lalu') }}
                        </div>

                        <div class="stat-value">
                            {{ $jumlah_karyawan_resign_minggu_lalu }}
                        </div>

                    </div>

                    <div class="stat-footer">
                        <span>
                            <i class="fas fa-sign-out-alt"></i>
                            Resigned
                        </span>
                    </div>

                </div>


                {{-- Blacklist --}}
                <div class="stat-card stat-red">

                    <div class="stat-card-top">

                        <div class="stat-icon">
                            <i class="fas fa-user-shield"></i>
                        </div>

                        <span class="stat-period">
                            LAST WEEK
                        </span>

                    </div>

                    <div class="stat-card-content">

                        <div class="stat-label">
                            {{ __('Karyawan Blacklist Minggu Lalu') }}
                        </div>

                        <div class="stat-value">
                            {{ $jumlah_karyawan_blacklist_minggu_lalu }}
                        </div>

                    </div>

                    <div class="stat-footer">
                        <span>
                            <i class="fas fa-ban"></i>
                            Blacklist
                        </span>
                    </div>

                </div>

            </div>

        </section>


        {{-- =========================================================
        MTD SUMMARY
    ========================================================== --}}
        <section class="dashboard-section dashboard-mtd-section">

            <div class="section-heading">

                <div class="section-title-wrapper">

                    <div class="section-icon mtd-icon">
                        <i class="fas fa-chart-pie"></i>
                    </div>

                    <div>
                        <h2>{{ __('Month To Date') }}</h2>
                        <p>{{ __('Ringkasan karyawan bulan berjalan') }}</p>
                    </div>

                </div>

                <div class="mtd-badge">
                    <i class="fas fa-calendar-alt"></i>
                    MTD
                </div>

            </div>


            <div class="dashboard-grid dashboard-grid-4">

                {{-- Karyawan Baru --}}
                <div class="stat-card stat-green">

                    <div class="stat-card-top">

                        <div class="stat-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>

                        <span class="stat-period">
                            MTD
                        </span>

                    </div>

                    <div class="stat-card-content">

                        <div class="stat-label">
                            {{ __('Karyawan Baru MTD') }}
                        </div>

                        <div class="stat-value">
                            {{ $karyawan_baru_mtd }}
                        </div>

                    </div>

                    <div class="stat-footer">
                        <span>
                            <i class="fas fa-user-plus"></i>
                            New Employee
                        </span>
                    </div>

                </div>


                {{-- Resigned --}}
                <div class="stat-card stat-blue">

                    <div class="stat-card-top">

                        <div class="stat-icon">
                            <i class="fas fa-user-minus"></i>
                        </div>

                        <span class="stat-period">
                            MTD
                        </span>

                    </div>

                    <div class="stat-card-content">

                        <div class="stat-label">
                            {{ __('Karyawan Resigned MTD') }}
                        </div>

                        <div class="stat-value">
                            {{ $karyawan_resigned_mtd }}
                        </div>

                    </div>

                    <div class="stat-footer">
                        <span>
                            <i class="fas fa-sign-out-alt"></i>
                            Resigned
                        </span>
                    </div>

                </div>


                {{-- Blacklist --}}
                <div class="stat-card stat-red">

                    <div class="stat-card-top">

                        <div class="stat-icon">
                            <i class="fas fa-user-shield"></i>
                        </div>

                        <span class="stat-period">
                            MTD
                        </span>

                    </div>

                    <div class="stat-card-content">

                        <div class="stat-label">
                            {{ __('Karyawan Blacklist MTD') }}
                        </div>

                        <div class="stat-value">
                            {{ $karyawan_blacklist_mtd }}
                        </div>

                    </div>

                    <div class="stat-footer">
                        <span>
                            <i class="fas fa-ban"></i>
                            Blacklist
                        </span>
                    </div>

                </div>


                {{-- Aktif --}}
                <div class="stat-card stat-purple active-card">

                    <div class="stat-card-top">

                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>

                        <span class="stat-period">
                            MTD
                        </span>

                    </div>

                    <div class="stat-card-content">

                        <div class="stat-label">
                            {{ __('Karyawan Aktif MTD') }}
                        </div>

                        <div class="stat-value">
                            {{ number_format($karyawan_aktif_mtd) }}
                        </div>

                    </div>

                    <div class="stat-footer">
                        <span>
                            <i class="fas fa-check-circle"></i>
                            Active Employee
                        </span>
                    </div>

                </div>

            </div>

        </section>


        {{-- =========================================================
        LIVEWIRE REPORTS
    ========================================================== --}}

        <section class="report-section">

            <div class="report-header">
                <div>
                    <div class="report-eyebrow">
                        <i class="fas fa-chart-bar"></i>
                        REPORTS
                    </div>

                    <h2>
                        {{ __('Workforce Reports') }}
                    </h2>

                    <p>
                        {{ __('Detailed employee and workforce analysis') }}
                    </p>
                </div>
            </div>

            <div class="report-container">
                <livewire:placementreport />
            </div>

            <div class="report-container">
                <livewire:agamadetail />
            </div>

            <div class="report-container">
                <livewire:turnover />
            </div>

        </section>

    </div>


    <style>
        /* =========================================================
           DASHBOARD BASE
        ========================================================== */

        .dashboard-page {
            width: 100%;
            max-width: 1500px;
            margin: 0 auto;
            padding: 25px 30px 50px;
            color: #323c43;
        }


        /* =========================================================
           HEADER
        ========================================================== */

        .dashboard-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 30px;
        }

        .dashboard-eyebrow {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #64748b;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .dashboard-eyebrow i {
            color: #0084f4;
        }

        .dashboard-title {
            margin: 0;
            font-size: 34px;
            line-height: 1.2;
            font-weight: 700;
            letter-spacing: -0.7px;
            color: #1e293b;
        }

        .dashboard-subtitle {
            margin: 7px 0 0;
            color: #94a3b8;
            font-size: 14px;
        }

        .dashboard-date {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 15px;
            background: #ffffff;
            border: 1px solid #e8edf3;
            border-radius: 10px;
            color: #64748b;
            font-size: 13px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(15, 23, 42, 0.04);
            white-space: nowrap;
        }


        /* =========================================================
           ADMIN ALERTS
        ========================================================== */

        .dashboard-alerts {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 15px;
            margin-bottom: 28px;
        }

        .dashboard-alert {
            position: relative;
            display: flex;
            align-items: center;
            gap: 15px;
            min-height: 76px;
            padding: 15px 18px;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid transparent;
        }

        .dashboard-alert::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
        }

        .alert-blue {
            background: linear-gradient(135deg,
                    rgba(0, 132, 244, 0.07),
                    rgba(26, 77, 162, 0.03));
            border-color: rgba(0, 132, 244, 0.12);
        }

        .alert-blue::before {
            background: #0084f4;
        }

        .alert-violet {
            background: linear-gradient(135deg,
                    rgba(124, 58, 237, 0.07),
                    rgba(139, 92, 246, 0.03));
            border-color: rgba(124, 58, 237, 0.12);
        }

        .alert-violet::before {
            background: #7c3aed;
        }

        .alert-icon {
            width: 42px;
            height: 42px;
            min-width: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
        }

        .alert-blue .alert-icon {
            background: rgba(0, 132, 244, 0.12);
            color: #0084f4;
        }

        .alert-violet .alert-icon {
            background: rgba(124, 58, 237, 0.12);
            color: #7c3aed;
        }

        .alert-content {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .alert-label {
            color: #64748b;
            font-size: 13px;
            font-weight: 600;
        }

        .alert-content strong {
            color: #1e293b;
            font-size: 22px;
            line-height: 1;
        }


        /* =========================================================
           SECTIONS
        ========================================================== */

        .dashboard-section {
            margin-bottom: 34px;
        }

        .section-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .section-title-wrapper {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .section-icon {
            width: 40px;
            height: 40px;
            min-width: 40px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
        }

        .today-icon {
            background: rgba(0, 196, 140, 0.10);
            color: #00a173;
        }

        .week-icon {
            background: rgba(0, 132, 244, 0.10);
            color: #0084f4;
        }

        .mtd-icon {
            background: rgba(124, 58, 237, 0.10);
            color: #7c3aed;
        }

        .section-heading h2 {
            margin: 0;
            color: #1e293b;
            font-size: 17px;
            line-height: 1.2;
            font-weight: 700;
        }

        .section-heading p {
            margin: 4px 0 0;
            color: #94a3b8;
            font-size: 12px;
        }

        .mtd-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            border-radius: 7px;
            background: rgba(124, 58, 237, 0.08);
            color: #7c3aed;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .7px;
        }


        /* =========================================================
           GRID
        ========================================================== */

        .dashboard-grid {
            display: grid;
            gap: 16px;
        }

        .dashboard-grid-3 {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .dashboard-grid-4 {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }


        /* =========================================================
           STAT CARD
        ========================================================== */

        .stat-card {
            position: relative;
            min-height: 185px;
            background: #ffffff;
            border: 1px solid #e9eef4;
            border-radius: 14px;
            padding: 20px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(15, 23, 42, 0.055);
            transition:
                transform .2s ease,
                box-shadow .2s ease,
                border-color .2s ease;
        }

        .stat-card::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            height: 3px;
            opacity: .9;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.09);
            border-color: #dce4ec;
        }


        /* =========================================================
           CARD COLORS
        ========================================================== */

        .stat-green::after {
            background: linear-gradient(90deg,
                    #00c48c,
                    #00a173);
        }

        .stat-blue::after {
            background: linear-gradient(90deg,
                    #0084f4,
                    #1a4da2);
        }

        .stat-red::after {
            background: linear-gradient(90deg,
                    #ff647c,
                    #e11d48);
        }

        .stat-purple::after {
            background: linear-gradient(90deg,
                    #8b5cf6,
                    #6d28d9);
        }


        /* =========================================================
           CARD TOP
        ========================================================== */

        .stat-card-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }

        .stat-green .stat-icon {
            background: rgba(0, 196, 140, 0.10);
            color: #00a173;
        }

        .stat-blue .stat-icon {
            background: rgba(0, 132, 244, 0.10);
            color: #0084f4;
        }

        .stat-red .stat-icon {
            background: rgba(255, 100, 124, 0.10);
            color: #e11d48;
        }

        .stat-purple .stat-icon {
            background: rgba(139, 92, 246, 0.10);
            color: #7c3aed;
        }

        .stat-period {
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 1px;
            color: #a0aabb;
        }


        /* =========================================================
           CARD CONTENT
        ========================================================== */

        .stat-label {
            color: #64748b;
            font-size: 13px;
            line-height: 1.45;
            font-weight: 600;
            min-height: 38px;
            display: flex;
            align-items: center;
        }

        .stat-value {
            margin-top: 4px;
            color: #1e293b;
            font-size: 34px;
            line-height: 1.1;
            font-weight: 700;
            letter-spacing: -1px;
        }


        /* =========================================================
           CARD FOOTER
        ========================================================== */

        .stat-footer {
            position: absolute;
            left: 20px;
            right: 20px;
            bottom: 13px;
            padding-top: 8px;
            border-top: 1px solid #f1f4f7;
        }

        .stat-footer span {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #a0aabb;
            font-size: 10px;
            font-weight: 600;
        }

        .stat-green .stat-footer i {
            color: #00a173;
        }

        .stat-blue .stat-footer i {
            color: #0084f4;
        }

        .stat-red .stat-footer i {
            color: #e11d48;
        }

        .stat-purple .stat-footer i {
            color: #7c3aed;
        }


        /* =========================================================
           MTD
        ========================================================== */

        .dashboard-mtd-section {
            margin-bottom: 40px;
        }

        .active-card {
            background:
                linear-gradient(145deg,
                    #ffffff 0%,
                    #fbfaff 100%);
        }


        /* =========================================================
           REPORTS
        ========================================================== */

        .report-section {
            margin-top: 10px;
        }

        .report-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
            padding-bottom: 14px;
            border-bottom: 1px solid #e8edf3;
        }

        .report-eyebrow {
            display: flex;
            align-items: center;
            gap: 7px;
            color: #0084f4;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.2px;
            margin-bottom: 4px;
        }

        .report-header h2 {
            margin: 0;
            color: #1e293b;
            font-size: 20px;
            font-weight: 700;
        }

        .report-header p {
            margin: 4px 0 0;
            color: #94a3b8;
            font-size: 12px;
        }

        .report-container {
            background: #ffffff;
            border: 1px solid #e9eef4;
            border-radius: 14px;
            padding: 20px;
            margin-bottom: 18px;
            box-shadow: 0 5px 20px rgba(15, 23, 42, 0.04);
        }


        /* =========================================================
           RESPONSIVE - TABLET
        ========================================================== */

        @media (max-width: 1199px) {

            .dashboard-grid-4 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .dashboard-grid-3 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

        }


        /* =========================================================
           RESPONSIVE - MOBILE
        ========================================================== */

        @media (max-width: 767px) {

            .dashboard-page {
                padding: 18px 12px 35px;
            }

            .dashboard-header {
                align-items: flex-start;
                flex-direction: column;
                margin-bottom: 22px;
            }

            .dashboard-title {
                font-size: 27px;
            }

            .dashboard-subtitle {
                font-size: 12px;
            }

            .dashboard-date {
                width: 100%;
                justify-content: center;
                padding: 9px 12px;
            }

            .dashboard-alerts {
                grid-template-columns: 1fr;
                gap: 10px;
                margin-bottom: 22px;
            }

            .dashboard-alert {
                min-height: 68px;
            }

            .dashboard-section {
                margin-bottom: 28px;
            }

            .section-heading {
                margin-bottom: 12px;
            }

            .section-title-wrapper {
                gap: 9px;
            }

            .section-icon {
                width: 36px;
                height: 36px;
                min-width: 36px;
            }

            .section-heading h2 {
                font-size: 15px;
            }

            .section-heading p {
                font-size: 11px;
            }

            .dashboard-grid-3,
            .dashboard-grid-4 {
                grid-template-columns: 1fr;
                gap: 11px;
            }

            .stat-card {
                min-height: 155px;
                padding: 16px;
                border-radius: 12px;
            }

            .stat-card-top {
                margin-bottom: 14px;
            }

            .stat-icon {
                width: 38px;
                height: 38px;
                border-radius: 9px;
            }

            .stat-label {
                font-size: 12px;
                min-height: auto;
            }

            .stat-value {
                font-size: 29px;
                margin-top: 6px;
            }

            .stat-footer {
                left: 16px;
                right: 16px;
                bottom: 10px;
            }

            .report-header {
                margin-bottom: 14px;
            }

            .report-header h2 {
                font-size: 18px;
            }

            .report-container {
                padding: 12px;
                border-radius: 11px;
                margin-bottom: 12px;
                overflow-x: auto;
            }

        }


        /* =========================================================
           EXTRA SMALL DEVICES
        ========================================================== */

        @media (max-width: 400px) {

            .dashboard-title {
                font-size: 24px;
            }

            .stat-card {
                min-height: 150px;
            }

            .stat-value {
                font-size: 27px;
            }

            .alert-content strong {
                font-size: 20px;
            }

        }
    </style>

@endsection
