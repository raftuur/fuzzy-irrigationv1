<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Smart Irrigation') ?></title>

    <!-- ============================================================ -->
    <!-- CSS -->
    <!-- ============================================================ -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= base_url('assets/css/app.css') ?>" rel="stylesheet">

    <style>
        /* ============================================================
           GLOBAL RESET & BASE
           ============================================================ */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
        }

        /* ============================================================
           WRAPPER
           ============================================================ */
        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* ============================================================
           SIDEBAR
           ============================================================ */
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: #1a2332;
            color: #e9ecef;
            transition: all 0.3s ease;
            flex-shrink: 0;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 1050;
        }

        .sidebar-brand {
            padding: 20px 20px 15px 20px;
            font-size: 1.2rem;
            font-weight: 700;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            text-align: center;
        }

        .sidebar-brand i {
            color: #27ae60;
            font-size: 1.8rem;
        }

        .sidebar-brand h4 {
            color: #fff;
            margin-top: 8px;
            margin-bottom: 0;
        }

        .sidebar-brand small {
            color: rgba(255,255,255,0.4);
            font-size: 0.8rem;
        }

        /* Menu */
        .sidebar-menu {
            padding: 10px 0;
        }

        .sidebar-menu .menu-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            gap: 12px;
            font-size: 0.95rem;
        }

        .sidebar-menu .menu-item:hover {
            background: rgba(255,255,255,0.08);
            color: #fff;
        }

        .sidebar-menu .menu-item.active {
            background: rgba(39, 174, 96, 0.15);
            color: #27ae60;
            border-left-color: #27ae60;
        }

        .sidebar-menu .menu-item i {
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
        }

        .sidebar-menu .menu-item .menu-text {
            flex: 1;
        }

        .sidebar-menu .menu-item.text-danger:hover {
            color: #dc3545 !important;
            background: rgba(220, 53, 69, 0.1);
        }

        /* Divider */
        .sidebar-divider {
            border-color: rgba(255,255,255,0.08);
            margin: 10px 20px;
        }

        /* Sidebar Scrollbar */
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.05);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.15);
            border-radius: 4px;
        }

        /* ============================================================
           MAIN CONTENT
           ============================================================ */
        .main-content {
            flex: 1;
            min-height: 100vh;
            background: #f0f2f5;
        }

        /* ============================================================
           TOGGLE BUTTON (Mobile)
           ============================================================ */
        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 10px;
            left: 10px;
            z-index: 1060;
            background: #1a2332;
            border: none;
            color: white;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 1.2rem;
            cursor: pointer;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .sidebar-toggle:hover {
            background: #2c3e50;
        }

        /* ============================================================
           OVERLAY (Mobile)
           ============================================================ */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1040;
        }

        .sidebar-overlay.show {
            display: block;
        }

        /* ============================================================
           RESPONSIVE
           ============================================================ */
        @media (max-width: 992px) {
            .sidebar {
                position: fixed;
                left: -280px;
                top: 0;
                height: 100vh;
                transition: left 0.3s ease;
                z-index: 1050;
            }

            .sidebar.show {
                left: 0;
            }

            .sidebar-toggle {
                display: block;
            }

            .main-content {
                padding-top: 60px;
            }

            .sidebar-overlay.show {
                display: block;
            }
        }

        @media (max-width: 576px) {
            .sidebar {
                width: 280px;
                left: -300px;
            }

            .sidebar-brand {
                font-size: 1rem;
                padding: 15px 15px 10px 15px;
            }

            .sidebar-menu .menu-item {
                padding: 10px 16px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>

<!-- ============================================================ -->
<!-- TOGGLE BUTTON (Mobile) -->
<!-- ============================================================ -->
<button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle Sidebar">
    <i class="bi bi-list"></i>
</button>

<!-- ============================================================ -->
<!-- OVERLAY (Mobile) -->
<!-- ============================================================ -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- ============================================================ -->
<!-- WRAPPER -->
<!-- ============================================================ -->
<div class="wrapper">

    <!-- ===================== SIDEBAR ===================== -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <i class="bi bi-droplet-fill"></i>
            <h4>Smart Irigasi</h4>
            <small>Monitoring System</small>
        </div>

        <div class="sidebar-menu">
            <!-- Dashboard -->
            <a href="<?= base_url('dashboard') ?>" 
               class="menu-item <?= (current_url() == base_url('dashboard') || current_url() == base_url('/')) ? 'active' : '' ?>">
                <i class="bi bi-speedometer2"></i>
                <span class="menu-text">Dashboard</span>
            </a>

            <!-- Kontrol -->
            <a href="<?= base_url('kontrol') ?>" 
               class="menu-item <?= (current_url() == base_url('kontrol')) ? 'active' : '' ?>">
                <i class="bi bi-sliders2"></i>
                <span class="menu-text">Kontrol</span>
            </a>

            <!-- Riwayat -->
            <a href="<?= base_url('riwayat') ?>" 
               class="menu-item <?= (current_url() == base_url('riwayat')) ? 'active' : '' ?>">
                <i class="bi bi-clock-history"></i>
                <span class="menu-text">Riwayat</span>
            </a>

            <!-- Device -->
            <a href="<?= base_url('device') ?>" 
               class="menu-item <?= (current_url() == base_url('device')) ? 'active' : '' ?>">
                <i class="bi bi-cpu"></i>
                <span class="menu-text">Device</span>
            </a>

            <!-- Setting -->
            <a href="<?= base_url('setting') ?>" 
               class="menu-item <?= (current_url() == base_url('setting')) ? 'active' : '' ?>">
                <i class="bi bi-gear-fill"></i>
                <span class="menu-text">Setting</span>
            </a>

            <!-- Admin -->
            <a href="<?= base_url('admin') ?>" 
               class="menu-item <?= (current_url() == base_url('admin')) ? 'active' : '' ?>">
                <i class="bi bi-people"></i>
                <span class="menu-text">Data Admin</span>
            </a>

            <!-- Log Aktivitas -->
            <a href="<?= base_url('log') ?>" 
               class="menu-item <?= (current_url() == base_url('log')) ? 'active' : '' ?>">
                <i class="bi bi-clipboard-data"></i>
                <span class="menu-text">Log Aktivitas</span>
            </a>

            <hr class="sidebar-divider">

            <!-- Logout -->
            <a href="<?= base_url('logout') ?>" class="menu-item text-danger">
                <i class="bi bi-box-arrow-right"></i>
                <span class="menu-text">Logout</span>
            </a>
        </div>
    </nav>

    <!-- ===================== MAIN CONTENT ===================== -->
    <div class="main-content">

        <!-- Navbar -->
        <?= $this->include('layouts/navbar') ?>

        <!-- Content -->
        <div class="container-fluid p-4">
            <?= $this->renderSection('content') ?>
        </div>

        <!-- Footer -->
        <?= $this->include('layouts/footer') ?>

    </div>

</div>

<!-- ============================================================ -->
<!-- SCRIPTS -->
<!-- ============================================================ -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js">
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js">
</script>

<!-- ============================================================ -->
<!-- SIDEBAR TOGGLE SCRIPT -->
<!-- ============================================================ -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');
    const overlay = document.getElementById('sidebarOverlay');

    // Toggle sidebar
    function toggleSidebar() {
        sidebar.classList.toggle('show');
        if (overlay) overlay.classList.toggle('show');
    }

    // Event: Toggle button
    if (toggleBtn) {
        toggleBtn.addEventListener('click', toggleSidebar);
    }

    // Event: Overlay click
    if (overlay) {
        overlay.addEventListener('click', toggleSidebar);
    }

    // Event: Close sidebar on resize to desktop
    window.addEventListener('resize', function() {
        if (window.innerWidth > 992) {
            sidebar.classList.remove('show');
            if (overlay) overlay.classList.remove('show');
        }
    });

    // Event: Close sidebar on menu click (mobile)
    const menuItems = sidebar.querySelectorAll('.menu-item');
    menuItems.forEach(function(item) {
        item.addEventListener('click', function() {
            if (window.innerWidth <= 992) {
                sidebar.classList.remove('show');
                if (overlay) overlay.classList.remove('show');
            }
        });
    });
});
</script>

<!-- ============================================================ -->
<!-- PAGE SPECIFIC SCRIPTS -->
<!-- ============================================================ -->
<?= $this->renderSection('scripts') ?>

</body>
</html>