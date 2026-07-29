<?= $this->extend('layouts/template') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h4>Data Riwayat</h4>
</div>

<div class="row mb-4">

    <div class="col-md-3">
        <div class="card border-primary shadow-sm">
            <div class="card-body text-center">
                <h6 class="text-muted">Total Data</h6>
                <h2 class="text-primary">
                    <?= number_format($statistik['total_data']) ?>
                </h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-success shadow-sm">
            <div class="card-body text-center">
                <h6 class="text-muted">Total Penyiraman</h6>
                <h2 class="text-success">
                    <?= number_format($statistik['total_penyiraman']) ?>
                </h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-warning shadow-sm">
            <div class="card-body text-center">
                <h6 class="text-muted">Rata-rata Suhu</h6>
                <h2 class="text-warning">
                    <?= number_format($statistik['rata_suhu'],1) ?> °C
                </h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-info shadow-sm">
            <div class="card-body text-center">
                <h6 class="text-muted">Rata-rata RH</h6>
                <h2 class="text-info">
                    <?= number_format($statistik['rata_kelembapan'],1) ?> %
                </h2>
            </div>
        </div>
    </div>

</div>

<div class="row mb-3">

    <div class="col-md-3">
        <div class="card border-danger shadow-sm">
            <div class="card-body text-center">
                <h6 class="text-muted">Total Durasi Penyiraman</h6>
                <h2 class="text-danger">
                    <?= number_format($statistik['total_durasi']) ?> s
                </h2>
            </div>
        </div>
    </div>

</div>

<div class="card mb-4">

    <div class="card-header bg-primary text-white">

        <i class="bi bi-bar-chart-line"></i>

        Grafik Monitoring Sensor

    </div>

    <div class="card-body">

        <canvas id="grafikSensor" height="100"></canvas>

    </div>

</div>

<div class="card mb-3">

    <div class="card-body">

        <form method="get">

            <div class="row">

                <div class="col-md-3">

                    <label class="form-label fw-bold">
                        Tanggal Awal
                    </label>

                    <input
                        type="date"
                        name="tanggal_awal"
                        class="form-control"
                        value="<?= esc($tanggal_awal ?? '') ?>">

                </div>

                <div class="col-md-3">

                    <label class="form-label fw-bold">
                        Tanggal Akhir
                    </label>

                    <input
                        type="date"
                        name="tanggal_akhir"
                        class="form-control"
                        value="<?= esc($tanggal_akhir ?? '') ?>">

                </div>

                <div class="col-md-2">

                    <label class="form-label fw-bold">
                        Mode
                    </label>

                    <select
                        name="mode"
                        class="form-select">

                        <option value="">Semua</option>

                        <option value="AUTO"
                            <?= ($mode ?? '')=='AUTO'?'selected':'' ?>>
                            AUTO
                        </option>

                        <option value="MANUAL"
                            <?= ($mode ?? '')=='MANUAL'?'selected':'' ?>>
                            MANUAL
                        </option>

                    </select>

                </div>

                <div class="col-md-2">

                    <label class="form-label fw-bold">
                        Zona
                    </label>

                    <select
                        name="zona"
                        class="form-select">

                        <option value="">Semua</option>

                        <option value="A" <?= ($zona ?? '')=='A'?'selected':'' ?>>A</option>
                        <option value="B" <?= ($zona ?? '')=='B'?'selected':'' ?>>B</option>
                        <option value="C" <?= ($zona ?? '')=='C'?'selected':'' ?>>C</option>
                        <option value="D" <?= ($zona ?? '')=='D'?'selected':'' ?>>D</option>

                    </select>

                </div>

                <div class="col-md-2 mt-3">

                    <label class="form-label fw-bold">
                        Status Hujan
                    </label>

                    <select
                        name="status_hujan"
                        class="form-select">

                        <option value="">Semua</option>

                        <option value="Hujan"
                            <?= ($status_hujan ?? '')=='Hujan'?'selected':'' ?>>
                            Hujan
                        </option>

                        <option value="Tidak Hujan"
                            <?= ($status_hujan ?? '')=='Tidak Hujan'?'selected':'' ?>>
                            Tidak Hujan
                        </option>

                    </select>

                </div>

            </div>

            <div class="mt-4">

                <button class="btn btn-primary">

                    <i class="bi bi-funnel-fill"></i>

                    Terapkan Filter

                </button>

                <a href="<?= site_url('riwayat') ?>"
                    class="btn btn-secondary">

                    Reset

                </a>

            </div>

        </form>

    </div>

</div>

<div class="card">

    <div class="card-body">

        <table class="table table-bordered table-striped" id="datatable">

            <thead>

            <tr>

                <th>No</th>

                <th>Tanggal</th>

                <th>Suhu</th>

                <th>Kelembapan</th>

                <th>Tanah A</th>

                <th>Tanah B</th>

                <th>Tanah C</th>

                <th>Tanah D</th>

                <th>Status Hujan</th>

                <th>Mode</th>

                <th>Zona</th>

                <th>Durasi</th>

            </tr>

            </thead>

            <tbody>

            <?php $no = 1; ?>

            <?php foreach($riwayat as $row): ?>

                <tr>

                    <td><?= $no++ ?></td>

                    <td><?= esc($row['tanggal']) ?></td>

                    <td><?= esc($row['suhu']) ?> °C</td>

                    <td><?= esc($row['kelembapan']) ?> %</td>

                    <td><?= esc($row['tanah_a']) ?> %</td>

                    <td><?= esc($row['tanah_b']) ?> %</td>

                    <td><?= esc($row['tanah_c']) ?> %</td>

                    <td><?= esc($row['tanah_d']) ?> %</td>

                    <td>

                        <?php if(strtolower($row['status_hujan']) == 'hujan'): ?>

                            <span class="badge bg-primary">Hujan</span>

                        <?php else: ?>

                            <span class="badge bg-success">Tidak Hujan</span>

                        <?php endif; ?>

                    </td>

                    <td>

                        <?php if($row['mode']=='AUTO'): ?>

                            <span class="badge bg-success">AUTO</span>

                        <?php else: ?>

                            <span class="badge bg-warning text-dark">MANUAL</span>

                        <?php endif; ?>

                    </td>

                    <td>

                        <span class="badge bg-dark">

                            <?= esc($row['zona']) ?>

                        </span>

                    </td>

                    <td>

                        <span class="badge bg-danger">

                            <?= esc($row['durasi_penyiraman']) ?> Detik

                        </span>

                    </td>

                </tr>

            <?php endforeach ?>

            </tbody>

        </table>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const label = [

<?php foreach($grafik as $g): ?>

'<?= date('H:i',strtotime($g['tanggal'])) ?>',

<?php endforeach; ?>

];

const suhu=[

<?php foreach($grafik as $g): ?>

<?= $g['suhu'] ?>,

<?php endforeach; ?>

];

const kelembapan=[

<?php foreach($grafik as $g): ?>

<?= $g['kelembapan'] ?>,

<?php endforeach; ?>

];

new Chart(document.getElementById('grafikSensor'),{

type:'line',

data:{

labels:label,

datasets:[

{

label:'Suhu',

data:suhu,

borderWidth:3,

tension:0.3

},

{

label:'Kelembapan',

data:kelembapan,

borderWidth:3,

tension:0.3

}

]

},

options:{

responsive:true,

plugins:{

legend:{

position:'top'

}

}

}

});

</script>

<?= $this->endSection() ?>