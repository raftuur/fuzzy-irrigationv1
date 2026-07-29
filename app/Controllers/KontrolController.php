<?php

namespace App\Controllers;

use App\Models\KontrolModel;
use App\Models\SettingModel;

class KontrolController extends BaseController
{
    protected $kontrol;
    protected $setting;

    public function __construct()
    {
        $this->kontrol = new KontrolModel();
        $this->setting = new SettingModel();
    }

    public function index()
    {
        $kontrol = $this->kontrol
                        ->orderBy('id_kontrol', 'DESC')
                        ->first();

        // ==========================================
        // PERBAIKAN: Gunakan find(1) langsung
        // ==========================================
        $setting = $this->setting->find(1);

        return view('kontrol/index', [
            'kontrol' => $kontrol,
            'setting' => $setting
        ]);
    }

    public function save()
    {
        // =========================
        // SIMPAN KONTROL
        // =========================
        $last = $this->kontrol
                     ->orderBy('id_kontrol', 'DESC')
                     ->first();

        $dataKontrol = [
            'mode'  => $this->request->getPost('mode'),
            'pompa' => $this->request->getPost('pompa'),
            'zona'  => $this->request->getPost('zona')
        ];

        if ($last) {
            $this->kontrol->update($last['id_kontrol'], $dataKontrol);
        } else {
            $this->kontrol->insert($dataKontrol);
        }

        // =========================
        // SIMPAN SETTING
        // =========================
        $dataSetting = [
            'dry_a'          => $this->request->getPost('dry_a'),
            'wet_a'          => $this->request->getPost('wet_a'),

            'dry_b'          => $this->request->getPost('dry_b'),
            'wet_b'          => $this->request->getPost('wet_b'),

            'dry_c'          => $this->request->getPost('dry_c'),
            'wet_c'          => $this->request->getPost('wet_c'),

            'dry_d'          => $this->request->getPost('dry_d'),
            'wet_d'          => $this->request->getPost('wet_d'),

            'rain_threshold' => $this->request->getPost('rain_threshold'),

            'soil_dry'       => $this->request->getPost('soil_dry'),
            'soil_moist'     => $this->request->getPost('soil_moist'),

            'temp_normal'    => $this->request->getPost('temp_normal'),
            'temp_hot'       => $this->request->getPost('temp_hot'),

            'max_duration'   => $this->request->getPost('max_duration')
        ];

        $this->setting->update(1, $dataSetting);

        helper('log');
        simpanLog('Mengubah konfigurasi sistem', 'ESP32-001');

        return redirect()->to('/kontrol')
                         ->with('success', 'Pengaturan berhasil disimpan.');
    }
}