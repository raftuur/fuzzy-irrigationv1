<div class="row g-3 mb-4">

    <!-- ============================================================ -->
    <!-- CARD 1: ESP32 TERHUBUNG -->
    <!-- ============================================================ -->
    <div class="col-lg-3 col-md-6">
        <div class="card dashboard-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">
                            <i class="bi bi-cpu"></i> ESP32 Terhubung
                        </div>
                        <h2 class="mt-2 mb-0 fw-bold"><?= $deviceOnline ?? 0 ?></h2>
                    </div>
                    <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                        <i class="bi bi-cpu-fill fs-1 text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- CARD 2: TOTAL MONITORING -->
    <!-- ============================================================ -->
    <div class="col-lg-3 col-md-6">
        <div class="card dashboard-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">
                            <i class="bi bi-bar-chart-line"></i> Total Monitoring
                        </div>
                        <h2 class="mt-2 mb-0 fw-bold"><?= $monitoringHariIni ?? 0 ?></h2>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                        <i class="bi bi-bar-chart-line-fill fs-1 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- CARD 3: TOTAL PENYIRAMAN -->
    <!-- ============================================================ -->
    <div class="col-lg-3 col-md-6">
        <div class="card dashboard-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">
                            <i class="bi bi-droplet"></i> Total Penyiraman
                        </div>
                        <h2 class="mt-2 mb-0 fw-bold"><?= $penyiramanHariIni ?? 0 ?></h2>
                    </div>
                    <div class="bg-info bg-opacity-10 p-3 rounded-circle">
                        <i class="bi bi-droplet-fill fs-1 text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- CARD 4: AKTIVITAS SISTEM -->
    <!-- ============================================================ -->
    <div class="col-lg-3 col-md-6">
        <div class="card dashboard-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">
                            <i class="bi bi-activity"></i> Aktivitas Sistem
                        </div>
                        <h2 class="mt-2 mb-0 fw-bold"><?= $aktivitasAdmin ?? 0 ?></h2>
                    </div>
                    <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                        <i class="bi bi-person-fill fs-1 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>