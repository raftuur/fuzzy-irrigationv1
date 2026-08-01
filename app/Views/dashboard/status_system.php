<div class="card dashboard-card h-100">
    <div class="card-body">

        <!-- ===================== HEADER ===================== -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="fw-bold mb-1">
                    <i class="bi bi-hdd-stack"></i> Status Sistem
                </h5>
                <small class="text-muted">
                    Informasi kondisi sistem saat ini
                </small>
            </div>
            <span id="systemBadge" class="badge bg-success px-3 py-2">
                Online
            </span>
        </div>

        <!-- ===================== STATUS ITEMS ===================== -->
        <div class="status-item">
            <span class="status-label">
                <i class="bi bi-arrow-left-right"></i> Mode Sistem
            </span>
            <span id="mode" class="status-value fw-bold">
                -
            </span>
        </div>

        <div class="status-item">
            <span class="status-label">
                <i class="bi bi-water"></i> Status Pompa
            </span>
            <span id="pompa" class="status-value fw-bold">
                -
            </span>
        </div>

        <div class="status-item">
            <span class="status-label">
                <i class="bi bi-pin-map"></i> Zona Aktif
            </span>
            <span id="zona" class="status-value fw-bold">
                -
            </span>
        </div>

        <div class="status-item">
            <span class="status-label">
                <i class="bi bi-wifi"></i> Status ESP32
            </span>
            <span id="esp32Status" class="status-value text-success fw-bold">
                Menunggu...
            </span>
        </div>

        <div class="status-item border-0 pb-0">
            <span class="status-label">
                <i class="bi bi-clock"></i> Update Terakhir
            </span>
            <span id="lastUpdate" class="status-value">
                Menunggu data...
            </span>
        </div>

    </div>
</div>