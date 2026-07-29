<?= $this->extend('layouts/template') ?>

<?= $this->section('content') ?>

<div class="container-fluid">

    <h3 class="mb-4">
        <i class="fas fa-microchip"></i>
        Manajemen Device
    </h3>

    <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <div class="card shadow">

        <div class="card-body">

            <table class="table table-bordered table-hover">

                <thead class="table-dark">

                    <tr>
                        <th>ID Device</th>
                        <th>Nama Device</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                        <th>Firmware</th>
                        <th>IP Address</th>
                        <th>Last Update</th>
                        <th>Aksi</th>
                    </tr>

                </thead>

                <tbody>

                    <?php foreach($device as $d): ?>

                    <tr>
                        <td>
                            <a href="<?= base_url('device/'.$d['id_device']) ?>">
                                <?= esc($d['id_device']) ?>
                            </a>
                        </td>
                        <td><?= esc($d['nama_device']) ?></td>
                        <td><?= esc($d['lokasi']) ?></td>

                        <!-- ========================================== -->
                        <!-- BADGE STATUS DENGAN ICON -->
                        <!-- ========================================== -->
                        <td>
                            <?php if ($d['status'] == 'Online'): ?>
                                <span class="badge bg-success">
                                    <i class="fas fa-circle"></i> Online
                                </span>
                            <?php else: ?>
                                <span class="badge bg-danger">
                                    <i class="fas fa-circle"></i> Offline
                                </span>
                            <?php endif; ?>
                        </td>

                        <td><?= esc($d['firmware']) ?></td>
                        <td><?= esc($d['ip_address']) ?></td>

                        <!-- ========================================== -->
                        <!-- LAST UPDATE DENGAN FUNGSI waktuLalu() -->
                        <!-- ========================================== -->
                        <td><?= waktuLalu($d['last_update']) ?></td>

                        <td>
                            <a href="<?= site_url('device/detail/' . $d['id_device']) ?>" 
                               class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>

                    <?php endforeach; ?>

                    <?php if (empty($device)): ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="fas fa-microchip fa-2x d-block mb-2"></i>
                            Belum ada device yang terdaftar
                        </td>
                    </tr>
                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?= $this->endSection() ?>