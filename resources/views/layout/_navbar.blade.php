<nav class="main-header navbar navbar-expand navbar-dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ url('/') }}" class="nav-link">Home</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="/contact" class="nav-link">Contact</a>
        </li>
    </ul>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        @auth
            <li class="nav-item">
                <button class="logout-button" onclick="confirmLogout(event)">
                    <i class="fas fa-power-off"></i>
                    <span>Logout</span>
                </button>
            </li>

            <script>
            function confirmLogout(event) {
                event.preventDefault(); // Mencegah langsung logout
                let confirmAction = confirm("Apakah Anda yakin ingin logout?");
                if (confirmAction) {
                    window.location.href = "/logout"; // Arahkan ke URL logout jika dikonfirmasi
                }
            }
            </script>

        @else
            <!-- Jika user belum login -->
            <li class="nav-item">
                <div class=login-button>
                    <a class="nav-link" href="/login">
                    <i class="fas fa-power-off"></i>
                    <span>Login</span>
                    </a>
                </div>
            </li>
        @endauth
    </ul>
</nav>
