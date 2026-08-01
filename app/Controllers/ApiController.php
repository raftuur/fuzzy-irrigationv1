<?php

namespace App\Controllers;

use App\Models\RiwayatModel;
use App\Models\KontrolModel;
use App\Models\SettingModel;
use App\Models\DeviceModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class ApiController extends ResourceController
{
    use ResponseTrait;
    
    protected $format = 'json';
    
    // ============================================================
    // KONSTANTA
    // ============================================================
    private const API_KEY = 'FuzzyIrigasi2026';
    private const DEVICE_ID = 'ESP32-001';
    private const TIMEOUT_ONLINE = 30; // detik
    private const MAX_HISTORY = 20;

    // ============================================================
    // 1. SENSOR - ESP32 kirim data sensor ke web
    //    Method : POST
    //    Endpoint : /api/sensor
    // ============================================================
    public function sensor()
    {
        // ---------- VALIDASI API KEY ----------
        $apiKey = $this->request->getVar('api_key');
        if ($apiKey !== self::API_KEY) {
            return $this->failUnauthorized('INVALID_API_KEY');
        }

        // ---------- AMBIL DATA ----------
        $a = $this->request->getVar('a');
        $b = $this->request->getVar('b');
        $c = $this->request->getVar('c');
        $d = $this->request->getVar('d');
        $suhu = $this->request->getVar('suhu');
        $hum = $this->request->getVar('hum');
        $rain = $this->request->getVar('rain');
        $durasi = $this->request->getVar('durasi') ?? 0;
        $pompaStatus = $this->request->getVar('p') ?? 'off';
        $zonaDariEsp = $this->request->getVar('z') ?? '-';
        $idDevice = $this->request->getPost('id_device') ?? self::DEVICE_ID;
        $firmware = $this->request->getPost('firmware') ?? 'v1.0';

        // ---------- VALIDASI DATA ----------
        if (
            $a === null || $b === null || $c === null || $d === null ||
            $suhu === null || $hum === null || $rain === null
        ) {
            return $this->failValidationErrors('NO_DATA_RECEIVED');
        }

        // ---------- VALIDASI NILAI ----------
        $a = $this->validateRange($a, 0, 100);
        $b = $this->validateRange($b, 0, 100);
        $c = $this->validateRange($c, 0, 100);
        $d = $this->validateRange($d, 0, 100);
        $suhu = $this->validateRange($suhu, -10, 50);
        $hum = $this->validateRange($hum, 0, 100);

        // ---------- TENTUKAN STATUS HUJAN ----------
        $statusHujan = ($rain < 1000) ? 'hujan' : 'cerah';

        // ---------- AMBIL MODE KONTROL ----------
        $kontrolModel = new KontrolModel();
        $kontrol = $kontrolModel->find(1);

        // ✅ PERBAIKAN: Pastikan data ada
        if (!$kontrol) {
            $kontrolModel->insert([
                'mode' => 'otomatis',
                'pompa' => 'off',
                'zona' => '-'
            ]);
            $kontrol = $kontrolModel->find(1);
        }

        // ✅ PERBAIKAN: Ambil nilai dengan default
        $mode = $kontrol['mode'] ?? 'otomatis';
        
        // ✅ PERBAIKAN: Log untuk debug
        log_message('debug', 'SENSOR: mode=' . $mode . ', pompaStatus=' . $pompaStatus . ', zonaEsp=' . $zonaDariEsp);

        // ---------- TENTUKAN POMPA & ZONA ----------
        if ($mode == 'manual') {
            // Manual: pakai data dari database
            $pompa = $kontrol['pompa'] ?? 'off';
            $zona = $kontrol['zona'] ?? '-';
        } else {
            // Otomatis: ESP32 yang menentukan
            $pompa = $pompaStatus;
            $zona = $zonaDariEsp;

            // ✅ PERBAIKAN: Update zona di database jika valid
            if ($zona != '-' && $zona != '' && $zona != null) {
                $kontrolModel->update(1, [
                    'zona' => $zona,
                    'pompa' => $pompa
                ]);
            }
        }

        // ---------- SIMPAN RIWAYAT ----------
        $riwayatModel = new RiwayatModel();
        $riwayatData = [
            'tanggal' => date('Y-m-d H:i:s'),
            'suhu' => $suhu,
            'kelembapan' => $hum,
            'tanah_a' => $a,
            'tanah_b' => $b,
            'tanah_c' => $c,
            'tanah_d' => $d,
            'status_hujan' => $statusHujan,
            'mode' => $mode,
            'pompa' => $pompa,
            'zona' => $zona,
            'durasi_penyiraman' => $durasi,
            'id_device' => $idDevice
        ];
        
        $riwayatModel->insert($riwayatData);

        // ✅ PERBAIKAN: Hapus data lama (lebih dari 7 hari)
        $this->cleanupOldData($riwayatModel);

        // ---------- UPDATE DEVICE ----------
        $deviceModel = new DeviceModel();
        $device = $deviceModel->find($idDevice);

        $dataDevice = [
            'status' => 'Online',
            'ip_address' => $this->request->getIPAddress(),
            'firmware' => $firmware,
            'last_update' => date('Y-m-d H:i:s'),
            'mode' => $mode,
            'pompa' => $pompa,
            'zona' => $zona,
            'durasi' => $durasi,
            'status_hujan' => $statusHujan
        ];

        if ($device) {
            $deviceModel->update($idDevice, $dataDevice);
        } else {
            $dataDevice['id_device'] = $idDevice;
            $dataDevice['nama_device'] = 'ESP32 Penyiram';
            $dataDevice['lokasi'] = 'Green House';
            $deviceModel->insert($dataDevice);
        }

        // ---------- RESPONSE ----------
        return $this->response->setBody($mode . ',' . $pompa . ',' . $zona);
    }

    // ============================================================
    // 2. KONTROL - ESP32 baca / Dashboard kirim kontrol
    //    Method : GET (ESP32) / POST (Dashboard)
    //    Endpoint : /api/kontrol
    // ============================================================
    public function kontrol()
    {
        $kontrolModel = new KontrolModel();

        // ==========================
        // GET : ESP32 BACA KONTROL
        // ==========================
        if ($this->request->getMethod() === 'get') {

            // ✅ PERBAIKAN: Cek dan buat data jika kosong
            $kontrol = $kontrolModel->find(1);

            // Jika tidak ada, buat default
            if (!$kontrol) {
                $kontrolModel->insert([
                    'mode' => 'otomatis',
                    'pompa' => 'off',
                    'zona' => '-'
                ]);
                $kontrol = $kontrolModel->find(1);
            }

            // ✅ PERBAIKAN: Pastikan nilai tidak null
            $mode = ($kontrol['mode'] ?? 'otomatis');
            $pompa = ($kontrol['pompa'] ?? 'off');
            $zona = ($kontrol['zona'] ?? '-');

            // ✅ PERBAIKAN: Log untuk debug
            log_message('debug', 'GET KONTROL: mode=' . $mode . ', pompa=' . $pompa . ', zona=' . $zona);

            // ✅ PERBAIKAN: Kirim response dengan nilai pasti
            return $this->respond([
                'mode' => $mode,
                'pompa' => $pompa,
                'zona' => $zona
            ]);
        }

        // ==========================
        // POST : DASHBOARD KIRIM KONTROL
        // ==========================
        $mode = $this->request->getPost('mode');
        $pompa = $this->request->getPost('pompa');
        $zona = $this->request->getPost('zona');

        // ✅ PERBAIKAN: Validasi API Key untuk POST
        $apiKey = $this->request->getPost('api_key');
        if ($apiKey && $apiKey !== self::API_KEY) {
            return $this->failUnauthorized('Invalid API Key');
        }

        // ✅ PERBAIKAN: Log POST
        log_message('debug', 'POST KONTROL: mode=' . $mode . ', pompa=' . $pompa . ', zona=' . $zona);

        // ✅ PERBAIKAN: Validasi dan set default
        if (empty($mode) || !in_array($mode, ['otomatis', 'manual'])) {
            $mode = 'otomatis';
        }

        if ($mode == 'otomatis') {
            $zona = '-';
            $pompa = 'off';
        }

        if (empty($zona) || $zona == null) {
            $zona = '-';
        }

        if (empty($pompa) || !in_array($pompa, ['on', 'off'])) {
            $pompa = 'off';
        }

        // ✅ PERBAIKAN: Simpan ke database
        $kontrol = $kontrolModel->find(1);
        $data = [
            'mode' => $mode, 
            'pompa' => $pompa, 
            'zona' => $zona
        ];

        if ($kontrol) {
            $kontrolModel->update(1, $data);
        } else {
            $kontrolModel->insert($data);
        }

        // ✅ PERBAIKAN: Update device
        $deviceModel = new DeviceModel();
        $device = $deviceModel->find(self::DEVICE_ID);
        if ($device) {
            $deviceModel->update(self::DEVICE_ID, [
                'mode' => $mode,
                'pompa' => $pompa,
                'zona' => $zona,
                'last_update' => date('Y-m-d H:i:s')
            ]);
        } else {
            $deviceModel->insert([
                'id_device' => self::DEVICE_ID,
                'nama_device' => 'ESP32 Penyiram',
                'mode' => $mode,
                'pompa' => $pompa,
                'zona' => $zona,
                'status' => 'Online',
                'last_update' => date('Y-m-d H:i:s')
            ]);
        }

        // ✅ PERBAIKAN: Kirim response dengan nilai pasti
        return $this->respond([
            'status' => 'success',
            'mode' => $mode,
            'pompa' => $pompa,
            'zona' => $zona
        ]);
    }

    // ============================================================
    // 3. DASHBOARD - Ambil data untuk dashboard
    //    Method : GET
    //    Endpoint : /api/dashboard
    // ============================================================
    public function dashboard()
    {
        $riwayatModel = new RiwayatModel();
        $kontrolModel = new KontrolModel();
        $deviceModel = new DeviceModel();

        // Data terakhir
        $riwayat = $riwayatModel->orderBy('id_riwayat', 'DESC')->first();
        $kontrol = $kontrolModel->find(1);
        $device = $deviceModel->find(self::DEVICE_ID);

        // ✅ PERBAIKAN: Jika device tidak ada, buat default
        if (!$device) {
            $deviceModel->insert([
                'id_device' => self::DEVICE_ID,
                'nama_device' => 'ESP32 Penyiram',
                'lokasi' => 'Green House',
                'status' => 'Offline',
                'mode' => 'otomatis',
                'pompa' => 'off',
                'zona' => '-'
            ]);
            $device = $deviceModel->find(self::DEVICE_ID);
        }

        // ✅ PERBAIKAN: Jika kontrol tidak ada, buat default
        if (!$kontrol) {
            $kontrolModel->insert([
                'mode' => 'otomatis',
                'pompa' => 'off',
                'zona' => '-'
            ]);
            $kontrol = $kontrolModel->find(1);
        }

        // Cek status online
        if ($device) {
            $lastUpdate = $device['last_update'] ?? date('Y-m-d H:i:s');
            $selisih = time() - strtotime($lastUpdate);
            $device['online'] = ($selisih <= self::TIMEOUT_ONLINE);
            
            // ✅ PERBAIKAN: Tambahkan status untuk view
            $device['is_online'] = $device['online'];
            $device['last_seen'] = $lastUpdate;
        }

        // History untuk grafik
        $history = $riwayatModel
            ->select('tanggal, suhu, kelembapan, tanah_a, tanah_b, tanah_c, tanah_d')
            ->orderBy('id_riwayat', 'DESC')
            ->limit(self::MAX_HISTORY)
            ->find();

        // ✅ PERBAIKAN: Tambahkan statistik
        $statistics = $this->getStatistics($riwayatModel);

        return $this->respond([
            'riwayat' => $riwayat,
            'kontrol' => $kontrol,
            'device' => $device,
            'history' => array_reverse($history),
            'statistics' => $statistics
        ]);
    }

    // ============================================================
    // 4. UPDATE KONTROL VIA WEB (Alternate endpoint)
    //    Method : POST
    //    Endpoint : /api/update-kontrol
    // ============================================================
    public function updateKontrol()
    {
        // ✅ PERBAIKAN: Validasi API Key
        $apiKey = $this->request->getPost('api_key');
        if ($apiKey !== self::API_KEY) {
            return $this->failUnauthorized('Invalid API Key');
        }

        $mode = $this->request->getPost('mode') ?? 'otomatis';
        $pompa = $this->request->getPost('pompa') ?? 'off';
        $zona = $this->request->getPost('zona') ?? '-';

        // ✅ PERBAIKAN: Validasi nilai
        if (!in_array($mode, ['otomatis', 'manual'])) {
            $mode = 'otomatis';
        }
        if (!in_array($pompa, ['on', 'off'])) {
            $pompa = 'off';
        }

        $kontrolModel = new KontrolModel();
        $kontrol = $kontrolModel->find(1);

        $data = ['mode' => $mode, 'pompa' => $pompa, 'zona' => $zona];

        if ($kontrol) {
            $kontrolModel->update(1, $data);
        } else {
            $kontrolModel->insert($data);
        }

        return $this->respond([
            'status' => 'success',
            'message' => 'Kontrol updated',
            'data' => $data
        ]);
    }

    // ============================================================
    // 5. CLEANUP OLD DATA
    // ============================================================
    private function cleanupOldData($riwayatModel)
    {
        $tanggalBatas = date('Y-m-d H:i:s', strtotime('-7 days'));
        $riwayatModel->where('tanggal <', $tanggalBatas)->delete();
    }

    // ============================================================
    // 6. VALIDATE RANGE
    // ============================================================
    private function validateRange($value, $min, $max)
    {
        $value = floatval($value);
        return max($min, min($max, $value));
    }

    // ============================================================
    // 7. GET STATISTICS
    // ============================================================
    private function getStatistics($riwayatModel)
    {
        $today = date('Y-m-d');
        
        // Total monitoring hari ini
        $monitoringHariIni = $riwayatModel
            ->where('DATE(tanggal)', $today)
            ->countAllResults();
            
        // Total penyiraman hari ini
        $penyiramanHariIni = $riwayatModel
            ->where('DATE(tanggal)', $today)
            ->where('durasi_penyiraman >', 0)
            ->countAllResults();
            
        // Average suhu hari ini
        $avgSuhu = $riwayatModel
            ->select('AVG(suhu) as avg_suhu')
            ->where('DATE(tanggal)', $today)
            ->first();
            
        // Average kelembapan hari ini
        $avgKelembapan = $riwayatModel
            ->select('AVG(kelembapan) as avg_kelembapan')
            ->where('DATE(tanggal)', $today)
            ->first();

        return [
            'monitoring_hari_ini' => $monitoringHariIni,
            'penyiraman_hari_ini' => $penyiramanHariIni,
            'avg_suhu' => round($avgSuhu['avg_suhu'] ?? 0, 1),
            'avg_kelembapan' => round($avgKelembapan['avg_kelembapan'] ?? 0, 1)
        ];
    }
}