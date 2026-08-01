<?php

namespace App\Controllers;

use App\Models\KontrolModel;
use App\Models\SettingModel;
use App\Models\DeviceModel;

class KontrolController extends BaseController
{
    protected $kontrolModel;
    protected $settingModel;
    protected $deviceModel;

    public function __construct()
    {
        $this->kontrolModel = new KontrolModel();
        $this->settingModel = new SettingModel();
        $this->deviceModel = new DeviceModel();
    }

    // ============================================================
    // INDEX - Tampilan halaman kontrol
    // ============================================================
    public function index()
    {
        $kontrol = $this->kontrolModel->find(1);
        $setting = $this->settingModel->find(1);
        $device = $this->deviceModel->find('ESP32-001');

        $data = [
            'title' => 'Kontrol Irigasi',
            'kontrol' => $kontrol,
            'setting' => $setting,
            'device' => $device,
            'zona' => ['A', 'B', 'C', 'D']
        ];

        return view('kontrol/index', $data);
    }

    // ============================================================
    // SAVE - Simpan kontrol & setting
    // ============================================================
    public function save()
    {
        // ---------- 1. SIMPAN KONTROL ----------
        $mode = $this->request->getPost('mode');
        $pompa = $this->request->getPost('pompa');
        $zona = $this->request->getPost('zona');

        // Validasi
        if ($mode == 'otomatis') {
            $zona = '-';
            $pompa = 'off';
        }

        if ($zona == '' || $zona == null) {
            $zona = '-';
        }

        $kontrol = $this->kontrolModel->find(1);
        $dataKontrol = ['mode' => $mode, 'pompa' => $pompa, 'zona' => $zona];

        if ($kontrol) {
            $this->kontrolModel->update(1, $dataKontrol);
        } else {
            $this->kontrolModel->insert($dataKontrol);
        }

        // ---------- 2. SIMPAN SETTING ----------
        $dataSetting = [
            'dry_a' => $this->request->getPost('dry_a'),
            'wet_a' => $this->request->getPost('wet_a'),
            'dry_b' => $this->request->getPost('dry_b'),
            'wet_b' => $this->request->getPost('wet_b'),
            'dry_c' => $this->request->getPost('dry_c'),
            'wet_c' => $this->request->getPost('wet_c'),
            'dry_d' => $this->request->getPost('dry_d'),
            'wet_d' => $this->request->getPost('wet_d'),
            'rain_threshold' => $this->request->getPost('rain_threshold'),
            'soil_dry' => $this->request->getPost('soil_dry'),
            'soil_moist' => $this->request->getPost('soil_moist'),
            'temp_normal' => $this->request->getPost('temp_normal'),
            'temp_hot' => $this->request->getPost('temp_hot'),
            'max_duration' => $this->request->getPost('max_duration')
        ];

        $this->settingModel->update(1, $dataSetting);

        // ---------- 3. UPDATE DEVICE ----------
        $this->deviceModel->update('ESP32-001', [
            'mode' => $mode,
            'pompa' => $pompa,
            'zona' => $zona,
            'last_update' => date('Y-m-d H:i:s')
        ]);

        // ---------- 4. LOG AKTIVITAS ----------
        helper('log');
        simpanLog('Mengubah konfigurasi sistem', 'ESP32-001');

        // ---------- 5. REDIRECT ----------
        return redirect()->to('/kontrol')
                         ->with('success', 'Pengaturan berhasil disimpan.');
    }
}