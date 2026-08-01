<script>
/* ============================================================
   SMART IRRIGATION DASHBOARD - JavaScript
   ============================================================ */

/* ============================================================
   1. SET MANUAL CONTROL
   ============================================================ */
function setManualControl(enabled) {
    document.getElementById("zonaSelect").disabled = !enabled;
    document.getElementById("btnPompaOn").disabled = !enabled;
    document.getElementById("btnPompaOff").disabled = !enabled;
}

/* ============================================================
   2. UPDATE SOIL MONITOR
   ============================================================ */
function updateSoil(zona, nilai) {
    const bar = document.getElementById("pb" + zona);
    const text = document.getElementById("nilai" + zona);
    const status = document.getElementById("status" + zona);
    const badge = document.getElementById("badge" + zona);

    if (!bar) return;

    nilai = Number(nilai) || 0;

    bar.style.width = nilai + "%";
    text.innerHTML = nilai + "%";

    // Update warna dan status
    if (nilai < 30) {
        bar.style.background = "#ef4444";
        status.innerHTML = "Tanah Sangat Kering";
        badge.className = "badge bg-danger";
        badge.innerHTML = "Kering";
    } else if (nilai < 60) {
        bar.style.background = "#f59e0b";
        status.innerHTML = "Kelembapan Sedang";
        badge.className = "badge bg-warning text-dark";
        badge.innerHTML = "Sedang";
    } else {
        bar.style.background = "#22c55e";
        status.innerHTML = "Kondisi Optimal";
        badge.className = "badge bg-success";
        badge.innerHTML = "Basah";
    }
}

/* ============================================================
   3. SIMPAN KONTROL
   ============================================================ */
function simpanKontrol(data) {
    fetch("<?= base_url('api/kontrol') ?>", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: new URLSearchParams(data)
    })
    .then(res => res.json())
    .then(res => {
        if (res.status === "success") {
            Swal.fire({
                icon: "success",
                title: "Berhasil",
                text: "Pengaturan berhasil disimpan",
                timer: 1200,
                showConfirmButton: false
            });
            loadDashboard();
        }
    })
    .catch(err => {
        console.error('Error:', err);
        Swal.fire({
            icon: "error",
            title: "Gagal",
            text: "Terjadi kesalahan: " + err.message
        });
    });
}

/* ============================================================
   4. LOAD DASHBOARD
   ============================================================ */
function loadDashboard() {
    fetch("<?= base_url('api/dashboard') ?>")
    .then(res => res.json())
    .then(data => {
        const r = data.riwayat;
        const k = data.kontrol;
        const d = data.device;

        // ---------- UPDATE DEVICE INFO ----------
        if (d) {
            const deviceName = document.getElementById("deviceName");
            const firmwareVersion = document.getElementById("firmwareVersion");

            if (deviceName) deviceName.textContent = d.id_device ?? "ESP32-001";
            if (firmwareVersion) firmwareVersion.textContent = d.firmware ?? "v1.0";
        }

        if (!d) return;

        // ---------- STATUS ONLINE/OFFLINE ----------
        const systemStatus = document.getElementById("systemStatus");
        const systemBadge = document.getElementById("systemBadge");
        const esp32Status = document.getElementById("esp32Status");

        if (d.online === true) {
            systemStatus.innerHTML = "Online";
            systemStatus.className = "badge bg-success";
            systemBadge.innerHTML = "Online";
            systemBadge.className = "badge bg-success px-3 py-2";
            esp32Status.innerHTML = "Terhubung";
            esp32Status.className = "status-value text-success";
        } else {
            systemStatus.innerHTML = "Offline";
            systemStatus.className = "badge bg-danger";
            systemBadge.innerHTML = "Offline";
            systemBadge.className = "badge bg-danger px-3 py-2";
            esp32Status.innerHTML = "Terputus";
            esp32Status.className = "status-value text-danger";
        }

        // ---------- TOMBOL AUTO/MANUAL ----------
        if (!k) return;

        const btnAuto = document.getElementById("btnAuto");
        const btnManual = document.getElementById("btnManual");

        if (d.mode === "otomatis") {
            btnAuto.classList.add("active");
            btnManual.classList.remove("active");
            setManualControl(false);
        } else {
            btnManual.classList.add("active");
            btnAuto.classList.remove("active");
            setManualControl(true);
        }

        // ---------- ZONA SELECT ----------
        const zonaSelect = document.getElementById("zonaSelect");
        if (d.zona && d.zona !== '-' && d.zona !== null) {
            zonaSelect.value = d.zona;
        } else {
            zonaSelect.value = 'A';
        }

        // ---------- STATUS SISTEM ----------
        if (d) {
            document.getElementById("mode").innerHTML = d.mode === "manual" ? "Manual" : "Otomatis";
            document.getElementById("pompa").innerHTML = d.pompa === "on" ? "Menyala" : "Mati";

            const zonaTampil = (d.zona && d.zona !== '-' && d.zona !== null) ? "Zona " + d.zona : "-";
            document.getElementById("zona").innerHTML = zonaTampil;
            document.getElementById("zonaAktif").innerHTML = zonaTampil;

            // Status Pompa Badge
            const pompaStatus = document.getElementById("pompaStatus");
            pompaStatus.innerHTML = (d.pompa === "on") ? "ON" : "OFF";
            pompaStatus.className = d.pompa === "on" ? "badge bg-success" : "badge bg-danger";

            // Tombol Pompa
            const btnOn = document.getElementById("btnPompaOn");
            const btnOff = document.getElementById("btnPompaOff");
            btnOn.classList.remove("btn-active", "btn-inactive");
            btnOff.classList.remove("btn-active", "btn-inactive");

            if (d.pompa === "on") {
                btnOn.classList.add("btn-active");
                btnOff.classList.add("btn-inactive");
            } else {
                btnOff.classList.add("btn-active");
                btnOn.classList.add("btn-inactive");
            }

            // Last Update
            const lastUpdate = d.last_update || "-";
            document.getElementById("lastUpdate").innerHTML = lastUpdate;
            document.getElementById("lastUpdatePanel").innerHTML = lastUpdate;
            document.getElementById("lastUpdateSuhu").innerHTML = "Update: " + lastUpdate;
            document.getElementById("lastUpdateKelembapan").innerHTML = "Update: " + lastUpdate;
            document.getElementById("lastUpdateCuaca").innerHTML = "Update: " + lastUpdate;
        }

        // ---------- DATA RIWAYAT ----------
        if (!r) return;

        document.getElementById("durasiPompa").innerHTML = (r.durasi_penyiraman || 0) + " Detik";

        // Suhu & Kelembapan
        document.getElementById("suhu").innerHTML = r.suhu || 0;
        document.getElementById("kelembapan").innerHTML = r.kelembapan || 0;

        // Status Cuaca
        const statusHujan = r.status_hujan || "cerah";
        document.getElementById("hujan").innerHTML = statusHujan.toUpperCase();

        const badgeCuaca = document.getElementById("badgeCuaca");
        if (statusHujan.toLowerCase() === "hujan") {
            badgeCuaca.className = "badge bg-info";
            badgeCuaca.innerHTML = "Hujan";
        } else {
            badgeCuaca.className = "badge bg-warning text-dark";
            badgeCuaca.innerHTML = "Cerah";
        }

        // Update Soil (4 Zona)
        const zonaList = ['A', 'B', 'C', 'D'];
        zonaList.forEach(z => {
            const nilai = r['tanah_' + z.toLowerCase()] || 0;
            updateSoil(z, nilai);
        });

        // Rata-rata Soil
        const avg = Math.round(
            (Number(r.tanah_a || 0) + Number(r.tanah_b || 0) + 
             Number(r.tanah_c || 0) + Number(r.tanah_d || 0)) / 4
        );
        document.getElementById("avgSoil").innerHTML = avg + "%";

        // Badge Suhu
        const badgeSuhu = document.getElementById("badgeSuhu");
        const suhuValue = parseFloat(r.suhu) || 0;
        if (suhuValue < 25) {
            badgeSuhu.className = "badge bg-info";
            badgeSuhu.innerText = "Rendah";
        } else if (suhuValue <= 32) {
            badgeSuhu.className = "badge bg-success";
            badgeSuhu.innerText = "Normal";
        } else {
            badgeSuhu.className = "badge bg-danger";
            badgeSuhu.innerText = "Tinggi";
        }

        // Badge Kelembapan
        const badgeKelembapan = document.getElementById("badgeKelembapan");
        const kelembapanValue = parseFloat(r.kelembapan) || 0;
        if (kelembapanValue < 40) {
            badgeKelembapan.className = "badge bg-warning";
            badgeKelembapan.innerText = "Kering";
        } else if (kelembapanValue <= 80) {
            badgeKelembapan.className = "badge bg-success";
            badgeKelembapan.innerText = "Optimal";
        } else {
            badgeKelembapan.className = "badge bg-info";
            badgeKelembapan.innerText = "Lembap";
        }
    })
    .catch(err => {
        console.error('Error loading dashboard:', err);
    });
}

/* ============================================================
   5. EVENT HANDLER
   ============================================================ */

// Tombol AUTO
document.getElementById("btnAuto").onclick = function() {
    this.classList.add("active");
    document.getElementById("btnManual").classList.remove("active");
    setManualControl(false);

    simpanKontrol({
        mode: "otomatis",
        pompa: "off",
        zona: "-"
    });
};

// Tombol MANUAL
document.getElementById("btnManual").onclick = function() {
    this.classList.add("active");
    document.getElementById("btnAuto").classList.remove("active");
    setManualControl(true);

    const zona = document.getElementById("zonaSelect").value;
    simpanKontrol({
        mode: "manual",
        pompa: "off",
        zona: zona
    });
};

// Tombol Pompa ON
document.getElementById("btnPompaOn").onclick = function() {
    const zona = document.getElementById("zonaSelect").value;
    
    // Pastikan mode manual
    document.getElementById("btnManual").click();
    
    simpanKontrol({
        mode: "manual",
        pompa: "on",
        zona: zona
    });
};

// Tombol Pompa OFF
document.getElementById("btnPompaOff").onclick = function() {
    const zona = document.getElementById("zonaSelect").value;
    
    simpanKontrol({
        mode: "manual",
        pompa: "off",
        zona: zona
    });
};

// Zona Select - Update saat mode manual
document.getElementById("zonaSelect").onchange = function() {
    const zona = this.value;
    const btnManual = document.getElementById("btnManual");
    
    if (btnManual.classList.contains("active")) {
        const pompaStatus = document.getElementById("pompaStatus");
        const statusPompa = pompaStatus ? (pompaStatus.innerHTML === "ON" ? "on" : "off") : "off";
        
        simpanKontrol({
            mode: "manual",
            pompa: statusPompa,
            zona: zona
        });
    }
};

/* ============================================================
   6. START
   ============================================================ */
// Load awal
loadDashboard();

// Auto refresh setiap 3 detik
setInterval(loadDashboard, 3000);

</script>