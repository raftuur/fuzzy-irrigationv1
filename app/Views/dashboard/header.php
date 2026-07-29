<div class="dashboard-header">

    <div>

        <div class="dashboard-title">
            Smart Irrigation Monitoring System
        </div>

        <div class="dashboard-subtitle">

            Sistem Penyiram Tanaman Berbasis Fuzzy Logic

        </div>

        <div class="mt-2 text-muted" style="font-size:13px;">

            <i class="bi bi-calendar-event"></i>

            <?= date('l, d F Y') ?>

            &nbsp;&nbsp;

            <i class="bi bi-clock"></i>

            <span id="clock"><?= date('H:i:s') ?></span>

        </div>

    </div>

    <div class="text-end">

        <span class="status-chip online" id="statusChip">

            <span class="dot"></span>

            <span id="statusText">Device Online</span>

        </span>

        <div class="mt-2" style="font-size:13px;color:#64748B;">

            <i class="bi bi-arrow-repeat"></i>

            Last Update :

            <span id="lastUpdate">--</span>

        </div>

        <div style="font-size:13px;color:#64748B;">

            <i class="bi bi-cpu"></i>

            Device :

            <strong>ESP32-001</strong>

        </div>

    </div>

</div>

<script>

setInterval(function(){

    const now = new Date();

    document.getElementById('clock').innerHTML =
        now.toLocaleTimeString('id-ID');

},1000);

</script>