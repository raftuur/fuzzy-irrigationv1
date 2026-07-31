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
    // VALIDASI DEVICE
    // ======================
    if (!d) return;

    // ======================
    // 1. STATUS ONLINE/OFFLINE
    // ======================
    const systemStatus = document.getElementById("systemStatus");
    const systemBadge = document.getElementById("systemBadge");
    const esp32Status = document.getElementById("esp32Status");

    if(d.status === "Online"){
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
    // 3. ZONA SELECT DARI DEVICE
    // ======================
    document.getElementById("zonaSelect").value = d.zona;

    // ======================
    // 4. STATUS DARI DEVICE
    // ======================
    if(d){
        document.getElementById("mode").innerHTML = d.mode;
        document.getElementById("pompa").innerHTML = d.pompa;
        document.getElementById("zona").innerHTML = d.zona;

        document.getElementById("zonaAktif").innerHTML = d.zona;

        document.getElementById("pompaStatus").innerHTML = d.pompa.toUpperCase();
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

        // Update last update untuk setiap card sensor
        document.getElementById("lastUpdateSuhu").innerHTML =
            "Update: " + d.last_update;

        document.getElementById("lastUpdateKelembapan").innerHTML =
            "Update: " + d.last_update;

        document.getElementById("lastUpdateCuaca").innerHTML =
            "Update: " + d.last_update;

        // ======================
        // 6. UPDATE DEVICE INFO DINAMIS
        // ======================
        document.getElementById("deviceName").innerHTML = d.id_device || "ESP32-001";
        document.getElementById("firmwareVersion").innerHTML = d.firmware || "v1.0";
    }

    // ======================
    // 7. DATA RIWAYAT
    // ======================
    if(!r) return;

    document.getElementById("durasiPompa").innerHTML = r.durasi_penyiraman + " Detik";

    counter("suhu", r.suhu);
    counter("kelembapan", r.kelembapan);

    document.getElementById("hujan").innerHTML = r.status_hujan.toUpperCase();

    // Update badge cuaca berdasarkan status hujan
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

    // Badge Suhu (tetap dipakai)
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

    // Badge Kelembapan (tetap dipakai)
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

});

}

/* ===========================
   Event
=========================== */

document.getElementById("btnAuto").onclick=function(){

    this.classList.add("active");
    document.getElementById("btnManual").classList.remove("active");

    simpanKontrol({

        mode:"otomatis",
        pompa:document.getElementById("pompa").innerHTML.toLowerCase(),
        zona:document.getElementById("zonaSelect").value

    });

};

document.getElementById("btnManual").onclick=function(){

    this.classList.add("active");
    document.getElementById("btnAuto").classList.remove("active");

    simpanKontrol({

        mode:"manual",
        pompa:document.getElementById("pompa").innerHTML.toLowerCase(),
        zona:document.getElementById("zonaSelect").value

    });

};

document.getElementById("btnPompaOn").onclick=function(){

    simpanKontrol({

        mode: document.getElementById("btnManual").classList.contains("active")
            ? "manual"
            : "otomatis",

        pompa:"on",

        zona:document.getElementById("zonaSelect").value

    });

};

document.getElementById("btnPompaOff").onclick=function(){

    simpanKontrol({

        mode: document.getElementById("btnManual").classList.contains("active")
            ? "manual"
            : "otomatis",

        pompa:"off",

        zona:document.getElementById("zonaSelect").value

    });

};

document.getElementById("zonaSelect").onchange=function(){

    simpanKontrol({

        mode: document.getElementById("btnManual").classList.contains("active")
            ? "manual"
            : "otomatis",

        pompa: document.getElementById("btnPompaOn").classList.contains("btn-active")
            ? "on"
            : "off",

        zona:this.value

    });

};

loadDashboard();

setInterval(loadDashboard,3000);

const chartLabels = <?= json_encode(array_map(fn($d) => date('H:i', strtotime($d['tanggal'])), $chartData)); ?>;
const suhuData = <?= json_encode(array_column($chartData, 'suhu')); ?>;
const kelembapanData = <?= json_encode(array_column($chartData, 'kelembapan')); ?>;

let monitoringChart = null;

window.addEventListener("load", function () {

    const canvas = document.getElementById("monitoringChart");

    if (!canvas) return;

    monitoringChart = new Chart(canvas.getContext("2d"), {

        type: "line",

        data: {

            labels: chartLabels,

            datasets: [

                {
                    label: "Suhu (°C)",
                    data: suhuData,
                    borderColor: "#ef4444",
                    backgroundColor: "rgba(239,68,68,.15)",
                    tension: .35,
                    fill: false
                },

                {
                    label: "Kelembapan (%)",
                    data: kelembapanData,
                    borderColor: "#3b82f6",
                    backgroundColor: "rgba(59,130,246,.15)",
                    tension: .35,
                    fill: false
                }

            ]

        },

        options: {

            responsive: true,
            maintainAspectRatio: false,

            interaction: {
                mode: "index",
                intersect: false
            },

            scales: {
                y: {
                    beginAtZero: true
                }
            }

        }

    });

});

</script>