<?= $this->extend('layouts/template') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-6">
            <h4 class="page-header-title">Tambah Data Admin</h4>
            <div class="small text-muted">Silakan isi data administrator</div>
        </div>
    </div>
</div>

<div class="container-fluid">

    <div class="card">

        <div class="card-header">
            <a href="<?= site_url('admin') ?>" class="btn btn-secondary">
                Kembali
            </a>
        </div>

        <div class="card-body">

            <?php if(session()->get('errors')) : ?>

                <div class="alert alert-danger">

                    <ul class="mb-0">

                        <?php foreach(session('errors') as $error): ?>

                            <li><?= esc($error) ?></li>

                        <?php endforeach; ?>

                    </ul>

                </div>

            <?php endif; ?>

            <form action="<?= site_url('admin/store') ?>" method="post">

                <?= csrf_field() ?>

                <div class="mb-3">

                    <label>Nama</label>

                    <input
                        type="text"
                        name="nama"
                        class="form-control"
                        value="<?= old('nama') ?>">

                </div>

                <div class="mb-3">

                    <label>Username</label>

                    <input
                        type="text"
                        name="username"
                        class="form-control"
                        value="<?= old('username') ?>">

                </div>

                <div class="mb-3">

                    <label>Password</label>

                    <input
                        type="password"
                        name="password"
                        class="form-control">

                </div>

                <button class="btn btn-success">
                    Simpan Data
                </button>

            </form>

        </div>

    </div>

</div>

<?= $this->endSection() ?>