<?= $this->extend('layouts/template') ?>

<?= $this->section('content') ?>

<?= $this->include('dashboard/header') ?>

<?= $this->include('dashboard/summary_cards') ?>

<?= $this->include('dashboard/control_panel') ?>

<?= $this->include('dashboard/sensor_cards') ?>

<?= $this->include('dashboard/soil_monitor') ?>

<div class="row mt-4">

    <div class="col-12">
        <?= $this->include('dashboard/status_system') ?>
    </div>

</div>

<?= $this->include('dashboard/footer') ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<?= $this->include('dashboard/scripts') ?>

<?= $this->endSection() ?>