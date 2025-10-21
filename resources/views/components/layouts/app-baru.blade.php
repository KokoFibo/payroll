<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | Yifang </title>

    {{-- Bootstrap Link --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">


    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    {{-- Google Nunito fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,100..900;1,9..144,100..900&family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap"
        rel="stylesheet">

    {{-- Jquery --}}
    <script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E="
        crossorigin="anonymous"></script>

    <!-- Bootstrap 5 -->
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> --}}

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

    {{-- Toaster --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />




    <style>
        :root {
            --sidebar-width: 250px;
            --sidebar-minimized-width: 80px;
            --sidebar-bg: #1f1f1f;
            --header-bg: #2a2a2a;
            --footer-bg: #2a2a2a;
            --text-light: #e9ecef;
        }

        body {
            background-color: #f8f9fa;
            color: #212529;
            overflow-x: hidden;
            font-family: "nunito", sans-serif;
        }

        .header-title {
            font-size: 1.4rem;
            font-weight: bold;
        }

        .welcome {
            display: flex;
            gap: 20px;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background-color: var(--sidebar-bg);
            color: var(--text-light);
            transition: width 0.3s ease;
            display: flex;
            flex-direction: column;
            z-index: 1000;
        }

        .sidebar.minimized {
            width: var(--sidebar-minimized-width);
        }

        .sidebar .logo {
            font-size: 1.4rem;
            text-align: center;
            padding: 1rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            background-color: #141414;
            white-space: nowrap;
        }

        .menu-container {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .menu-container ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .menu-container ul li {
            padding: 0.8rem 1rem;
            display: flex;
            align-items: center;
            transition: background 0.2s;
        }

        .menu-container ul li:hover {
            background-color: rgba(255, 255, 255, 0.1);
            cursor: pointer;
        }

        .menu-container ul li i {
            margin-right: 10px;
            font-size: 1.2rem;
        }

        .sidebar.minimized .menu-container ul li .text {
            display: none;
        }

        /* Main Content */
        .main-content {
            /* width: 100% */
            /* width: calc(100% - var(--sidebar-width)-30px); */
            width: calc(100% - 220px);
            /* margin-left: var(--sidebar-width); */
            margin-left: calc(var(--sidebar-width) - 30px);
            /* margin-left: 220px; */
            /* min-height: 100vh; */
            transition: margin-left 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .main-content.minimized {
            margin-left: var(--sidebar-minimized-width);
        }

        header {
            background-color: var(--header-bg);
            color: var(--text-light);
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        footer {
            background-color: var(--footer-bg);
            color: var(--text-light);
            padding: 0.75rem;
            text-align: center;
            font-size: 0.9rem;
        }

        main {
            flex-grow: 1;
            padding: 1.5rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                left: -250px;
            }

            .sidebar.active {
                left: 0;
            }

            .main-content {
                margin-left: 0 !important;
            }
        }
    </style>
</head>

<body>

    <div class="d-flex" style="width: 100%;">
        <!-- Sidebar -->
        <div id="sidebar" class="sidebar">
            {{-- <div class="logo">
                <i class="bi bi-box"></i> <span class="text">Yifang</span>
            </div> --}}

            <div class="menu-container">
                @include('layouts.menu-baru')
            </div>
        </div>

        <!-- Main Content -->
        <div id="main-content" class="main-content">

            <header style="width: 100%">
                <div class="d-flex align-items-center">
                    <button id="toggleSidebar" class="btn btn-outline-light " style="margin-left: 30px"><i
                            class="bi bi-list"></i></button>
                    {{-- <h5 class="mb-0">Dashboard</h5> --}}
                </div>
                <div class="header-title">Yifang Investment Group Payroll System (OS)</div>
                <div class="welcome">
                    @if (auth()->user()->language == 'Cn')
                        @if (app()->getLocale() == 'id')
                            {{-- <a class="dropdown-item" href="{{ url('locale/en') }}">{{ __('english') }}</a> --}}
                            <a class="nav-link" href="{{ url('locale/cn') }}">{{ __('中文') }}</a>
                        @endif
                        @if (app()->getLocale() == 'cn')
                            <a class="nav-link" href="{{ url('locale/id') }}">{{ __('英语') }}</a>
                        @endif
                    @endif
                    <div style="margin-right: 30px">
                        Welcome, {{ auth()->user()->name }}
                    </div>
                </div>

            </header>

            <main>
                <div>
                    <div class="container-fluid-aja">
                        {{ $slot ?? 'Konten ...' }}

                    </div>
            </main>

            <style>
                .container-fluid-aja {
                    /* width: calc(100% - var(--sidebar-width)); */
                    width: 100%;
                    /* margin: 0 auto; */
                    transition: width 0.3s ease;
                }

                /* Kalau sidebar disembunyikan */
                .sidebar-hidden .container-fluid-aja {
                    width: 100%;
                }
            </style>
            <div class="mt-5">

            </div>

            {{-- <footer >
                Copyright © 2025 Yifang Investment Group | All rights reserved.
            </footer> --}}
        </div>
    </div>

    <!-- JS -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> --}}
    <script>
        const toggleBtn = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content');

        toggleSidebar.addEventListener('click', () => {
            if (sidebar.style.display === 'none') {
                sidebar.style.display = 'flex';
                // mainContent.style.marginLeft = 'var(--sidebar-width)';
                mainContent.style.marginLeft = 'calc(var(--sidebar-width) - 30px)';
                mainContent.style.width = 'calc(100% - 220px)';

                //  width: calc(100% - 220px);
                /* margin-left: var(--sidebar-width); */
                // mainContent.style.marginLeft = '220px';
            } else {
                sidebar.style.display = 'none';
                mainContent.style.marginLeft = '0';
                mainContent.style.width = '100%';
            }
        });



        toggleBtn.addEventListener('click', () => {
            if (window.innerWidth < 768) {
                sidebar.classList.toggle('active');
            } else {
                sidebar.classList.toggle('minimized');
                mainContent.classList.toggle('minimized');
            }
        });
    </script>
    {{-- Toaster --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    {{-- Sweet alert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- flat picker bagus --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    {{-- Bootstrap Scripts --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script> --}}

    {{-- Flat Picker --}}
    <script>
        flatpickr("#tanggal", {
            dateFormat: "d M Y",
        });
    </script>
</body>

</html>
