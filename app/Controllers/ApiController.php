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
        log_message('error', 'POST : ' . json_encode($this->request->getPost()));
        log_message('error', 'RAW  : ' . $this->request->getBody());
        // ==========================================

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

        if ($a === null || $suhu === null) {
            return $this->response->setBody("NO_DATA_RECEIVED");
        }

        $status_hujan = ($rain < 1000) ? 'hujan' : 'cerah';

        $tanggal = date('Y-m-d H:i:s');

        $current = $kontrol
            ->orderBy('id_kontrol', 'DESC')
            ->first();

        if ($current) {

            $mode = $current['mode'];

            $pompa = $this->request->getVar('p') ?? $current['pompa'];

            $zona = $this->request->getVar('z');

            if (!$zona || $zona == "-") {
                $zona = $current['zona'];
            }

        } else {

            $mode = "otomatis";
            $pompa = "off";
            $zona = "A";

        }

        $last = $riwayat
                    ->orderBy('id_riwayat','DESC')
                    ->first();

        $diffMinutes = 999;

        if ($last) {
            $diffMinutes = (time() - strtotime($last['tanggal'])) / 60;
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
            'zona'                => $zona,
            'durasi_penyiraman'   => $durasi,
        ];

        if ($durasi > 0) {

            $riwayat->insert($data);

        } else {

            if (
                $last &&
                $last['durasi_penyiraman'] == 0 &&
                $diffMinutes < 5
            ) {

                $riwayat
                    ->update($last['id_riwayat'], $data);

            } else {

                $riwayat->insert($data);

            }

        }

        // ==========================================
        // UPDATE STATUS DEVICE
        // ==========================================
        $idDevice = $this->request->getPost('id_device') ?? 'ESP32-001';

        $deviceModel = new DeviceModel();

        $deviceModel->update($idDevice, [
            'status'      => 'Online',
            'ip_address'  => $this->request->getIPAddress(),
            'last_update' => date('Y-m-d H:i:s')
        ]);

        return $this->response->setBody(
            $mode . "," . $pompa . "," . $zona
        );
    }

    public function kontrol()
    {
        $kontrol = new \App\Models\KontrolModel();

        log_message('error', 'KONTROL POST: ' . json_encode($this->request->getPost()));

        $mode = $this->request->getVar('mode') ?? 'otomatis';
        $pompa = $this->request->getVar('pompa') ?? 'off';
        $zona = $this->request->getVar('zona') ?? 'A';

        $last = $kontrol
            ->orderBy('id_kontrol', 'DESC')
            ->first();

        $data = [
            'mode' => $mode,
            'pompa' => $pompa,
            'zona' => $zona
        ];

        if ($last) {
            $result = $kontrol->update($last['id_kontrol'], $data);
        } else {
            $result = $kontrol->insert($data);
        }

        if (!$result) {
            return $this->response->setStatusCode(500)
                ->setJSON([
                    'status' => 'error',
                    'message' => 'Gagal menyimpan data kontrol.'
                ]);
        }

        // ============================================
        // TAMBAHAN: SIMPAN KONFIGURASI KE TABEL SETTING
        // ============================================
        $settingModel = new SettingModel();

        foreach ($this->request->getPost() as $key => $value) {

            // Skip field yang sudah diproses di kontrol
            if (in_array($key, ['mode', 'pompa', 'zona'])) {
                continue;
            }

            // Simpan ke tabel setting
            $settingModel->setValue($key, $value);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'mode'   => $mode,
            'pompa'  => $pompa,
            'zona'   => $zona,
            'last'   => $last,
            'result' => $result
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