<div class="row g-4 mb-4">

    <!-- ===================== -->
    <!-- MODE SISTEM -->
    <!-- ===================== -->

    <div class="col-lg-4">

        <div class="card dashboard-card h-100">

            <div class="card-body">

                <h5 class="section-title mb-4">

                    <i class="bi bi-cpu"></i>

                    Mode Sistem

                </h5>

                <div class="mode-wrapper">

                    <button
                        id="btnAuto"
                        class="btn btn-mode active">

                        AUTO

                    </button>

                    <button
                        id="btnManual"
                        class="btn btn-mode">

                        MANUAL

                    </button>

                </div>

                <hr>

                <div class="d-flex justify-content-between mt-3">

                    <span>Status Sistem</span>

                    <span
                        id="systemStatus"
                        class="badge bg-success">

                        Online

                    </span>

                </div>

                <div class="d-flex justify-content-between mt-2">

                    <span>Device</span>

                    <strong>ESP32-001</strong>

                </div>

                <div class="d-flex justify-content-between mt-2">

                    <span>Firmware</span>

                    <strong>v1.0</strong>

                </div>

            </div>

        </div>

    </div>

    <!-- ===================== -->
    <!-- KONTROL -->
    <!-- ===================== -->

    <div class="col-lg-4">

        <div class="card dashboard-card h-100">

            <div class="card-body">

                <h5 class="section-title mb-4">

                    <i class="bi bi-sliders"></i>

                    Kontrol Sistem

                </h5>

                <label class="form-label fw-semibold">

                    Pilih Zona Irigasi

                </label>

                <select
                    id="zonaSelect"
                    class="form-select mb-3">

                    <option value="A">Zona A</option>
                    <option value="B">Zona B</option>
                    <option value="C">Zona C</option>
                    <option value="D">Zona D</option>

                </select>

                <div class="d-grid gap-2">

                    <button
                        id="btnPompaOn"
                        class="btn btn-success">

                        <i class="bi bi-play-fill"></i>

                        Pompa ON

                    </button>

                    <button
                        id="btnPompaOff"
                        class="btn btn-danger">

                        <i class="bi bi-stop-fill"></i>

                        Pompa OFF

                    </button>

                </div>

            </div>

        </div>

    </div>

    <!-- ===================== -->
    <!-- STATUS -->
    <!-- ===================== -->

    <div class="col-lg-4">

        <div class="card dashboard-card h-100">

            <div class="card-body">

                <h5 class="section-title mb-4">

                    <i class="bi bi-info-circle"></i>

                    Status Penyiraman

                </h5>

                <table class="table table-borderless">

                    <tr>

                        <td>Zona Aktif</td>

                        <td class="text-end">

                            <span
                                id="zonaAktif"
                                class="badge bg-primary">

                                -

                            </span>

                        </td>

                    </tr>

                    <tr>

                        <td>Status Pompa</td>

                        <td class="text-end">

                            <span
                                id="pompaStatus"
                                class="badge bg-danger">

                                OFF

                            </span>

                        </td>

                    </tr>

                    <tr>

                        <td>Durasi</td>

                        <td class="text-end">

                            <span
                                id="durasiPompa">

                                0 Detik

                            </span>

                        </td>

                    </tr>

                    <tr>

                        <td>Last Update</td>

                        <td class="text-end">

                            <span
                                id="lastUpdatePanel">

                                --

                            </span>

                        </td>

                    </tr>

                </table>

            </div>

        </div>

    </div>

</div>