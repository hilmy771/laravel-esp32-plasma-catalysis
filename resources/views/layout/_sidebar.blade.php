<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/') }}" class="brand-link">
        <img src="{{ asset('reglogin/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">UPT Lab Plasma-Catalysis</span>
    </a>

    <!-- Custom CSS untuk Brand Text -->
    <style>
        .brand-text {
            font-size: 1rem;
            white-space: normal !important;
            word-wrap: break-word;
            display: block;
            max-width: 100%;
        }
    </style>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar User Panel -->
        @if(Auth::check() && in_array(Auth::user()->role, ['admin', 'user']))
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('reglogin/dist/img/user2-160x160.jpg') }}"
                    class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="{{ url('/users') }}" class="d-block">{{ Auth::user()->name }}</a>
            </div>
        </div>
        @endif


        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ url('/') }}"
                        class="nav-link {{ request()->is('/') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('/sensor-data') }}"
                        class="nav-link {{ request()->is('sensor-data') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-history"></i>
                        <p>Data Sensor</p>
                    </a>
                </li>


                <!-- Devices (Hanya untuk Admin) -->
                @if(Auth::check() && Auth::user()->role === 'admin')
                    <li class="nav-item">
                        <a href="{{ route('devices.index') }}"
                            class="nav-link {{ request()->is('devices') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-microchip"></i>
                            <p>Devices</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('users_data.index2') }}"
                                class="nav-link {{ request()->is('users_data') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Users Data</p>
                        </a>
                    </li>
                @endif

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
