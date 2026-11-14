<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta name="robots" content="noindex, nofollow">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ url('favicon/favicon-32x32.png') }}">

    <title>{{ $title ?? config('app.name') }} | Yifang</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@100..900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E="
        crossorigin="anonymous"></script>

    <!-- Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Night Owl -->
    <script type="module" src="https://cdn.jsdelivr.net/npm/@bufferhead/nightowl@0.0.14/dist/nightowl.js"></script>

    @livewireStyles

    @stack('styles')
</head>

<body class="min-h-screen bg-gray-100 font-sans text-gray-900 antialiased">

    <div id="app" class="min-h-screen">

        <!-- Navbar -->
        {{-- @include('layouts.navbar') --}}

        <!-- Sidebar -->
        {{-- @include('layouts.aside') --}}

        <!-- Main Content -->
        <main class="min-h-screen">

            {{ $slot }}

        </main>

        <!-- Footer -->
        @include('layouts.footer')

    </div>

    <!-- Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Flatpickr -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <!-- Bootstrap JS (temporary compatibility for old components) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    <!-- Flatpickr Initialization -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (document.querySelector('#tanggal')) {
                flatpickr('#tanggal', {
                    dateFormat: 'd M Y',
                });
            }
        });

        document.addEventListener('livewire:navigated', function() {
            if (document.querySelector('#tanggal')) {
                flatpickr('#tanggal', {
                    dateFormat: 'd M Y',
                });
            }
        });
    </script>

    @livewireScripts

    @stack('scripts')

    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ env('GA_ID', 'G-8KWREPG05Q') }}"></script>

    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', '{{ env('GA_ID', 'G-8KWREPG05Q') }}', {
            anonymize_ip: true
        });
    </script>

</body>

</html>
