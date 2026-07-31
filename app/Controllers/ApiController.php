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

        $current = $kontrol
            ->orderBy('id_kontrol', 'DESC')
            ->first();

        if ($current) {

            $mode = $current['mode'];

            if ($mode == 'otomatis') {
                $pompa = $this->request->getVar('p') ?? 'off';
            } else {
                $pompa = $current['pompa'];
            }

            $zona = $this->request->getVar('z');

            if (!$zona || $zona == "-") {
                $zona = $current['zona'];
            }

        } else {

            $mode = "otomatis";
            $pompa = "off";
            $zona = "A";

        }

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
        ];

        // ========== TRANSACTION DATABASE ==========
        $db = \Config\Database::connect();
        $db->transStart();

        // Simpan riwayat
        if (!$riwayat->insert($data)) {
            $db->transRollback();
            return $this->response
                ->setStatusCode(500)
                ->setBody('FAILED_SAVE_DATA');
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

            // Status realtime
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
        // ==========================================

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

            $last = $kontrol
                ->orderBy('id_kontrol', 'DESC')
                ->first();

            if (!$last) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'mode'   => 'otomatis',
                    'pompa'  => 'off',
                    'zona'   => 'A'
                ]);
            }

            return $this->response->setJSON([
                'status' => 'success',
                'mode'   => $last['mode'],
                'pompa'  => $last['pompa'],
                'zona'   => $last['zona']
            ]);
        }

        // ==========================
        // POST : Dari Dashboard
        // ==========================

        $mode  = $this->request->getPost('mode');
        $pompa = $this->request->getPost('pompa');
        $zona  = $this->request->getPost('zona');

        $last = $kontrol
            ->orderBy('id_kontrol', 'DESC')
            ->first();

        $data = [
            'mode'  => $mode,
            'pompa' => $pompa,
            'zona'  => $zona
        ];

        if ($last) {
            $kontrol->update($last['id_kontrol'], $data);
        } else {
            $kontrol->insert($data);
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

        $lastRiwayat = $riwayat
            ->orderBy('id_riwayat', 'DESC')
            ->first();

        $lastKontrol = $kontrol
            ->orderBy('id_kontrol', 'DESC')
            ->first();

        // Ambil 10 data terakhir
        $history = $riwayat
            ->orderBy('id_riwayat', 'DESC')
            ->findAll(10);

        return $this->response->setJSON([
            'riwayat' => $lastRiwayat,
            'kontrol' => $lastKontrol,
            'history' => array_reverse($history)
        ]);
    }
}