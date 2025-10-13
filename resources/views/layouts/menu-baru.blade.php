<div class="sidebar bg-dark text-white d-flex flex-column justify-content-between vh-100 p-2">
    <!-- Bagian Atas -->
    <div class="d-flex flex-column overflow-auto">
        <!-- Logo -->
        {{-- <div class="logo fw-bold text-center py-2 mb-2 border-bottom border-secondary">
            ⚡ Admin Panel
        </div> --}}
        <div class="logo fw-bold text-center py-3 mb-2 border-bottom border-secondary">
            <a href="/" class="brand-link nightowl-daylight">
                <img src="{{ asset('images/logo-only.png') }}" width="60px" alt="Yifang Logo" style="opacity: .8"></a>
        </div>


        </a>

        <!-- Menu utama -->
        <ul class="nav flex-column small">
            <li class="nav-item">
                <a href="/" class="nav-link text-white py-1">
                    <i class="bi bi-house-door-fill me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="/karyawanindex" class="nav-link text-white py-1">
                    <i class="bi bi-people-fill me-2"></i> Data Karyawan
                </a>
            </li>

            @if (Auth::user()->role >= 4)
                <li class="nav-item">
                    <a href="/dataapplicant" class="nav-link text-white py-1">
                        <i class="bi bi-folder2-open me-2"></i> Data Applicant
                    </a>
                </li>
            @endif

            @if (Auth::user()->role > 5 || Auth::user()->role == 2)
                @if (isRequester(auth()->user()->username) || Auth::user()->role > 5)
                    <li class="nav-item">
                        <a href="/permohonan-personnel" class="nav-link text-white py-1">
                            <i class="bi bi-person-lines-fill me-2"></i> Personnel Request
                        </a>
                    </li>
                @endif
            @endif

            @if (Auth::user()->role >= 5)
                @if (Auth::user()->role > 7)
                    <li class="nav-item">
                        <a href="/dataresigned" class="nav-link text-white py-1">
                            <i class="bi bi-person-dash-fill me-2"></i> Data Resigned
                        </a>
                    </li>
                @endif

                <li class="nav-item">
                    <a href="/tambahan" class="nav-link text-white py-1">
                        <i class="bi bi-cash-stack me-2"></i> Bonus & Potongan
                    </a>
                </li>

                @if (Auth::user()->role >= 6)
                    <li class="nav-item">
                        <a href="/salaryadjustment" class="nav-link text-white py-1">
                            <i class="bi bi-currency-dollar me-2"></i> Penyesuaian Gaji
                        </a>
                    </li>
                @endif

                @if (Auth::user()->role == 8)
                    <li class="nav-item">
                        <a href="/yfpresensiindexwr" class="nav-link text-white py-1">
                            <i class="bi bi-clock-history me-2"></i> Old Presensi
                        </a>
                    </li>
                @endif

                <li class="nav-item">
                    <a href="/newpresensi" class="nav-link text-white py-1">
                        <i class="bi bi-calendar-check-fill me-2"></i> Presensi
                    </a>
                </li>

                @if (Auth::user()->role >= 6)
                    <li class="nav-item">
                        <a href="/payroll" class="nav-link text-white py-1">
                            <i class="bi bi-receipt-cutoff me-2"></i> Payroll
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/gajibpjs" class="nav-link text-white py-1">
                            <i class="bi bi-shield-lock-fill me-2"></i> BPJS / PTKP
                        </a>
                    </li>
                @endif

                <li class="nav-item">
                    <a href="/informationwr" class="nav-link text-white py-1">
                        <i class="bi bi-info-circle-fill me-2"></i> Add Information
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/liburnasional" class="nav-link text-white py-1">
                        <i class="bi bi-calendar2-week-fill me-2"></i> Libur Nasional
                    </a>
                </li>

                @if (Auth::user()->role >= 6)
                    <li class="nav-item">
                        <a href="/usermobile" class="nav-link text-white py-1">
                            <i class="bi bi-phone-fill me-2"></i> User Mobile
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/hitungthr" class="nav-link text-white py-1">
                            <i class="bi bi-gift-fill me-2"></i> Hitung THR
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/data-log" class="nav-link text-white py-1">
                            <i class="bi bi-clock-fill me-2"></i> History Gaji
                        </a>
                    </li>
                @endif

                <!-- Setting dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white py-1" href="#" id="settingDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-gear-fill me-2"></i> Setting
                    </a>
                    <ul class="dropdown-menu bg-secondary border-0 shadow-sm small">
                        <li>
                            <a class="dropdown-item text-white py-1" href="/changeprofilewr">
                                <i class="bi bi-person-fill me-2"></i> Change Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item text-white py-1" href="/karyawansettingwr">
                                <i class="bi bi-tools me-2"></i> Karyawan Settings
                            </a>
                        </li>
                        @if (Auth::user()->role > 6)
                            <li>
                                <a class="dropdown-item text-white py-1" href="/changeuserrolewr">
                                    <i class="bi bi-person-gear me-2"></i> Change User Role
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>

                @if (Auth::user()->role > 7)
                    <li><a class="nav-link text-white py-1" href="/laporan"><i class="bi bi-bar-chart-fill me-2"></i>
                            Laporan</a></li>
                    <li><a class="nav-link text-white py-1" href="/applicant-accepted"><i
                                class="bi bi-person-check-fill me-2"></i> Applicant Diterima</a></li>
                    <li><a class="nav-link text-white py-1" href="/changefield"><i class="bi bi-pencil-square me-2"></i>
                            Change Field</a></li>
                    <li><a class="nav-link text-white py-1" href="/developer-dashboard"><i
                                class="bi bi-code-slash me-2"></i> Developer Dashboard</a></li>
                    <li><a class="nav-link text-white py-1" href="/deletenoscan"><i
                                class="bi bi-trash3-fill me-2"></i>
                            Delete Noscan</a></li>
                    <li><a class="nav-link text-white py-1" href="/test"><i
                                class="bi bi-lightning-charge-fill me-2"></i> Test Livewire</a></li>
                    <li><a class="nav-link text-white py-1" href="/UserLog"><i class="bi bi-journal-text me-2"></i>
                            User Log</a></li>
                @endif
            @endif
        </ul>
    </div>

    <!-- Logout -->
    <ul class="nav flex-column mt-auto small">
        <li class="nav-item">
            <a href="{{ route('logout') }}" class="nav-link text-white py-1"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-right me-2"></i> Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        </li>
    </ul>
</div>

<style>
    .sidebar {
        width: max-content;
        min-width: 220px;
        transition: all 0.3s ease;
        font-size: 1.1rem;
        overflow-y: auto;
        overflow-x: hidden;
        scrollbar-width: thin;
    }

    .sidebar .nav-link {
        text-decoration: none !important;
        color: #f8f9fa;
        border-radius: 4px;
        padding: 3px 8px;
        display: flex;
        align-items: center;
        line-height: 1;
        white-space: nowrap;
    }

    .sidebar .nav-link i {
        font-size: 0.9rem;
    }

    .sidebar .nav-link:hover,
    .sidebar .dropdown-item:hover {
        background-color: rgba(255, 255, 255, 0.15);
    }

    .sidebar .dropdown-menu {
        margin-left: 14px;
        font-size: 0.78rem;
        padding: 4px 0;
    }

    .sidebar .dropdown-item {
        padding: 3px 10px;
        line-height: 1.1;
    }

    .sidebar .logo {
        letter-spacing: 0.3px;
        font-size: 0.9rem;
    }

    .sidebar::-webkit-scrollbar {
        width: 5px;
    }

    .sidebar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 3px;
    }
</style>
