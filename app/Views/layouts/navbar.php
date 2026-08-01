<!-- ============================================================ -->
<!-- NAVBAR -->
<!-- ============================================================ -->
<nav class="navbar navbar-expand navbar-custom">
    <div class="container-fluid">

        <!-- ===================== LEFT SIDE ===================== -->
        <div class="d-flex align-items-center gap-3">
            <!-- Toggle Button (Mobile) -->
            <button class="btn btn-sm btn-outline-secondary d-lg-none" 
                    id="sidebarToggleMobile" 
                    type="button"
                    aria-label="Toggle Sidebar">
                <i class="bi bi-list fs-4"></i>
            </button>

            <div>
                <h4 class="mb-0 fw-bold">
                    <i class="bi bi-droplet-fill text-success"></i>
                    Smart Irrigation
                </h4>
                <small class="text-muted">
                    <i class="bi bi-diagram-3"></i>
                    Sistem Monitoring & Kontrol Irigasi
                </small>
            </div>
        </div>

        <!-- ===================== RIGHT SIDE ===================== -->
        <div class="text-end">
            <div class="fw-semibold">
                <i class="bi bi-person-circle"></i>
                <?= session()->get('nama') ?? 'Admin' ?>
            </div>
            <small class="text-muted">
                <i class="bi bi-shield-check"></i>
                Administrator
            </small>
        </div>

    </div>
</nav>