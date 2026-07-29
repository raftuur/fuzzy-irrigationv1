<div class="row g-4 mb-4">

    <!-- ====================== -->
    <!-- SUHU -->
    <!-- ====================== -->

    <div class="col-lg-4">

        <div class="card sensor-card h-100 shadow-sm">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <div class="sensor-label">

                            <i class="bi bi-thermometer-half text-danger"></i>

                            Suhu Udara

                        </div>

                    </div>

                    <span
                        id="badgeSuhu"
                        class="badge bg-success">

                        Normal

                    </span>

                </div>

                <div class="sensor-number mt-3">

                    <span id="suhu">0</span>

                    <small>°C</small>

                </div>

                <div class="progress mt-3" style="height:8px;">

                    <div
                        id="progressSuhu"
                        class="progress-bar bg-danger"
                        style="width:0%">

                    </div>

                </div>

                <div class="sensor-status mt-2">

                    Realtime Monitoring

                </div>

            </div>

        </div>

    </div>

    <!-- ====================== -->
    <!-- KELEMBAPAN -->
    <!-- ====================== -->

    <div class="col-lg-4">

        <div class="card sensor-card h-100 shadow-sm">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div class="sensor-label">

                        <i class="bi bi-droplet-fill text-primary"></i>

                        Kelembapan Udara

                    </div>

                    <span
                        id="badgeKelembapan"
                        class="badge bg-success">

                        Optimal

                    </span>

                </div>

                <div class="sensor-number mt-3">

                    <span id="kelembapan">0</span>

                    <small>%</small>

                </div>

                <div class="progress mt-3" style="height:8px;">

                    <div
                        id="progressKelembapan"
                        class="progress-bar bg-primary"
                        style="width:0%">

                    </div>

                </div>

                <div class="sensor-status mt-2">

                    Realtime Monitoring

                </div>

            </div>

        </div>

    </div>

    <!-- ====================== -->
    <!-- HUJAN -->
    <!-- ====================== -->

    <div class="col-lg-4">

        <div class="card sensor-card h-100 shadow-sm">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div class="sensor-label">

                        <i class="bi bi-cloud-rain-fill text-info"></i>

                        Status Cuaca

                    </div>

                    <span
                        id="badgeCuaca"
                        class="badge bg-primary">

                        Monitoring

                    </span>

                </div>

                <div
                    id="hujan"
                    class="display-6 fw-bold mt-3 text-primary">

                    CERAH

                </div>

                <div class="sensor-status mt-3">

                    Sensor Hujan

                </div>

            </div>

        </div>

    </div>

</div>