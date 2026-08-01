<!-- ============================================================ -->
<!-- SIDEBAR -->
<!-- ============================================================ -->
<nav class="sidebar d-flex flex-column" id="sidebar">

    <!-- ===================== LOGO ===================== -->
    <div class="sidebar-brand text-center py-3">
        <i class="bi bi-droplet-fill text-success fs-2"></i>
        <h4 class="mb-0 mt-1 fw-bold">Smart Irigasi</h4>
        <small class="text-muted">Monitoring System</small>
    </div>

    <!-- ===================== MENU ===================== -->
    <div class="sidebar-menu mt-2">

        <!-- Dashboard -->
        <a href="<?= site_url('dashboard') ?>"
           class="menu-item <?= uri_string() == 'dashboard' || uri_string() == '' ? 'active' : '' ?>">
            <i class="bi bi-speedometer2"></i>
            <span class="menu-text">Dashboard</span>
        </a>

        <!-- Kontrol -->
        <a href="<?= site_url('kontrol') ?>"
           class="menu-item <?= uri_string() == 'kontrol' ? 'active' : '' ?>">
            <i class="bi bi-sliders2"></i>
            <span class="menu-text">Kontrol</span>
        </a>

        <!-- Riwayat -->
        <a href="<?= site_url('riwayat') ?>"
           class="menu-item <?= uri_string() == 'riwayat' ? 'active' : '' ?>">
            <i class="bi bi-clock-history"></i>
            <span class="menu-text">Riwayat</span>
        </a>

        <!-- Device -->
        <a href="<?= site_url('device') ?>"
           class="menu-item <?= uri_string() == 'device' ? 'active' : '' ?>">
            <i class="bi bi-cpu"></i>
            <span class="menu-text">Device</span>
        </a>

        <!-- Setting -->
        <a href="<?= site_url('setting') ?>"
           class="menu-item <?= uri_string() == 'setting' ? 'active' : '' ?>">
            <i class="bi bi-gear-fill"></i>
            <span class="menu-text">Setting</span>
        </a>

        <!-- Data Admin -->
        <a href="<?= site_url('admin') ?>"
           class="menu-item <?= uri_string() == 'admin' ? 'active' : '' ?>">
            <i class="bi bi-people"></i>
            <span class="menu-text">Data Admin</span>
        </a>

        <!-- Log Aktivitas -->
        <a href="<?= site_url('log') ?>"
           class="menu-item <?= uri_string() == 'log' ? 'active' : '' ?>">
            <i class="bi bi-clipboard-data"></i>
            <span class="menu-text">Log Aktivitas</span>
        </a>

    </div>

    <!-- ===================== FOOTER MENU ===================== -->
    <div class="mt-auto p-3">
        <hr style="border-color: rgba(255,255,255,0.1);">
        <a href="<?= site_url('logout') ?>" class="menu-item text-danger">
            <i class="bi bi-box-arrow-right"></i>
            <span class="menu-text">Logout</span>
        </a>
    </div>

</nav>