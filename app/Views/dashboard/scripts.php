<script>

let chart = null;

/* ===========================
   Counter Animation
=========================== */
function counter(id, value) {
    const el = document.getElementById(id);
    if (!el) return;
    el.innerHTML = value;
}

/* ===========================
   Set Manual Control
=========================== */
function setManualControl(enabled){
    document.getElementById("zonaSelect").disabled = !enabled;
    document.getElementById("btnPompaOn").disabled = !enabled;
    document.getElementById("btnPompaOff").disabled = !enabled;
}

/* ===========================
   Soil Monitor
=========================== */
function updateSoil(zona, nilai) {
    const bar = document.getElementById("pb" + zona);
    const text = document.getElementById("nilai" + zona);
    const status = document.getElementById("status" + zona);

    if (!bar) return;

    nilai = Number(nilai);

    bar.style.width = nilai + "%";
    text.innerHTML = nilai + "%";

    if (nilai < 30) {
        bar.style.background = "#ef4444";
        status.innerHTML = "Kering";
    } else if (nilai < 50) {
        bar.style.background = "#f59e0b";
        status.innerHTML = "Perlu Penyiraman";
    } else if (nilai < 80) {
        bar.style.background = "#22c55e";
        status.innerHTML = "Optimal";
    } else {
        bar.style.background = "#3b82f6";
        status.innerHTML = "Terlalu Basah";
    }
}

function updateSoilStatus(zona, nilai){
    const progress = document.getElementById("pb"+zona);
    const status = document.getElementById("status"+zona);
    const badge = document.getElementById("badge"+zona);

    progress.style.width = nilai + "%";

    if(nilai < 30){
        progress.className = "progress-bar bg-danger";
        status.innerHTML = "Tanah Sangat Kering";
        badge.className = "badge bg-danger";
        badge.innerHTML = "Kering";
    }
    else if(nilai < 60){
        progress.className = "progress-bar bg-warning";
        status.innerHTML = "Kelembapan Sedang";
        badge.className = "badge bg-warning text-dark";
        badge.innerHTML = "Sedang";
    }
    else{
        progress.className = "progress-bar bg-success";
        status.innerHTML = "Kondisi Optimal";
        badge.className = "badge bg-success";
        badge.innerHTML = "Basah";
    }
}

/* ===========================
   Simpan Kontrol
=========================== */
function simpanKontrol(data){
    fetch("<?= base_url('api/kontrol') ?>",{
        method:"POST",
        headers:{
            "Content-Type":"application/x-www-form-urlencoded"
        },
        body:new URLSearchParams(data)
    })
    .then(res=>res.json())
    .then(res=>{
        if(res.status==="success"){
            Swal.fire({
                icon:"success",
                title:"Berhasil",
                text:"Pengaturan berhasil disimpan",
                timer:1200,
                showConfirmButton:false
            });
            loadDashboard();
        }
    })
    .catch(err => {
        console.error('Error:', err);
        Swal.fire({
            icon:"error",
            title:"Gagal",
            text:"Terjadi kesalahan: " + err.message
        });
    });
}

/* ===========================
   Dashboard
=========================== */
function loadDashboard(){
    fetch("<?= base_url('api/dashboard') ?>")
    .then(res=>res.json())
    .then(data=>{
        const r = data.riwayat;
        const k = data.kontrol;
        const d = data.device;

        // ======================
        // UPDATE DEVICE INFO DARI API
        // ======================
        if (d) {
            if (document.getElementById("deviceName")) {
                document.getElementById("deviceName").textContent =
                    d.id_device ?? "ESP32-001";
            }

            if (document.getElementById("firmwareVersion")) {
                document.getElementById("firmwareVersion").textContent =
                    d.firmware ?? "v1.0";
            }
        }

        // ======================
        // VALIDASI DEVICE
        // ======================
        if (!d) return;

        // ======================
        // 1. STATUS ONLINE/OFFLINE
        // ======================
        const systemStatus = document.getElementById("systemStatus");
        const systemBadge = document.getElementById("systemBadge");
        const esp32Status = document.getElementById("esp32Status");

        if(d.online === true){
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

        // ======================
        // 2. TOMBOL AUTO/MANUAL DARI DEVICE
        // ======================
        if(!k) return;

        const btnAuto = document.getElementById("btnAuto");
        const btnManual = document.getElementById("btnManual");

        // ========== PERBAIKAN: Gunakan mode dari device, bukan kontrol ==========
        if(d.mode === "otomatis"){
            btnAuto.classList.add("active");
            btnManual.classList.remove("active");
            setManualControl(false);
        } else {
            btnManual.classList.add("active");
            btnAuto.classList.remove("active");
            setManualControl(true);
        }

        // ======================
        // 3. ZONA SELECT
        // ======================
        // ========== PERBAIKAN: Jika zona '-' atau null, tampilkan '-' ==========
        var zonaDisplay = (d.zona == '-' || d.zona == null) ? '-' : d.zona;
        document.getElementById("zonaSelect").value = (d.zona != '-' && d.zona != null) ? d.zona : 'A';

        // ======================
        // 4. STATUS DARI DEVICE
        // ======================
        if(d){
            // Mode dengan huruf kapital
            document.getElementById("mode").innerHTML =
                d.mode === "manual" ? "Manual" : "Otomatis";

            // Status Pompa dengan teks jelas
            document.getElementById("pompa").innerHTML =
                d.pompa === "on" ? "Menyala" : "Mati";

            // Zona dengan format "Zona A"
            var zonaTampil = (d.zona == '-' || d.zona == null) ? '-' : "Zona " + d.zona;
            document.getElementById("zona").innerHTML = zonaTampil;

            document.getElementById("zonaAktif").innerHTML = zonaTampil;

            document.getElementById("pompaStatus").innerHTML = (d.pompa === "on") ? "ON" : "OFF";
            document.getElementById("pompaStatus").className =
                d.pompa === "on" ? "badge bg-success" : "badge bg-danger";

            // Update tombol pompa
            const btnOn = document.getElementById("btnPompaOn");
            const btnOff = document.getElementById("btnPompaOff");

            btnOn.classList.remove("btn-active", "btn-inactive");
            btnOff.classList.remove("btn-active", "btn-inactive");

            if(d.pompa === "on"){
                btnOn.classList.add("btn-active");
                btnOff.classList.add("btn-inactive");
            } else {
                btnOff.classList.add("btn-active");
                btnOn.classList.add("btn-inactive");
            }

            // ======================
            // 5. LAST UPDATE DARI DEVICE
            // ======================
            document.getElementById("lastUpdate").innerHTML = d.last_update;
            document.getElementById("lastUpdatePanel").innerHTML = d.last_update;

            document.getElementById("lastUpdateSuhu").innerHTML = "Update: " + d.last_update;
            document.getElementById("lastUpdateKelembapan").innerHTML = "Update: " + d.last_update;
            document.getElementById("lastUpdateCuaca").innerHTML = "Update: " + d.last_update;
        }

        // ======================
        // 6. DATA RIWAYAT
        // ======================
        if(!r) return;

        document.getElementById("durasiPompa").innerHTML = r.durasi_penyiraman + " Detik";

        counter("suhu", r.suhu);
        counter("kelembapan", r.kelembapan);

        document.getElementById("hujan").innerHTML = r.status_hujan.toUpperCase();

        // Update badge cuaca
        const badgeCuaca = document.getElementById("badgeCuaca");
        if (r.status_hujan.toLowerCase() === "hujan") {
            badgeCuaca.className = "badge bg-info";
            badgeCuaca.innerHTML = "Hujan";
        } else {
            badgeCuaca.className = "badge bg-warning text-dark";
            badgeCuaca.innerHTML = "Cerah";
        }

        updateSoil("A", r.tanah_a);
        updateSoil("B", r.tanah_b);
        updateSoil("C", r.tanah_c);
        updateSoil("D", r.tanah_d);

        updateSoilStatus("A", r.tanah_a);
        updateSoilStatus("B", r.tanah_b);
        updateSoilStatus("C", r.tanah_c);
        updateSoilStatus("D", r.tanah_d);

        const avg = Math.round(
            (Number(r.tanah_a) + Number(r.tanah_b) + Number(r.tanah_c) + Number(r.tanah_d)) / 4
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

/* ===========================
   Event
=========================== */

// Tombol AUTO
document.getElementById("btnAuto").onclick = function(){
    this.classList.add("active");
    document.getElementById("btnManual").classList.remove("active");
    setManualControl(false);

    simpanKontrol({
        mode : "otomatis",
        pompa : "off",
        zona : "A"
    });
};

// Tombol MANUAL
document.getElementById("btnManual").onclick = function(){
    this.classList.add("active");
    document.getElementById("btnAuto").classList.remove("active");
    setManualControl(true);

    var zona = document.getElementById("zonaSelect").value;
    
    simpanKontrol({
        mode : "manual",
        pompa : "off",
        zona : zona
    });
};

// Tombol Pompa ON
document.getElementById("btnPompaOn").onclick = function(){
    var zona = document.getElementById("zonaSelect").value;
    
    // Pastikan mode manual
    document.getElementById("btnManual").click();
    
    simpanKontrol({
        mode : "manual",
        pompa : "on",
        zona : zona
    });
};

// Tombol Pompa OFF
document.getElementById("btnPompaOff").onclick = function(){
    var zona = document.getElementById("zonaSelect").value;
    
    simpanKontrol({
        mode : "manual",
        pompa : "off",
        zona : zona
    });
};

// Zona Select - Update saat manual
document.getElementById("zonaSelect").onchange = function(){
    var zona = this.value;
    var btnManual = document.getElementById("btnManual");
    
    if (btnManual.classList.contains("active")) {
        // Jika mode manual, update zona
        var statusPompa = document.getElementById("pompaStatus");
        var pompaStatus = statusPompa ? (statusPompa.innerHTML === "ON" ? "on" : "off") : "off";
        
        simpanKontrol({
            mode : "manual",
            pompa : pompaStatus,
            zona : zona
        });
    } else {
        console.log("Zona dipilih (otomatis):", zona);
    }
};

// Load awal
loadDashboard();

// Auto refresh setiap 3 detik
setInterval(loadDashboard, 3000);

</script>