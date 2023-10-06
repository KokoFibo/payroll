<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css"
        rel="stylesheet">

    <!-- Scripts -->

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])


    <title>{{ $title ?? config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">



    {{-- <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback"> --}}
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />


</head>
<style>
    body {
        background-color: #F4F6F9;
    }
</style>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">


        @include('layouts.navbar')

        @include('layouts.aside')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper ">

            {{ $slot }}

        </div>
        <!-- /.content-wrapper -->

        @include('layouts.footer')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>

        <script>
            $(".date").datepicker({
                format: "dd-mm-yyyy",
                todayHighlight: true,
                orientation: "auto",
            });
        </script>
</body>

</html>
