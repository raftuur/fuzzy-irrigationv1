<?php

namespace App\Controllers;

use Config\Database;

class Dashboard extends BaseController
{
    public function index()
    {
        $db = Database::connect();

        // Device Online
        $deviceOnline = $db->table('device')
            ->where('status', 'Online')
            ->countAllResults();

        // Monitoring Hari Ini
        $monitoringHariIni = $db->table('data_riwayat')
            ->where('DATE(tanggal)', date('Y-m-d'))
            ->countAllResults();

        // Penyiraman Hari Ini
        $penyiramanHariIni = $db->table('data_riwayat')
            ->where('DATE(tanggal)', date('Y-m-d'))
            ->where('durasi_penyiraman >', 0)
            ->countAllResults();

        // Aktivitas Admin Hari Ini
        $aktivitasAdmin = $db->table('log_aktivitas')
            ->where('DATE(created_at)', date('Y-m-d'))
            ->countAllResults();

        // 20 data monitoring terakhir
        $chartData = $db->table('data_riwayat')
            ->select('tanggal, suhu, kelembapan')
            ->orderBy('id_riwayat', 'DESC')
            ->limit(20)
            ->get()
            ->getResultArray();

        // Urutkan kembali agar grafik dari data lama → terbaru
        $chartData = array_reverse($chartData);

        return view('dashboard/index', [

            'title' => 'Dashboard',

            'deviceOnline' => $deviceOnline,

            'monitoringHariIni' => $monitoringHariIni,

            'penyiramanHariIni' => $penyiramanHariIni,

            'aktivitasAdmin' => $aktivitasAdmin,

            'chartData' => $chartData

        ]);
    }
}