<?php

namespace App\Controllers;

use App\Models\DeviceModel;
use App\Models\RiwayatModel;
use App\Models\LogAktivitasModel;

class Dashboard extends BaseController
{
    public function index()
    {
        // ========== INISIALISASI MODEL ==========
        $deviceModel = new DeviceModel();
        $riwayatModel = new RiwayatModel();
        $logModel = new LogAktivitasModel();

        // ========== STATISTIK ==========
        // Device Online
        $deviceOnline = $deviceModel
            ->where('status', 'Online')
            ->countAllResults();

        // Monitoring Hari Ini
        $monitoringHariIni = $riwayatModel
            ->where('DATE(tanggal)', date('Y-m-d'))
            ->countAllResults();

        // Penyiraman Hari Ini
        $penyiramanHariIni = $riwayatModel
            ->where('DATE(tanggal)', date('Y-m-d'))
            ->where('durasi_penyiraman >', 0)
            ->countAllResults();

        // Aktivitas Admin Hari Ini
        $aktivitasAdmin = $logModel
            ->where('DATE(created_at)', date('Y-m-d'))
            ->countAllResults();

        // ========== DATA GRAFIK ==========
        $chartData = $riwayatModel
            ->select('tanggal, suhu, kelembapan')
            ->orderBy('id_riwayat', 'DESC')
            ->limit(20)
            ->find();

        // Urutkan dari lama ke terbaru
        $chartData = array_reverse($chartData);

        // ========== DATA TERBARU ==========
        $device = $deviceModel->find('ESP32-001');
        $riwayat = $riwayatModel->orderBy('id_riwayat', 'DESC')->first();

        // ========== KIRIM KE VIEW ==========
        return view('dashboard/index', [
            'title' => 'Dashboard Irigasi',
            'deviceOnline' => $deviceOnline,
            'monitoringHariIni' => $monitoringHariIni,
            'penyiramanHariIni' => $penyiramanHariIni,
            'aktivitasAdmin' => $aktivitasAdmin,
            'chartData' => $chartData,
            'device' => $device,
            'riwayat' => $riwayat
        ]);
    }
}