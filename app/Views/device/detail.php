<?= $this->extend('layouts/template') ?>

<?= $this->section('content') ?>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <h3>
            <i class="fas fa-microchip text-primary"></i>
            Detail Device
        </h3>

        <a href="<?= base_url('device') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Kembali
        </a>

    </div>

    <!-- HEADER DEVICE -->

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <div class="row align-items-center">

                <div class="col-md-8">

                    <h3 class="mb-1">

                        <?= esc($device['id_device']) ?>

                    </h3>

                    <h5 class="text-muted">

                        <?= esc($device['nama_device']) ?>

                    </h5>

                    <p class="mb-0">

                        <i class="fas fa-map-marker-alt text-danger"></i>

                        <?= esc($device['lokasi']) ?>

                    </p>

                </div>

                <div class="col-md-4 text-md-end">

                    <?php if($device['status']=="Online"): ?>

                        <span class="badge bg-success p-2">

                            <i class="fas fa-circle"></i>

                            ONLINE

                        </span>

                    <?php else: ?>

                        <span class="badge bg-danger p-2">

                            <i class="fas fa-circle"></i>

                            OFFLINE

                        </span>

                    <?php endif; ?>

                    <div class="mt-2">

                        <small>

                            Last Update

                        </small>

                        <br>

                        <strong>

                            <?= waktuLalu($device['last_update']) ?>

                        </strong>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- STATUS CARD -->

    <div class="row">

        <div class="col-md-3">

            <div class="small-box bg-info">

                <div class="inner">

                    <h3><?= esc($sensor['suhu']) ?>°C</h3>

                    <p>Suhu</p>

                </div>

                <div class="icon">

                    <i class="fas fa-temperature-high"></i>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="small-box bg-primary">

                <div class="inner">

                    <h3><?= esc($sensor['kelembapan']) ?>%</h3>

                    <p>Humidity</p>

                </div>

                <div class="icon">

                    <i class="fas fa-tint"></i>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="small-box bg-warning">

                <div class="inner">

                    <h3><?= esc($sensor['status_hujan']) ?></h3>

                    <p>Status Hujan</p>

                </div>

                <div class="icon">

                    <i class="fas fa-cloud-rain"></i>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="small-box <?= ($kontrol['pompa']=="on") ? "bg-success" : "bg-danger" ?>">

                <div class="inner">

                    <h3><?= strtoupper($kontrol['pompa']) ?></h3>

                    <p>Pompa</p>

                </div>

                <div class="icon">

                    <i class="fas fa-faucet"></i>

                </div>

            </div>

        </div>

    </div>


    <!-- SOIL -->

    <div class="card shadow">

        <div class="card-header bg-success">

            <h5 class="mb-0 text-white">

                <i class="fas fa-seedling"></i>

                Kelembapan Tanah

            </h5>

        </div>

        <div class="card-body">

            <?php

            $soil = [

                'Tanah A'=>$sensor['tanah_a'],

                'Tanah B'=>$sensor['tanah_b'],

                'Tanah C'=>$sensor['tanah_c'],

                'Tanah D'=>$sensor['tanah_d']

            ];

            ?>

            <?php foreach($soil as $nama=>$nilai): ?>

                <div class="mb-3">

                    <div class="d-flex justify-content-between">

                        <strong><?= $nama ?></strong>

                        <strong><?= $nilai ?>%</strong>

                    </div>

                    <div class="progress">

                        <div class="progress-bar bg-success"

                             style="width:<?= $nilai ?>%">

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    </div>


    <!-- KONTROL -->

    <div class="card shadow mt-4">

        <div class="card-header bg-dark">

            <h5 class="text-white mb-0">

                <i class="fas fa-sliders-h"></i>

                Status Kontrol

            </h5>

        </div>

        <div class="card-body">

            <div class="row text-center">

                <div class="col-md-4">

                    <h6>Mode</h6>

                    <span class="badge bg-primary p-2">

                        <?= strtoupper($kontrol['mode']) ?>

                    </span>

                </div>

                <div class="col-md-4">

                    <h6>Zona</h6>

                    <span class="badge bg-success p-2">

                        <?= strtoupper($kontrol['zona']) ?>

                    </span>

                </div>

                <div class="col-md-4">

                    <h6>Durasi</h6>

                    <span class="badge bg-warning text-dark p-2">

                        <?= esc($sensor['durasi_penyiraman']) ?> Detik

                    </span>

                </div>

            </div>

        </div>

    </div>

</div>

<?= $this->endSection() ?>