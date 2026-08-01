<?php

namespace App\Controllers;

use App\Models\RiwayatModel;
use App\Models\KontrolModel;
use App\Models\SettingModel;
use App\Models\DeviceModel;
use CodeIgniter\RESTful\ResourceController;

class ApiController extends ResourceController
{
    protected $format = 'json';

    // ============================================================
    // 1. SENSOR - ESP32 kirim data sensor ke web
    //    Method : POST
    //    Endpoint : /api/sensor
    // ============================================================
    public function sensor()
    {
        // ---------- VALIDASI API KEY ----------
        $apiKey = $this->request->getVar('api_key');
        if ($apiKey !== 'FuzzyIrigasi2026') {
            return $this->response
                ->setStatusCode(401)
                ->setBody('INVALID_API_KEY');
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
        $idDevice = $this->request->getPost('id_device') ?? 'ESP32-001';
        $firmware = $this->request->getPost('firmware') ?? 'v1.0';

        // ---------- VALIDASI DATA ----------
        if (
            $a === null || $b === null || $c === null || $d === null ||
            $suhu === null || $hum === null || $rain === null
        ) {
            return $this->response
                ->setStatusCode(400)
                ->setBody('NO_DATA_RECEIVED');
        }

        // ---------- TENTUKAN STATUS HUJAN ----------
        $statusHujan = ($rain < 1000) ? 'hujan' : 'cerah';

        // ---------- AMBIL MODE KONTROL ----------
        $kontrolModel = new KontrolModel();
        $kontrol = $kontrolModel->find(1);

        if (!$kontrol) {
            $kontrolModel->insert([
                'mode' => 'otomatis',
                'pompa' => 'off',
                'zona' => '-'
            ]);
            $kontrol = $kontrolModel->find(1);
        }

        $mode = $kontrol['mode'] ?? 'otomatis';

        // ---------- TENTUKAN POMPA & ZONA ----------
        if ($mode == 'manual') {
            // Manual: pakai data dari database
            $pompa = $kontrol['pompa'] ?? 'off';
            $zona = $kontrol['zona'] ?? '-';
        } else {
            // Otomatis: ESP32 yang menentukan
            $pompa = $pompaStatus;
            $zona = $zonaDariEsp;

            // Update zona di database jika valid
            if ($zona != '-' && $zona != '' && $zona != null) {
                $kontrolModel->update(1, [
                    'zona' => $zona,
                    'pompa' => $pompa
                ]);
            }
        }

        // ---------- SIMPAN RIWAYAT ----------
        $riwayatModel = new RiwayatModel();
        $riwayatModel->insert([
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
        ]);

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

            // Ambil data dari database
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

            // ========== PERBAIKAN: Pastikan nilai tidak null ==========
            $mode = ($kontrol['mode'] ?? 'otomatis');
            $pompa = ($kontrol['pompa'] ?? 'off');
            $zona = ($kontrol['zona'] ?? '-');

            // ========== PERBAIKAN: Log untuk debug ==========
            log_message('error', 'GET KONTROL: mode=' . $mode . ', pompa=' . $pompa . ', zona=' . $zona);

            // ========== PERBAIKAN: Kirim response dengan nilai pasti ==========
            return $this->response
                ->setHeader('Content-Type', 'application/json')
                ->setJSON([
                    'status' => 'success',
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

        // ========== PERBAIKAN: Log POST ==========
        log_message('error', 'POST KONTROL: mode=' . $mode . ', pompa=' . $pompa . ', zona=' . $zona);

        // ========== PERBAIKAN: Validasi ==========
        if ($mode == 'otomatis') {
            $zona = '-';
            $pompa = 'off';
        }

        if ($zona == '' || $zona == null) {
            $zona = '-';
        }

        // ========== PERBAIKAN: Simpan ke database ==========
        $kontrol = $kontrolModel->find(1);
        $data = ['mode' => $mode, 'pompa' => $pompa, 'zona' => $zona];

        if ($kontrol) {
            $kontrolModel->update(1, $data);
        } else {
            $kontrolModel->insert($data);
        }

        // ========== PERBAIKAN: Update device ==========
        $deviceModel = new DeviceModel();
        $deviceModel->update('ESP32-001', [
            'mode' => $mode,
            'pompa' => $pompa,
            'zona' => $zona,
            'last_update' => date('Y-m-d H:i:s')
        ]);

        // ========== PERBAIKAN: Kirim response dengan nilai pasti ==========
        return $this->response
            ->setHeader('Content-Type', 'application/json')
            ->setJSON([
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
        $device = $deviceModel->find('ESP32-001');

        // Jika device tidak ada, buat default
        if (!$device) {
            $deviceModel->insert([
                'id_device' => 'ESP32-001',
                'nama_device' => 'ESP32 Penyiram',
                'lokasi' => 'Green House',
                'status' => 'Offline',
                'mode' => 'otomatis',
                'pompa' => 'off',
                'zona' => '-'
            ]);
            $device = $deviceModel->find('ESP32-001');
        }

        // Cek status online
        if ($device) {
            $selisih = time() - strtotime($device['last_update'] ?? date('Y-m-d H:i:s'));
            $device['online'] = ($selisih <= 30);
        }

        // History untuk grafik
        $history = $riwayatModel
            ->select('tanggal, suhu, kelembapan, tanah_a, tanah_b, tanah_c, tanah_d')
            ->orderBy('id_riwayat', 'DESC')
            ->limit(20)
            ->find();

        return $this->response
            ->setHeader('Content-Type', 'application/json')
            ->setJSON([
                'riwayat' => $riwayat,
                'kontrol' => $kontrol,
                'device' => $device,
                'history' => array_reverse($history)
            ]);
    }
}