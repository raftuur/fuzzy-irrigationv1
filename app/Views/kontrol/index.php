<?= $this->extend('layouts/template') ?>

<?= $this->section('content') ?>

<div class="container-fluid">

    <h3 class="mb-4">
        <i class="fas fa-sliders-h"></i>
        Kontrol & Konfigurasi Sistem
    </h3>

    <!-- Tampilkan Notifikasi Flash Message -->
    <?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <!-- Form dengan method POST ke kontrol/save -->
    <form method="post" action="<?= site_url('kontrol/save') ?>">
        <?= csrf_field(); ?>

        <!-- Card 1: Kontrol Penyiraman -->
        <div class="card">

            <div class="card-header bg-primary text-white">
                <i class="fas fa-tint"></i> Kontrol Penyiraman
            </div>

            <div class="card-body">

                <div class="mb-3">
                    <label>Mode</label>
                    <select class="form-control" name="mode">
                        <option value="otomatis" <?= ($kontrol['mode'] ?? 'otomatis') == 'otomatis' ? 'selected' : '' ?>>
                            Otomatis
                        </option>
                        <option value="manual" <?= ($kontrol['mode'] ?? 'otomatis') == 'manual' ? 'selected' : '' ?>>
                            Manual
                        </option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Pompa</label>
                    <select class="form-control" name="pompa">
                        <option value="off" <?= ($kontrol['pompa'] ?? 'off') == 'off' ? 'selected' : '' ?>>
                            OFF
                        </option>
                        <option value="on" <?= ($kontrol['pompa'] ?? 'off') == 'on' ? 'selected' : '' ?>>
                            ON
                        </option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Zona</label>
                    <select class="form-control" name="zona">
                        <option value="A" <?= ($kontrol['zona'] ?? 'A') == 'A' ? 'selected' : '' ?>>A</option>
                        <option value="B" <?= ($kontrol['zona'] ?? 'A') == 'B' ? 'selected' : '' ?>>B</option>
                        <option value="C" <?= ($kontrol['zona'] ?? 'A') == 'C' ? 'selected' : '' ?>>C</option>
                        <option value="D" <?= ($kontrol['zona'] ?? 'A') == 'D' ? 'selected' : '' ?>>D</option>
                    </select>
                </div>

            </div>

        </div>

        <!-- Card 2: Kalibrasi Sensor Tanah -->
        <div class="card mt-4">

            <div class="card-header bg-success text-white">
                <i class="fas fa-seedling"></i> Kalibrasi Sensor Tanah
            </div>

            <div class="card-body">

                <table class="table table-bordered">

                    <thead>
                        <tr>
                            <th>Zona</th>
                            <th>Nilai Kering</th>
                            <th>Nilai Basah</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach(['a','b','c','d'] as $z): ?>
                        <tr>
                            <td><?= strtoupper($z) ?></td>
                            <td>
                                <input
                                    type="number"
                                    class="form-control"
                                    name="dry_<?= $z ?>"
                                    value="<?= esc($setting['dry_'.$z] ?? 4095) ?>">
                            </td>
                            <td>
                                <input
                                    type="number"
                                    class="form-control"
                                    name="wet_<?= $z ?>"
                                    value="<?= esc($setting['wet_'.$z] ?? 3400) ?>">
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>

            </div>

        </div>

        <!-- Card 3: Kalibrasi Sensor Hujan -->
        <div class="card mt-4">

            <div class="card-header bg-info text-white">
                <i class="fas fa-cloud-rain"></i> Kalibrasi Sensor Hujan
            </div>

            <div class="card-body">

                <label>Threshold Sensor Hujan (ADC)</label>
                <input
                    type="number"
                    class="form-control"
                    name="rain_threshold"
                    value="<?= esc($setting['rain_threshold'] ?? 1000) ?>">

            </div>

        </div>

        <!-- Card 4: Parameter Fuzzy -->
        <div class="card mt-4">

            <div class="card-header bg-warning">
                <i class="fas fa-cogs"></i> Parameter Fuzzy
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4">
                        <label>Tanah Kering (%)</label>
                        <input
                            type="number"
                            class="form-control"
                            name="soil_dry"
                            value="<?= esc($setting['soil_dry'] ?? 30) ?>">
                    </div>

                    <div class="col-md-4">
                        <label>Tanah Lembap (%)</label>
                        <input
                            type="number"
                            class="form-control"
                            name="soil_moist"
                            value="<?= esc($setting['soil_moist'] ?? 70) ?>">
                    </div>

                    <div class="col-md-4">
                        <label>Durasi Maksimum (detik)</label>
                        <input
                            type="number"
                            class="form-control"
                            name="max_duration"
                            value="<?= esc($setting['max_duration'] ?? 10) ?>">
                    </div>

                </div>

                <div class="row mt-3">

                    <div class="col-md-6">
                        <label>Suhu Normal (°C)</label>
                        <input
                            type="number"
                            class="form-control"
                            name="temp_normal"
                            value="<?= esc($setting['temp_normal'] ?? 25) ?>">
                    </div>

                    <div class="col-md-6">
                        <label>Suhu Panas (°C)</label>
                        <input
                            type="number"
                            class="form-control"
                            name="temp_hot"
                            value="<?= esc($setting['temp_hot'] ?? 30) ?>">
                    </div>

                </div>

            </div>

        </div>

        <!-- Card 5: Informasi ESP32 -->
        <div class="card mt-4 mb-4">

            <div class="card-header bg-dark text-white">
                <i class="fas fa-microchip"></i> Informasi ESP32
            </div>

            <div class="card-body">

                <table class="table table-striped">

                    <tr>
                        <td><i class="fas fa-circle text-success"></i> Status</td>
                        <td><span class="badge bg-success">Online</span></td>
                    </tr>

                    <tr>
                        <td><i class="fas fa-code"></i> Firmware</td>
                        <td>1.0</td>
                    </tr>

                    <tr>
                        <td><i class="fas fa-clock"></i> Update Terakhir</td>
                        <td><?= date('d-m-Y H:i:s') ?></td>
                    </tr>

                </table>

            </div>

        </div>

        <!-- Tombol Simpan Semua Pengaturan -->
        <div class="text-end mt-4">
            <button type="submit" class="btn btn-success btn-lg">
                <i class="fas fa-save"></i>
                Simpan Semua Pengaturan
            </button>
        </div>

    </form>

</div>

<?= $this->endSection() ?>