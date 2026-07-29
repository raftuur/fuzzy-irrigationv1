<?= $this->extend('layouts/template') ?>

<?= $this->section('content') ?>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <h3>
            <i class="fas fa-history text-primary"></i>
            Log Aktivitas
        </h3>

        <span class="badge bg-primary fs-6">
            Total Log :
            <?= count($log) ?>
        </span>

    </div>

    <div class="card shadow">

        <div class="card-header bg-primary text-white">

            <i class="fas fa-clipboard-list"></i>

            Riwayat Aktivitas Administrator

        </div>

        <div class="card-body table-responsive">

            <table class="table table-bordered table-hover table-striped">

                <thead class="table-dark">

                    <tr>

                        <th width="60">No</th>

                        <th width="170">Waktu</th>

                        <th width="120">Admin</th>

                        <th>Aktivitas</th>

                        <th width="120">Device</th>

                        <th width="150">IP Address</th>

                    </tr>

                </thead>

                <tbody>

                <?php if(empty($log)): ?>

                    <tr>

                        <td colspan="6" class="text-center text-muted">

                            Belum ada aktivitas.

                        </td>

                    </tr>

                <?php else: ?>

                    <?php $no=1; ?>

                    <?php foreach($log as $item): ?>

                        <tr>

                            <td><?= $no++ ?></td>

                            <td>

                                <i class="far fa-clock text-secondary"></i>

                                <?= date('d-m-Y H:i:s', strtotime($item['created_at'])) ?>

                            </td>

                            <td>

                                <span class="badge bg-info">

                                    <?= esc($item['username']) ?>

                                </span>

                            </td>

                            <td>

                                <?php

                                $icon = 'fa-info-circle';
                                $color = 'secondary';

                                if(stripos($item['aktivitas'],'login') !== false){
                                    $icon='fa-sign-in-alt';
                                    $color='success';
                                }

                                elseif(stripos($item['aktivitas'],'logout') !== false){
                                    $icon='fa-sign-out-alt';
                                    $color='danger';
                                }

                                elseif(stripos($item['aktivitas'],'konfigurasi') !== false){
                                    $icon='fa-cogs';
                                    $color='warning';
                                }

                                elseif(stripos($item['aktivitas'],'device') !== false){
                                    $icon='fa-microchip';
                                    $color='primary';
                                }

                                ?>

                                <span class="badge bg-<?= $color ?>">

                                    <i class="fas <?= $icon ?>"></i>

                                </span>

                                <?= esc($item['aktivitas']) ?>

                            </td>

                            <td>

                                <?php if(empty($item['device_id'])): ?>

                                    -

                                <?php else: ?>

                                    <span class="badge bg-dark">

                                        <?= esc($item['device_id']) ?>

                                    </span>

                                <?php endif; ?>

                            </td>

                            <td>

                                <?= esc($item['ip_address']) ?>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?= $this->endSection() ?>