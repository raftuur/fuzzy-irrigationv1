<?php

namespace App\Controllers;

use App\Models\DeviceModel;
use App\Models\RiwayatModel;
use App\Models\KontrolModel;

class DeviceController extends BaseController
{
    protected $device;
    protected $riwayat;
    protected $kontrol;

    public function __construct()
    {
        helper('device');

        $this->device   = new DeviceModel();
        $this->riwayat  = new RiwayatModel();
        $this->kontrol  = new KontrolModel();
    }

    public function index()
    {
        $deviceList = $this->device->findAll();

        // Cek status offline otomatis (jika > 2 menit tidak update)
        foreach ($deviceList as &$d) {

            if (!empty($d['last_update'])) {

                $selisih = time() - strtotime($d['last_update']);

                if ($selisih > 120) { // 2 menit = 120 detik

                    $this->device->update($d['id_device'], [
                        'status' => 'Offline'
                    ]);

                    $d['status'] = 'Offline';
                }
            }
        }

        $data = [
            'device' => $deviceList
        ];

        return view('device/index', $data);
    }

    public function detail($id)
    {
        $device = $this->device->find($id);

        if (!$device) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        helper('log');
        simpanLog('Melihat detail device', $id);

        $sensor = $this->riwayat
            ->where('id_device', $id)
            ->orderBy('id_riwayat', 'DESC')
            ->first();

        $kontrol = $this->kontrol
                        ->orderBy('id_kontrol', 'DESC')
                        ->first();

        return view('device/detail', [
            'device'  => $device,
            'sensor'  => $sensor,
            'kontrol' => $kontrol
        ]);
    }
}