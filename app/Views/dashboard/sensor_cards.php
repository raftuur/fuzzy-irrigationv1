<div class="row g-4 mb-4">

    <!-- ============================================================ -->
    <!-- CARD 1: SUHU UDARA -->
    <!-- ============================================================ -->
    <div class="col-lg-4 col-md-6">
        <div class="card sensor-card h-100 shadow-sm">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">
                    <div class="sensor-label">
                        <i class="bi bi-thermometer-half text-danger"></i>
                        Suhu Udara
                    </div>
                    <span id="badgeSuhu" class="badge bg-success">Normal</span>
                </div>

                <div class="sensor-number mt-3">
                    <span id="suhu">0</span>
                    <small>°C</small>
                </div>

                <div id="lastUpdateSuhu" class="sensor-status mt-2">
                    <i class="bi bi-clock"></i> Menunggu data...
                </div>

            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- CARD 2: KELEMBAPAN UDARA -->
    <!-- ============================================================ -->
    <div class="col-lg-4 col-md-6">
        <div class="card sensor-card h-100 shadow-sm">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">
                    <div class="sensor-label">
                        <i class="bi bi-droplet-fill text-primary"></i>
                        Kelembapan Udara
                    </div>
                    <span id="badgeKelembapan" class="badge bg-success">Optimal</span>
                </div>

                <div class="sensor-number mt-3">
                    <span id="kelembapan">0</span>
                    <small>%</small>
                </div>

                <div id="lastUpdateKelembapan" class="sensor-status mt-2">
                    <i class="bi bi-clock"></i> Menunggu data...
                </div>

            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- CARD 3: STATUS CUACA -->
    <!-- ============================================================ -->
    <div class="col-lg-4 col-md-6">
        <div class="card sensor-card h-100 shadow-sm">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">
                    <div class="sensor-label">
                        <i class="bi bi-cloud-rain-fill text-info"></i>
                        Status Cuaca
                    </div>
                    <span id="badgeCuaca" class="badge bg-warning text-dark">Cerah</span>
                </div>

                <div id="hujan" class="display-6 fw-bold mt-3 text-primary">
                    CERAH
                </div>

                <div id="lastUpdateCuaca" class="sensor-status mt-3">
                    <i class="bi bi-clock"></i> Menunggu data...
                </div>

            </div>
        </div>
    </div>

</div>