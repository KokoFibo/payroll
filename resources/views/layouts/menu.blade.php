<!-- Sidebar Menu -->
<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
with font-awesome or any other icon font library -->

        <li class="nav-item">
            <a href="/dashboard" class="nav-link" wire:navigate>
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                    Dashboard
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="/karyawan" class="nav-link" wire:navigate>
                <i class="nav-icon fa-solid fa-people-group"></i>
                <p>
                    Data Karyawan
                </p>
            </a>
        </li>

        <li class="nav-item">
            <a href="/presensi" class="nav-link" wire:navigate>
                <i class="nav-icon fas fa-clipboard-check"></i>
                <p>
                    Presensi
                </p>
            </a>
        </li>

        <li class="nav-item">
            <a href="/test" class="nav-link" wire:navigate>
                <i class="nav-icon fas fa-dollar-sign"></i>
                <p>
                    Payroll
                </p>
            </a>
        </li>
        <li class="nav-item ">
            <a href="#" class="nav-link" wire:navigate>
                <i class="nav-icon fa-solid fa-gear"></i>
                <p>
                    User Setting
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="#" class="nav-link" wire:navigate>
                        <i class="far fa-circle nav-icon"></i>
                        <p>Change Password</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" wire:navigate>
                        <i class="far fa-circle nav-icon"></i>
                        <p>Language Setting</p>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('logout') }}"
                onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                <i class="nav-icon fa-solid fa-right-from-bracket"></i>
                <p>{{ __('Logout') }}</p>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>
    </ul>




</nav>
<!-- /.sidebar-menu -->
