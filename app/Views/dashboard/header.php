<!-- ============================================================ -->
<!-- DASHBOARD HEADER -->
<!-- ============================================================ -->
<div class="dashboard-header">

    <!-- ===================== LEFT SIDE ===================== -->
    <div>
        <div class="dashboard-title">
            <i class="bi bi-house-door-fill text-primary"></i>
            Smart Irrigation Monitoring System
        </div>
        <div class="dashboard-subtitle">
            Monitoring dan Kontrol Irigasi Berbasis ESP32 &amp; Fuzzy Logic
        </div>
        <div class="mt-2 text-muted small">
            <i class="bi bi-calendar-event"></i>
            <?= date('l, d F Y') ?>
            &nbsp;&nbsp;
            <i class="bi bi-clock"></i>
            <span id="clock"><?= date('H:i:s') ?></span>
        </div>
    </div>

    <!-- ===================== RIGHT SIDE ===================== -->
    <div class="text-end">
        <!-- Status Chip -->
        <span class="status-chip online" id="statusChip">
            <span class="dot"></span>
            <span id="statusText">ESP32 Online</span>
        </span>

        <!-- Last Update -->
        <div class="mt-2 small text-secondary">
            <i class="bi bi-arrow-repeat"></i>
            Last Update :
            <span id="lastUpdate">--</span>
        </div>

        <!-- Device Info -->
        <div class="small text-secondary">
            <i class="bi bi-cpu"></i>
            Node :
            <strong id="deviceName">ESP32-001</strong>
        </div>
    </div>

</div>

<!-- ============================================================ -->
<!-- CLOCK SCRIPT -->
<!-- ============================================================ -->
<script>
// Update clock setiap detik
setInterval(function() {
    const now = new Date();
    const clock = document.getElementById('clock');
    if (clock) {
        clock.innerHTML = now.toLocaleTimeString('id-ID');
    }
}, 1000);
</script>