<!-- 🚨 Gas Popup Alert -->
<div id="gasPopup" class="popup hidden">
    <strong>🚨 Gas Alert!</strong><br>
    <span id="gasPopupMessage"></span>
</div>

<!-- Main Navbar -->
<nav class="main-header navbar navbar-expand navbar-dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
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
                    <!-- Notification Icon -->
                    <li class="nav-item dropdown" id="push-notification-dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-bell"></i>
                    <span class="badge badge-danger navbar-badge" id="alert-badge-count" style="display:none;">0</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id="alert-dropdown-menu" style="max-width: 420px; max-height: 300px; overflow-y: auto;">
                    <span class="dropdown-header">Gas Alerts</span>
                    <div class="dropdown-divider"></div>
                    <div id="alert-list" class="px-3 py-2 text-sm">
                        <div class="text-muted text-center">No active alerts</div>
                    </div>

                     <!-- 🔴 Clear All Button -->
                    <div class="dropdown-item text-center">
                        <button class="btn btn-sm btn-outline-danger" onclick="clearAllAlerts()">Clear All</button>
                    </div>
                </div>
            </li>
        @auth
            <!-- Logout Button -->
            <li class="nav-item">
                <button class="logout-button" onclick="confirmLogout(event)">
                    <i class="fas fa-power-off"></i>
                    <span>Logout</span>
                </button>
            </li>

            <script>
            function confirmLogout(event) {
                event.preventDefault();
                let confirmAction = confirm("Apakah Anda yakin ingin logout?");
                if (confirmAction) {
                    window.location.href = "/logout";
                }
            }
            </script>

            <script>
                function showAlertHistory() {
                    let history = alertHistory;
                    const saved = localStorage.getItem("alertHistory");
                    if (saved) history = JSON.parse(saved);
                    if (!history.length) {
                        alert("No alert history found.");
                        return;
                    }
                    const historyText = history.join('\n\n');
                    alert("📜 Gas Alert History:\n\n" + historyText);
                }
            </script>
        @else
            <!-- Login Button -->
            <li class="nav-item">
                <div class="login-button">
                    <a class="nav-link" href="/login">
                        <i class="fas fa-power-off"></i>
                        <span>Login</span>
                    </a>
                </div>
            </li>
        @endauth
    </ul>
</nav>
