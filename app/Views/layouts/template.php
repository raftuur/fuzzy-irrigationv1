<!doctype html>

<html lang="id">

<head>

<meta charset="utf-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title><?= esc($title ?? 'Smart Irrigation') ?></title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">

<link href="<?= base_url('assets/css/app.css') ?>" rel="stylesheet">

</head>

<body>

<div class="d-flex">

<?= $this->include('layouts/sidebar') ?>

<div class="main-content">

<?= $this->include('layouts/navbar') ?>

<div class="container-fluid p-4">

<?= $this->renderSection('content') ?>

</div>

<?= $this->include('layouts/footer') ?>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<?= $this->renderSection('scripts') ?>

</body>

</html>