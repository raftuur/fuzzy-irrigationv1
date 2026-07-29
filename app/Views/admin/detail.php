<?= $this->extend('layouts/template') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-6">
            <h4 class="page-header-title">
                Detail Admin
            </h4>
            <div class="small text-muted">
                Informasi Lengkap Administrator
            </div>
        </div>
        <div class="col-md-6 text-end">
            <a href="<?= site_url('admin') ?>" class="btn btn-secondary">
                <i class="icon ion-arrow-left-a"></i> Kembali
            </a>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th width="200">ID Admin</th>
                    <td><?= esc($admin['id_admin']) ?></td>
                </tr>
                <tr>
                    <th>Nama</th>
                    <td><?= esc($admin['nama']) ?></td>
                </tr>
                <tr>
                    <th>Username</th>
                    <td><?= esc($admin['username']) ?></td>
                </tr>
                <tr>
                    <th>Password</th>
                    <td><span class="text-muted">•••••••• (disembunyikan)</span></td>
                </tr>
                <?php if(isset($admin['created_at'])): ?>
                <tr>
                    <th>Dibuat Pada</th>
                    <td><?= esc($admin['created_at']) ?></td>
                </tr>
                <?php endif; ?>
                <?php if(isset($admin['updated_at'])): ?>
                <tr>
                    <th>Diperbarui Pada</th>
                    <td><?= esc($admin['updated_at']) ?></td>
                </tr>
                <?php endif; ?>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>