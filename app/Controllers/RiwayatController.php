<?php

namespace App\Controllers;

use App\Models\RiwayatModel;

class RiwayatController extends BaseController
{
    protected $riwayat;

    public function __construct()
    {
        $this->riwayat = new RiwayatModel();
    }

    public function index()
    {
        $mode = $this->request->getGet('mode');
        $zona = $this->request->getGet('zona');
        $status_hujan = $this->request->getGet('status_hujan');

        $tanggal_awal = $this->request->getGet('tanggal_awal');
        $tanggal_akhir = $this->request->getGet('tanggal_akhir');

        $builder = $this->riwayat;

        if (!empty($tanggal_awal) && !empty($tanggal_akhir)) {

            $builder->where('DATE(tanggal) >=', $tanggal_awal)
                    ->where('DATE(tanggal) <=', $tanggal_akhir);

        }

        if (!empty($mode)) {

            $builder->where('mode', $mode);

        }

        if (!empty($zona)) {

            $builder->where('zona', $zona);

        }

        if (!empty($status_hujan)) {

            $builder->where('status_hujan', $status_hujan);

        }

        $data = $builder
                    ->orderBy('id_riwayat','DESC')
                    ->paginate(10);

        $db = \Config\Database::connect();

        $statistik = $db->table('data_riwayat')
            ->select("
                COUNT(*) total_data,
                AVG(suhu) rata_suhu,
                AVG(kelembapan) rata_kelembapan,
                SUM(durasi_penyiraman) total_durasi,
                COUNT(CASE WHEN durasi_penyiraman > 0 THEN 1 END) total_penyiraman
            ")
            ->get()
            ->getRowArray();

        $grafik = $this->riwayat
            ->select('tanggal,suhu,kelembapan,tanah_a,tanah_b,tanah_c,tanah_d')
            ->orderBy('id_riwayat','DESC')
            ->limit(7)
            ->find();

        $grafik = array_reverse($grafik);

        return view('riwayat/index',[
            'title'=>'Data Riwayat',
            'riwayat'=>$data,
            'pager'=>$this->riwayat->pager,
            'statistik'=>$statistik,
            'mode'=>$mode,
            'zona'=>$zona,
            'status_hujan'=>$status_hujan,
            'tanggal_awal'=>$tanggal_awal,
            'tanggal_akhir'=>$tanggal_akhir,
            'grafik'=>$grafik
        ]);
    }
}