<?php

namespace App\Models;

use CodeIgniter\Model;

class RiwayatModel extends Model
{
    protected $table = 'riwayat';
    protected $primaryKey = 'id_riwayat';
    protected $returnType = 'array';
    
    protected $allowedFields = [
        'tanggal', 'suhu', 'kelembapan', 
        'tanah_a', 'tanah_b', 'tanah_c', 'tanah_d',
        'status_hujan', 'mode', 'pompa', 'zona',
        'durasi_penyiraman', 'id_device'
    ];

    // ✅ PERBAIKAN: Auto timestamps
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $dateFormat = 'datetime';

    // ✅ PERBAIKAN: Validasi data
    protected $validationRules = [
        'suhu' => 'permit_empty|numeric|greater_than[-10]|less_than[50]',
        'kelembapan' => 'permit_empty|numeric|greater_than[0]|less_than[100]',
        'tanah_a' => 'permit_empty|numeric|greater_than[0]|less_than[100]',
        'tanah_b' => 'permit_empty|numeric|greater_than[0]|less_than[100]',
        'tanah_c' => 'permit_empty|numeric|greater_than[0]|less_than[100]',
        'tanah_d' => 'permit_empty|numeric|greater_than[0]|less_than[100]',
        'durasi_penyiraman' => 'permit_empty|numeric|greater_than_equal_to[0]'
    ];

    /**
     * ✅ PERBAIKAN: Get latest data
     */
    public function getLatest()
    {
        return $this->orderBy('id_riwayat', 'DESC')->first();
    }

    /**
     * ✅ PERBAIKAN: Get data for dashboard
     */
    public function getDashboardData()
    {
        $latest = $this->getLatest();
        
        if (!$latest) {
            return null;
        }
        
        // Add calculated fields
        $latest['rata_rata_tanah'] = round(
            ($latest['tanah_a'] + $latest['tanah_b'] + 
             $latest['tanah_c'] + $latest['tanah_d']) / 4, 1
        );
        
        $latest['status_kelembapan'] = $this->getKelembapanStatus($latest['rata_rata_tanah']);
        
        return $latest;
    }

    /**
     * ✅ PERBAIKAN: Get kelembapan status
     */
    public function getKelembapanStatus($nilai)
    {
        if ($nilai < 30) {
            return 'Kering';
        } elseif ($nilai < 60) {
            return 'Sedang';
        } else {
            return 'Basah';
        }
    }

    /**
     * ✅ PERBAIKAN: Get history for charts
     */
    public function getHistory($limit = 20)
    {
        return $this
            ->select('tanggal, suhu, kelembapan, tanah_a, tanah_b, tanah_c, tanah_d')
            ->orderBy('id_riwayat', 'DESC')
            ->limit($limit)
            ->find();
    }

    /**
     * ✅ PERBAIKAN: Get statistics
     */
    public function getStatistics()
    {
        $today = date('Y-m-d');
        
        // Monitoring hari ini
        $monitoringHariIni = $this
            ->where('DATE(tanggal)', $today)
            ->countAllResults();
        
        // Penyiraman hari ini
        $penyiramanHariIni = $this
            ->where('DATE(tanggal)', $today)
            ->where('durasi_penyiraman >', 0)
            ->countAllResults();
        
        // Average suhu hari ini
        $avgSuhu = $this
            ->select('AVG(suhu) as avg_suhu')
            ->where('DATE(tanggal)', $today)
            ->first();
        
        // Average kelembapan hari ini
        $avgKelembapan = $this
            ->select('AVG(kelembapan) as avg_kelembapan')
            ->where('DATE(tanggal)', $today)
            ->first();
        
        // Min/Max suhu hari ini
        $suhuExtrem = $this
            ->select('MIN(suhu) as min_suhu, MAX(suhu) as max_suhu')
            ->where('DATE(tanggal)', $today)
            ->first();
        
        return [
            'monitoring_hari_ini' => $monitoringHariIni,
            'penyiraman_hari_ini' => $penyiramanHariIni,
            'avg_suhu' => round($avgSuhu['avg_suhu'] ?? 0, 1),
            'avg_kelembapan' => round($avgKelembapan['avg_kelembapan'] ?? 0, 1),
            'min_suhu' => round($suhuExtrem['min_suhu'] ?? 0, 1),
            'max_suhu' => round($suhuExtrem['max_suhu'] ?? 0, 1)
        ];
    }

    /**
     * ✅ PERBAIKAN: Cleanup old data (lebih dari N hari)
     */
    public function cleanupOldData($days = 7)
    {
        $tanggalBatas = date('Y-m-d H:i:s', strtotime("-$days days"));
        return $this->where('tanggal <', $tanggalBatas)->delete();
    }

    /**
     * ✅ PERBAIKAN: Get data by date range
     */
    public function getByDateRange($startDate, $endDate)
    {
        return $this
            ->where('tanggal >=', $startDate)
            ->where('tanggal <=', $endDate)
            ->orderBy('tanggal', 'ASC')
            ->find();
    }

    /**
     * ✅ PERBAIKAN: Get last N data
     */
    public function getLastN($n = 10)
    {
        return $this
            ->orderBy('id_riwayat', 'DESC')
            ->limit($n)
            ->find();
    }
}