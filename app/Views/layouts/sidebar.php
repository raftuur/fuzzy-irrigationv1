<div class="sidebar d-flex flex-column">

    <div class="logo text-center">

        <h4 class="mb-1">Smart Irrigation</h4>

        <small class="text-muted">
            Monitoring System
        </small>

    </div>

    <div class="mt-4">

        <a href="<?= site_url('dashboard') ?>"
            class="nav-link <?= uri_string() == 'dashboard' ? 'active' : '' ?>">

            <i class="bi bi-speedometer2 me-2"></i>
            Dashboard

        </a>

        <a href="<?= site_url('riwayat') ?>"
            class="nav-link <?= uri_string() == 'riwayat' ? 'active' : '' ?>">

            <i class="bi bi-clock-history me-2"></i>
            Riwayat

        </a>

        <a href="<?= site_url('kontrol') ?>"
            class="nav-link <?= uri_string() == 'kontrol' ? 'active' : '' ?>">

            <i class="bi bi-sliders me-2"></i>
            Kontrol

        </a>

        <!-- ========================================== -->
        <!-- TAMBAHKAN MENU DEVICE DI SINI -->
        <!-- ========================================== -->
        <a href="<?= site_url('device') ?>"
            class="nav-link <?= uri_string() == 'device' ? 'active' : '' ?>">

            <i class="bi bi-cpu me-2"></i>
            Device

        </a>

        <a href="<?= site_url('admin') ?>"
            class="nav-link <?= uri_string() == 'admin' ? 'active' : '' ?>">

            <i class="bi bi-people me-2"></i>
            Data Admin

        </a>

        <a href="<?= site_url('log') ?>"
            class="nav-link <?= uri_string() == 'log' ? 'active' : '' ?>">

            <i class="bi bi-clipboard-data me-2"></i>
            Log Aktivitas

        </a>

    </div>

    <div class="mt-auto p-3">

        <a href="<?= site_url('logout') ?>"
            class="btn btn-outline-danger w-100">

            Logout

        </a>

    </div>

</div>