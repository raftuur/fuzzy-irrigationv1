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

    public function sensor()
    {
        // ========== LOGGING UNTUK DEBUG ==========
        log_message('info', 'POST : ' . json_encode($this->request->getPost()));
        log_message('debug', 'RAW : ' . $this->request->getBody());
        // ==========================================

        // ========== VALIDASI API KEY ==========
        $apiKey = $this->request->getVar('api_key');
        if ($apiKey !== 'FuzzyIrigasi2026') {
            return $this->response
                ->setStatusCode(401)
                ->setBody('INVALID_API_KEY');
        }
        // ======================================

        $riwayat = new RiwayatModel();
        $kontrol = new KontrolModel();

        // Ambil parameter GET atau POST
        $a = $this->request->getVar('a');
        $b = $this->request->getVar('b');
        $c = $this->request->getVar('c');
        $d = $this->request->getVar('d');

        $suhu = $this->request->getVar('suhu');
        $hum = $this->request->getVar('hum');
        $rain = $this->request->getVar('rain');
        $durasi = $this->request->getVar('durasi') ?? 0;

        // ========== VALIDASI DATA LENGKAP ==========
        if (
            $a === null ||
            $b === null ||
            $c === null ||
            $d === null ||
            $suhu === null ||
            $hum === null ||
            $rain === null
        ) {
            return $this->response
                ->setStatusCode(400)
                ->setBody("NO_DATA_RECEIVED");
        }
        // ===========================================

        $status_hujan = ($rain < 1000) ? 'hujan' : 'cerah';

        $tanggal = date('Y-m-d H:i:s');

        // ========== AMBIL DATA KONTROL ==========
        $current = $kontrol->find(1);

        if (!$current) {
            $kontrol->insert([
                'mode' => 'otomatis',
                'pompa' => 'off',
                'zona' => 'A'
            ]);
            $current = $kontrol->find(1);
        }

        if ($current) {
            $mode = $current['mode'] ?? 'otomatis';
            
            if ($mode == 'manual') {
                // Mode Manual: Gunakan data dari database
                $pompa = $current['pompa'] ?? 'off';
                $zona = $current['zona'] ?? 'A';
            } else {
                // Mode Otomatis: ESP32 yang menentukan
                $pompa = $this->request->getVar('p') ?? 'off';
                $zona = $this->request->getVar('z') ?? '-';
                
                // Update zona di database
                if ($zona != '-' && $zona != null && $zona != '') {
                    $kontrol->update(1, [
                        'zona' => $zona,
                        'pompa' => $pompa
                    ]);
                }
            }
        } else {
            $mode = "otomatis";
            $pompa = "off";
            $zona = "A";
        }

        // ========== SIMPAN DATA RIWAYAT ==========
        $data = [
            'tanggal'             => $tanggal,
            'suhu'                => $suhu,
            'kelembapan'          => $hum,
            'tanah_a'             => $a,
            'tanah_b'             => $b,
            'tanah_c'             => $c,
            'tanah_d'             => $d,
            'status_hujan'        => $status_hujan,
            'mode'                => $mode,
            'pompa'               => $pompa,
            'zona'                => $zona,
            'durasi_penyiraman'   => $durasi,
            'id_device'           => $this->request->getPost('id_device') ?? 'ESP32-001'
        ];

        // ========== TRANSACTION DATABASE ==========
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $riwayat->insert($data);
        } catch (\Throwable $e) {
            $db->transRollback();
            return $this->response
                ->setStatusCode(500)
                ->setBody($e->getMessage());
        }

        // ========== UPDATE STATUS DEVICE ==========
        $idDevice = $this->request->getPost('id_device') ?? 'ESP32-001';
        $firmware = $this->request->getPost('firmware') ?? 'v1.0';

        $deviceModel = new DeviceModel();

        $dataDevice = [
            'status'        => 'Online',
            'ip_address'    => $this->request->getIPAddress(),
            'firmware'      => $firmware,
            'last_update'   => date('Y-m-d H:i:s'),
            'mode'          => $mode,
            'pompa'         => $pompa,
            'zona'          => $zona,
            'durasi'        => $durasi,
            'status_hujan'  => $status_hujan,
        ];

        $device = $deviceModel->find($idDevice);

        if ($device) {
            $deviceModel->update($idDevice, $dataDevice);
        } else {
            $dataDevice['id_device'] = $idDevice;
            $dataDevice['nama_device'] = 'ESP32 Penyiram';
            $dataDevice['lokasi'] = 'Green House';
            $deviceModel->insert($dataDevice);
        }

        $db->transComplete();

        return $this->response->setBody(
            $mode . "," . $pompa . "," . $zona
        );
    }

    public function kontrol()
    {
        $kontrol = new KontrolModel();

        // ==========================
        // GET : Dibaca oleh ESP32
        // ==========================
        if ($this->request->getMethod() === 'get') {

            $last = $kontrol->find(1);

            if (!$last) {
                $kontrol->insert([
                    'mode' => 'otomatis',
                    'pompa' => 'off',
                    'zona' => 'A'
                ]);
                $last = $kontrol->find(1);
            }

            $mode = $last['mode'] ?? 'otomatis';
            $pompa = $last['pompa'] ?? 'off';
            $zona = $last['zona'] ?? 'A';

            log_message('error', 'GET KONTROL: mode=' . $mode . ', pompa=' . $pompa . ', zona=' . $zona);

            return $this->response->setJSON([
                'status' => 'success',
                'mode'   => $mode,
                'pompa'  => $pompa,
                'zona'   => $zona
            ]);
        }

        // ==========================
        // POST : Dari Dashboard
        // ==========================

        $mode  = $this->request->getPost('mode');
        $pompa = $this->request->getPost('pompa');
        $zona  = $this->request->getPost('zona');

        log_message('error', 'POST KONTROL: mode=' . $mode . ', pompa=' . $pompa . ', zona=' . $zona);

        // ========== VALIDASI ==========
        if ($zona == '-' || $zona == null || $zona == '') {
            $zona = 'A';
        }

        // ========== LOGIKA MODE ==========
        if ($mode == 'otomatis') {
            $zona = '-';
            $pompa = 'off';
        }

        $last = $kontrol->find(1);

        $data = [
            'mode'  => $mode,
            'pompa' => $pompa,
            'zona'  => $zona
        ];

        if ($last) {
            $kontrol->update(1, $data);
        } else {
            $kontrol->insert($data);
        }

        // ========== UPDATE DEVICE ==========
        $deviceModel = new DeviceModel();
        $device = $deviceModel->find('ESP32-001');

        if ($device) {
            $deviceModel->update('ESP32-001', [
                'mode'        => $mode,
                'pompa'       => $pompa,
                'zona'        => $zona,
                'last_update' => date('Y-m-d H:i:s')
            ]);
        } else {
            $deviceModel->insert([
                'id_device' => 'ESP32-001',
                'nama_device' => 'ESP32 Penyiram',
                'lokasi' => 'Green House',
                'status' => 'Online',
                'mode' => $mode,
                'pompa' => $pompa,
                'zona' => $zona,
                'last_update' => date('Y-m-d H:i:s')
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'mode'   => $mode,
            'pompa'  => $pompa,
            'zona'   => $zona
        ]);
    }

    public function dashboard()
    {
        $riwayat = new \App\Models\RiwayatModel();
        $kontrol = new \App\Models\KontrolModel();
        $device = new \App\Models\DeviceModel();

        $lastRiwayat = $riwayat
            ->orderBy('id_riwayat', 'DESC')
            ->first();

        $lastKontrol = $kontrol->find(1);

        $lastDevice = $device->find('ESP32-001');

        if (!$lastDevice) {
            $device->insert([
                'id_device' => 'ESP32-001',
                'nama_device' => 'ESP32 Penyiram',
                'lokasi' => 'Green House',
                'status' => 'Offline',
                'mode' => 'otomatis',
                'pompa' => 'off',
                'zona' => 'A'
            ]);
            $lastDevice = $device->find('ESP32-001');
        }

        if ($lastDevice) {
            $selisih = time() - strtotime($lastDevice['last_update'] ?? date('Y-m-d H:i:s'));
            $lastDevice['online'] = ($selisih <= 30);
            $lastDevice['selisih_detik'] = $selisih;
        }

        $history = $riwayat
            ->select('tanggal, suhu, kelembapan, tanah_a, tanah_b, tanah_c, tanah_d')
            ->orderBy('id_riwayat', 'DESC')
            ->findAll(20);

        return $this->response->setJSON([
            'riwayat' => $lastRiwayat,
            'kontrol' => $lastKontrol,
            'device'  => $lastDevice,
            'history' => array_reverse($history)
        ]);
    }
}