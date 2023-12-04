<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-dark text-light py-3">

    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <h2 class="ml-auto text-bold  fs-3">Yifang Investment Group Payroll System</h2>

    <!-- Right navbar links -->
    <ul class="navbar-nav
                ml-auto">

        <li class="nav-item">
            @if (app()->getLocale() == 'id')
                <a class="nav-link" href="{{ url('locale/cn') }}">中文 </a>
            @endif

            @if (app()->getLocale() == 'cn')
                <a class="nav-link" href="{{ url('locale/id') }}">{{ __('Indonesia') }} </a>
            @endif
        </li>

        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
    </ul>
</nav>
<!-- /.navbar -->
