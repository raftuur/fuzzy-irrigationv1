<?= $this->extend('layouts/template') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-6">
            <h4 class="page-header-title">
                Data Admin
            </h4>
            <div class="small text-muted">
                Daftar Administrator
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">

    <div class="card">

        <div class="card-header">

            <a href="<?= site_url('admin/create') ?>" class="btn btn-success">
                Tambah Data
            </a>

        </div>

        <div class="card-body">

            <table id="datatable" class="table table-bordered table-striped">

                <thead>

                    <tr>

                        <th width="60">No</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th width="180">Aksi</th>

                    </tr>

                </thead>

                <tbody>

                <?php if(empty($admins)): ?>

                    <tr>
                        <td colspan="4" class="text-center">
                            Tidak ada data
                        </td>
                    </tr>

                <?php else: ?>

                    <?php
                    $no = 1;
                    ?>

                    <?php foreach($admins as $admin): ?>

                    <tr>

                        <td><?= $no++ ?></td>

                        <td><?= esc($admin['nama']) ?></td>

                        <td><?= esc($admin['username']) ?></td>

                        <td>

                            <a href="<?= site_url('admin/detail/'.$admin['id_admin']) ?>" class="btn btn-info btn-sm">
                                Detail
                            </a>

                            <a href="<?= site_url('admin/edit/'.$admin['id_admin']) ?>" class="btn btn-warning btn-sm">
                                Edit
                            </a>

                            <a href="<?= site_url('admin/delete/'.$admin['id_admin']) ?>"
                               class="btn btn-danger btn-sm btn-delete">
                                Hapus
                            </a>

                        </td>

                    </tr>

                    <?php endforeach; ?>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<script>
document.querySelectorAll('.btn-delete').forEach(function(button){

    button.addEventListener('click', function(e){

        e.preventDefault();

        let url = this.href;

        Swal.fire({
            title: 'Yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#d33'
        }).then((result)=>{

            if(result.isConfirmed){
                window.location = url;
            }

        });

    });

});
</script>

<?= $this->endSection() ?>