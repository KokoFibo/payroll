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
            <a href="/karyawanindex" class="nav-link" wire:navigate>
                <i class="nav-icon fa-solid fa-people-group"></i>
                <p>
                    Data Karyawan
                </p>
            </a>
        </li>
        @if (Auth::user()->role != 1)
            <li class="nav-item">
                <a href="/yfpresensiindexwr" class="nav-link" wire:navigate>
                    <i class="nav-icon fas fa-clipboard-check"></i>
                    <p>Presensi</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="/payrollindex" class="nav-link" wire:navigate>
                    <i class="nav-icon fas fa-dollar-sign"></i>
                    <p>
                        Payroll
                    </p>
                </a>
            </li>





            <li class="nav-item ">
                <a href="#" class="nav-link">
                    <i class="nav-icon fa-solid fa-screwdriver-wrench"></i>
                    <p>
                        Developer Tools
                        <i class="right fas fa-angle-left"></i>

                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="/karyawanviewimport" class="nav-link" wire:navigate>
                            <i class="far fa-circle nav-icon"></i>
                            <p>Karyawan Excel Uploader</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="/yfdeletetanggalpresensiwr" class="nav-link" wire:navigate>
                            <i class="far fa-circle nav-icon"></i>
                            <p>Delete Tgl Presensi</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/yfdeletepresensi" class="nav-link" wire:navigate>
                            <i class="far fa-circle nav-icon"></i>
                            <p class="text-danger">Truncate Table Presensi</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/deletenoscan" class="nav-link" wire:navigate>
                            <i class="far fa-circle nav-icon"></i>
                            <p>Delete No Scan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/deletejamkerja" class="nav-link" wire:navigate>
                            <i class="far fa-circle nav-icon"></i>
                            <p>Delete Jam Kerja</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link" wire:navigate>
                            <i class="far fa-circle nav-icon"></i>
                            <p>Reserved</p>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item">
                <a href="/test" class="nav-link" wire:navigate>
                    <i class="far fa-circle nav-icon"></i>
                    <p>test livewire</p>
                </a>
            </li>
            <li class="nav-item ">
                <a href="#" class="nav-link">
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
        @endif


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
