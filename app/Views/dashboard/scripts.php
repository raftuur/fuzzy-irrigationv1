/* ============================================================
   SMART IRRIGATION DASHBOARD - JavaScript (DIPERBAIKI)
   ============================================================ */

// ============================================================
// 1. KONFIGURASI GLOBAL
// ============================================================
const CONFIG = {
    autoRefreshInterval: 5000, // 5 detik
    apiBaseUrl: '<?= base_url("api") ?>',
    maxRetries: 3,
    retryDelay: 1000
};

// ============================================================
// 2. SET MANUAL CONTROL
// ============================================================
function setManualControl(enabled) {
    const zonaSelect = document.getElementById("zonaSelect");
    const btnPompaOn = document.getElementById("btnPompaOn");
    const btnPompaOff = document.getElementById("btnPompaOff");
    
    if (zonaSelect) zonaSelect.disabled = !enabled;
    if (btnPompaOn) btnPompaOn.disabled = !enabled;
    if (btnPompaOff) btnPompaOff.disabled = !enabled;
}

// ============================================================
// 3. UPDATE SOIL MONITOR
// ============================================================
function updateSoil(zona, nilai) {
    const bar = document.getElementById("pb" + zona);
    const text = document.getElementById("nilai" + zona);
    const status = document.getElementById("status" + zona);
    const badge = document.getElementById("badge" + zona);

    if (!bar) return;

    nilai = Number(nilai) || 0;
    const nilaiAman = Math.min(Math.max(nilai, 0), 100);

    bar.style.width = nilaiAman + "%";
    bar.setAttribute("aria-valuenow", nilaiAman);
    text.innerHTML = nilaiAman + "%";

    // Update warna dan status berdasarkan tingkat kelembapan
    let statusText, badgeText, badgeClass, barColor;
    
    if (nilaiAman < 30) {
        statusText = "🔴 Tanah Sangat Kering (Perlu Penyiraman)";
        badgeText = "Kering";
        badgeClass = "badge bg-danger";
        barColor = "#ef4444";
    } else if (nilaiAman < 60) {
        statusText = "🟡 Kelembapan Sedang";
        badgeText = "Sedang";
        badgeClass = "badge bg-warning text-dark";
        barColor = "#f59e0b";
    } else {
        statusText = "🟢 Kondisi Optimal";
        badgeText = "Basah";
        badgeClass = "badge bg-success";
        barColor = "#22c55e";
    }

    if (status) {
        status.innerHTML = statusText;
        status.className = "text-muted small";
    }
    
    if (badge) {
        badge.className = badgeClass;
        badge.innerHTML = badgeText;
    }
    
    if (bar) {
        bar.style.background = barColor;
    }
}

// ============================================================
// 4. SIMPAN KONTROL (DENGAN VALIDASI)
// ============================================================
function simpanKontrol(data) {
    // Validasi data
    if (!data.mode || !data.pompa) {
        console.error("Data tidak lengkap:", data);
        return Promise.reject(new Error("Data tidak lengkap"));
    }

    // Tambahkan api_key untuk keamanan
    data.api_key = "<?= $apiKey ?? 'FuzzyIrigasi2026' ?>";

    return fetch(CONFIG.apiBaseUrl + "/kontrol", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
            "X-Requested-With": "XMLHttpRequest"
        },
        body: new URLSearchParams(data)
    })
    .then(res => {
        if (!res.ok) {
            throw new Error(`HTTP error! status: ${res.status}`);
        }
        return res.json();
    })
    .then(res => {
        if (res.status === "success") {
            showToast("success", "Berhasil", "Pengaturan berhasil disimpan");
            loadDashboard();
            return res;
        } else {
            throw new Error(res.message || "Gagal menyimpan pengaturan");
        }
    })
    .catch(err => {
        console.error('Error:', err);
        showToast("error", "Gagal", "Terjadi kesalahan: " + err.message);
        throw err;
    });
}

// ============================================================
// 5. TOAST NOTIFICATION
// ============================================================
function showToast(type, title, message) {
    // Gunakan SweetAlert2 jika tersedia
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: type,
            title: title,
            text: message,
            timer: 2000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    } else {
        // Fallback: console log
        console.log(`[${type.toUpperCase()}] ${title}: ${message}`);
    }
}

// ============================================================
// 6. LOAD DASHBOARD (DIPERBAIKI)
// ============================================================
function loadDashboard(retryCount = 0) {
    fetch(CONFIG.apiBaseUrl + "/dashboard")
    .then(res => {
        if (!res.ok) {
            throw new Error(`HTTP error! status: ${res.status}`);
        }
        return res.json();
    })
    .then(data => {
        // Reset retry count on success
        retryCount = 0;
        
        const r = data.riwayat || {};
        const k = data.kontrol || {};
        const d = data.device || {};

        // ---------- UPDATE DEVICE INFO ----------
        updateDeviceInfo(d);

        // ---------- UPDATE MODE & CONTROL ----------
        updateModeAndControl(d);

        // ---------- UPDATE RIWAYAT ----------
        updateRiwayat(r, d);

        // ---------- UPDATE STATUS ----------
        updateStatusSystem(d);

    })
    .catch(err => {
        console.error('Error loading dashboard:', err);
        
        // Retry logic
        if (retryCount < CONFIG.maxRetries) {
            setTimeout(() => {
                loadDashboard(retryCount + 1);
            }, CONFIG.retryDelay * (retryCount + 1));
        } else {
            showToast("error", "Gagal", "Tidak dapat menghubungi server");
        }
    });
}

// ============================================================
// 7. UPDATE DEVICE INFO
// ============================================================
function updateDeviceInfo(d) {
    const deviceName = document.getElementById("deviceName");
    const firmwareVersion = document.getElementById("firmwareVersion");
    const systemStatus = document.getElementById("systemStatus");
    const systemBadge = document.getElementById("systemBadge");
    const esp32Status = document.getElementById("esp32Status");
    const statusChip = document.getElementById("statusChip");

    if (deviceName) {
        deviceName.textContent = d.id_device ?? "ESP32-001";
    }
    
    if (firmwareVersion) {
        firmwareVersion.textContent = d.firmware ?? "v1.0";
    }

    const isOnline = d.online === true;
    
    // Update status
    const statusElements = [
        { el: systemStatus, online: "Online", offline: "Offline" },
        { el: systemBadge, online: "Online", offline: "Offline" },
        { el: esp32Status, online: "Terhubung", offline: "Terputus" }
    ];
    
    statusElements.forEach(({ el, online, offline }) => {
        if (!el) return;
        el.textContent = isOnline ? online : offline;
        el.className = isOnline ? "badge bg-success" : "badge bg-danger";
    });

    // Update status chip
    if (statusChip) {
        statusChip.className = isOnline ? "status-chip online" : "status-chip offline";
        const statusText = statusChip.querySelector("#statusText");
        if (statusText) {
            statusText.textContent = isOnline ? "ESP32 Online" : "ESP32 Offline";
        }
    }
}

// ============================================================
// 8. UPDATE MODE AND CONTROL
// ============================================================
function updateModeAndControl(d) {
    const btnAuto = document.getElementById("btnAuto");
    const btnManual = document.getElementById("btnManual");
    const zonaSelect = document.getElementById("zonaSelect");
    const pompaStatus = document.getElementById("pompaStatus");
    const btnOn = document.getElementById("btnPompaOn");
    const btnOff = document.getElementById("btnPompaOff");
    const mode = d.mode || "otomatis";
    const pompa = d.pompa || "off";
    const zona = d.zona || "A";

    // Update mode buttons
    if (btnAuto && btnManual) {
        if (mode === "otomatis") {
            btnAuto.classList.add("active");
            btnManual.classList.remove("active");
            setManualControl(false);
        } else {
            btnManual.classList.add("active");
            btnAuto.classList.remove("active");
            setManualControl(true);
        }
    }

    // Update zona select
    if (zonaSelect) {
        zonaSelect.value = (zona && zona !== '-' && zona !== null) ? zona : 'A';
    }

    // Update pompa status
    if (pompaStatus) {
        pompaStatus.innerHTML = pompa === "on" ? "ON" : "OFF";
        pompaStatus.className = pompa === "on" ? "badge bg-success" : "badge bg-danger";
    }

    // Update pompa buttons
    if (btnOn && btnOff) {
        btnOn.classList.remove("btn-active", "btn-inactive");
        btnOff.classList.remove("btn-active", "btn-inactive");
        
        if (pompa === "on") {
            btnOn.classList.add("btn-active");
            btnOff.classList.add("btn-inactive");
        } else {
            btnOff.classList.add("btn-active");
            btnOn.classList.add("btn-inactive");
        }
    }
}

// ============================================================
// 9. UPDATE RIWAYAT
// ============================================================
function updateRiwayat(r, d) {
    // Update waktu
    const lastUpdate = d.last_update || r.last_update || "-";
    const updateElements = [
        "lastUpdate", 
        "lastUpdatePanel", 
        "lastUpdateSuhu", 
        "lastUpdateKelembapan", 
        "lastUpdateCuaca"
    ];
    
    updateElements.forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.innerHTML = id === "lastUpdateSuhu" || 
                          id === "lastUpdateKelembapan" || 
                          id === "lastUpdateCuaca" ? 
                          "Update: " + lastUpdate : lastUpdate;
        }
    });

    // Update durasi
    const durasiEl = document.getElementById("durasiPompa");
    if (durasiEl) {
        durasiEl.innerHTML = (r.durasi_penyiraman || 0) + " Detik";
    }

    // Update suhu dan kelembapan
    const suhu = parseFloat(r.suhu) || 0;
    const kelembapan = parseFloat(r.kelembapan) || 0;
    
    const suhuEl = document.getElementById("suhu");
    const kelembapanEl = document.getElementById("kelembapan");
    
    if (suhuEl) suhuEl.innerHTML = suhu;
    if (kelembapanEl) kelembapanEl.innerHTML = kelembapan;

    // Update status cuaca
    const statusHujan = r.status_hujan || "cerah";
    const hujanEl = document.getElementById("hujan");
    const badgeCuaca = document.getElementById("badgeCuaca");
    
    if (hujanEl) hujanEl.innerHTML = statusHujan.toUpperCase();
    
    if (badgeCuaca) {
        if (statusHujan.toLowerCase() === "hujan") {
            badgeCuaca.className = "badge bg-info";
            badgeCuaca.innerHTML = "🌧️ Hujan";
        } else {
            badgeCuaca.className = "badge bg-warning text-dark";
            badgeCuaca.innerHTML = "☀️ Cerah";
        }
    }

    // Update soil (4 zona)
    const zonaList = ['A', 'B', 'C', 'D'];
    let totalSoil = 0;
    
    zonaList.forEach(z => {
        const nilai = r['tanah_' + z.toLowerCase()] || 0;
        totalSoil += Number(nilai);
        updateSoil(z, nilai);
    });

    // Update rata-rata
    const avg = Math.round(totalSoil / 4);
    const avgEl = document.getElementById("avgSoil");
    if (avgEl) avgEl.innerHTML = avg + "%";

    // Update badge suhu
    updateTemperatureBadge(suhu);
    
    // Update badge kelembapan
    updateHumidityBadge(kelembapan);
}

// ============================================================
// 10. UPDATE BADGE SUHU
// ============================================================
function updateTemperatureBadge(suhu) {
    const badgeSuhu = document.getElementById("badgeSuhu");
    if (!badgeSuhu) return;
    
    if (suhu < 25) {
        badgeSuhu.className = "badge bg-info";
        badgeSuhu.innerText = "❄️ Rendah";
    } else if (suhu <= 32) {
        badgeSuhu.className = "badge bg-success";
        badgeSuhu.innerText = "✅ Normal";
    } else {
        badgeSuhu.className = "badge bg-danger";
        badgeSuhu.innerText = "🔥 Tinggi";
    }
}

// ============================================================
// 11. UPDATE BADGE KELEMBAPAN
// ============================================================
function updateHumidityBadge(kelembapan) {
    const badgeKelembapan = document.getElementById("badgeKelembapan");
    if (!badgeKelembapan) return;
    
    if (kelembapan < 40) {
        badgeKelembapan.className = "badge bg-warning";
        badgeKelembapan.innerText = "💧 Kering";
    } else if (kelembapan <= 80) {
        badgeKelembapan.className = "badge bg-success";
        badgeKelembapan.innerText = "✅ Optimal";
    } else {
        badgeKelembapan.className = "badge bg-info";
        badgeKelembapan.innerText = "🌊 Lembap";
    }
}

// ============================================================
// 12. UPDATE STATUS SYSTEM
// ============================================================
function updateStatusSystem(d) {
    const mode = d.mode || "otomatis";
    const pompa = d.pompa || "off";
    const zona = d.zona || "-";
    
    const modeEl = document.getElementById("mode");
    const pompaEl = document.getElementById("pompa");
    const zonaEl = document.getElementById("zona");
    const zonaAktifEl = document.getElementById("zonaAktif");
    
    if (modeEl) {
        modeEl.innerHTML = mode === "manual" ? "🛠️ Manual" : "🤖 Otomatis";
        modeEl.className = "status-value fw-bold";
    }
    
    if (pompaEl) {
        pompaEl.innerHTML = pompa === "on" ? "🟢 Menyala" : "🔴 Mati";
        pompaEl.className = "status-value fw-bold";
    }
    
    const zonaTampil = (zona && zona !== '-' && zona !== null) ? "Zona " + zona : "-";
    if (zonaEl) {
        zonaEl.innerHTML = "📍 " + zonaTampil;
        zonaEl.className = "status-value fw-bold";
    }
    
    if (zonaAktifEl) {
        zonaAktifEl.innerHTML = zonaTampil;
        zonaAktifEl.className = "badge bg-primary";
    }
}

// ============================================================
// 13. EVENT HANDLER (DIPERBAIKI)
// ============================================================

// Tombol AUTO
const btnAuto = document.getElementById("btnAuto");
if (btnAuto) {
    btnAuto.onclick = function() {
        simpanKontrol({
            mode: "otomatis",
            pompa: "off",
            zona: "-"
        });
    };
}

// Tombol MANUAL
const btnManual = document.getElementById("btnManual");
if (btnManual) {
    btnManual.onclick = function() {
        const zona = document.getElementById("zonaSelect")?.value || "A";
        simpanKontrol({
            mode: "manual",
            pompa: "off",
            zona: zona
        });
    };
}

// Tombol Pompa ON
const btnPompaOn = document.getElementById("btnPompaOn");
if (btnPompaOn) {
    btnPompaOn.onclick = function() {
        const zona = document.getElementById("zonaSelect")?.value || "A";
        simpanKontrol({
            mode: "manual",
            pompa: "on",
            zona: zona
        });
    };
}

// Tombol Pompa OFF
const btnPompaOff = document.getElementById("btnPompaOff");
if (btnPompaOff) {
    btnPompaOff.onclick = function() {
        const zona = document.getElementById("zonaSelect")?.value || "A";
        simpanKontrol({
            mode: "manual",
            pompa: "off",
            zona: zona
        });
    };
}

// Zona Select - Update saat mode manual
const zonaSelect = document.getElementById("zonaSelect");
if (zonaSelect) {
    zonaSelect.onchange = function() {
        const zona = this.value;
        const btnManual = document.getElementById("btnManual");
        
        // Cek apakah mode manual aktif
        if (btnManual && btnManual.classList.contains("active")) {
            const pompaStatus = document.getElementById("pompaStatus");
            const statusPompa = pompaStatus ? (pompaStatus.innerHTML === "ON" ? "on" : "off") : "off";
            
            simpanKontrol({
                mode: "manual",
                pompa: statusPompa,
                zona: zona
            });
        }
    };
}

// ============================================================
// 14. START
// ============================================================
// Load awal
document.addEventListener('DOMContentLoaded', function() {
    loadDashboard();
});

// Auto refresh dengan interval yang ditentukan
setInterval(loadDashboard, CONFIG.autoRefreshInterval);

// ============================================================
// 15. SERVICE WORKER UNTUK OFFLINE MODE (Opsional)
// ============================================================
// Cek koneksi internet
window.addEventListener('online', function() {
    showToast("info", "Online", "Koneksi internet pulih");
    loadDashboard();
});

window.addEventListener('offline', function() {
    showToast("warning", "Offline", "Koneksi internet terputus");
});

console.log("✅ Smart Irrigation Dashboard loaded successfully!");
console.log(`🔄 Auto refresh: ${CONFIG.autoRefreshInterval / 1000} detik`);