<?php

namespace App\Models;

use CodeIgniter\Model;

class DeviceModel extends Model
{
    protected $table = 'device';
    protected $primaryKey = 'id_device';
    protected $returnType = 'array';

    protected $allowedFields = [
        'id_device',
        'nama_device',
        'lokasi',
        'status',
        'ip_address',
        'firmware',
        'last_update',
        'mode',
        'pompa',
        'zona',
        'durasi',
        'status_hujan'
    ];

    // ✅ PERBAIKAN: Auto timestamps
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $dateFormat = 'datetime';

    // ✅ PERBAIKAN: Default device ID
    private $defaultDeviceId = 'ESP32-001';

    // ✅ PERBAIKAN: Validasi data
    protected $validationRules = [
        'id_device' => 'required|max_length[50]',
        'nama_device' => 'permit_empty|max_length[100]',
        'lokasi' => 'permit_empty|max_length[100]',
        'status' => 'permit_empty|in_list[Online,Offline]',
        'ip_address' => 'permit_empty|valid_ip',
        'firmware' => 'permit_empty|max_length[20]',
        'mode' => 'permit_empty|in_list[otomatis,manual]',
        'pompa' => 'permit_empty|in_list[on,off]',
        'zona' => 'permit_empty|in_list[A,B,C,D,-]',
        'durasi' => 'permit_empty|numeric|greater_than_equal_to[0]',
        'status_hujan' => 'permit_empty|in_list[hujan,cerah]'
    ];

    protected $validationMessages = [
        'id_device' => [
            'required' => 'ID Device harus diisi',
            'max_length' => 'ID Device maksimal 50 karakter'
        ],
        'status' => [
            'in_list' => 'Status harus Online atau Offline'
        ],
        'mode' => [
            'in_list' => 'Mode harus otomatis atau manual'
        ],
        'pompa' => [
            'in_list' => 'Status pompa harus on atau off'
        ]
    ];

    /**
     * ✅ PERBAIKAN: Get device with default if empty
     */
    public function getDevice($idDevice = null)
    {
        $id = $idDevice ?? $this->defaultDeviceId;
        $device = $this->find($id);
        
        if (!$device) {
            // Insert default device
            $defaultData = [
                'id_device' => $id,
                'nama_device' => 'ESP32 Penyiram',
                'lokasi' => 'Green House',
                'status' => 'Offline',
                'firmware' => 'v1.0',
                'mode' => 'otomatis',
                'pompa' => 'off',
                'zona' => '-',
                'durasi' => 0,
                'status_hujan' => 'cerah'
            ];
            $this->insert($defaultData);
            $device = $this->find($id);
        }
        
        return $device;
    }

    /**
     * ✅ PERBAIKAN: Update device data
     */
    public function updateDevice($data, $idDevice = null)
    {
        $id = $idDevice ?? $this->defaultDeviceId;
        $device = $this->find($id);
        
        // Set last_update
        $data['last_update'] = date('Y-m-d H:i:s');
        
        if ($device) {
            return $this->update($id, $data);
        } else {
            $data['id_device'] = $id;
            $data['nama_device'] = $data['nama_device'] ?? 'ESP32 Penyiram';
            $data['lokasi'] = $data['lokasi'] ?? 'Green House';
            return $this->insert($data);
        }
    }

    /**
     * ✅ PERBAIKAN: Check if device is online
     */
    public function isOnline($idDevice = null)
    {
        $device = $this->getDevice($idDevice);
        
        if (!$device || empty($device['last_update'])) {
            return false;
        }
        
        $lastUpdate = strtotime($device['last_update']);
        $now = time();
        $diff = $now - $lastUpdate;
        
        // Online if last update less than 30 seconds
        return $diff <= 30;
    }

    /**
     * ✅ PERBAIKAN: Update device status based on last_update
     */
    public function updateStatus($idDevice = null)
    {
        $id = $idDevice ?? $this->defaultDeviceId;
        $device = $this->find($id);
        
        if (!$device) {
            return false;
        }
        
        $isOnline = $this->isOnline($id);
        $status = $isOnline ? 'Online' : 'Offline';
        
        return $this->update($id, ['status' => $status]);
    }

    /**
     * ✅ PERBAIKAN: Get device status
     */
    public function getStatus($idDevice = null)
    {
        $device = $this->getDevice($idDevice);
        return $device['status'] ?? 'Offline';
    }

    /**
     * ✅ PERBAIKAN: Get device last update
     */
    public function getLastUpdate($idDevice = null)
    {
        $device = $this->getDevice($idDevice);
        return $device['last_update'] ?? date('Y-m-d H:i:s');
    }

    /**
     * ✅ PERBAIKAN: Update device from sensor data
     */
    public function updateFromSensor($sensorData, $idDevice = null)
    {
        $id = $idDevice ?? $this->defaultDeviceId;
        
        $data = [
            'status' => 'Online',
            'ip_address' => $sensorData['ip_address'] ?? null,
            'firmware' => $sensorData['firmware'] ?? 'v1.0',
            'last_update' => date('Y-m-d H:i:s'),
            'mode' => $sensorData['mode'] ?? 'otomatis',
            'pompa' => $sensorData['pompa'] ?? 'off',
            'zona' => $sensorData['zona'] ?? '-',
            'durasi' => $sensorData['durasi'] ?? 0,
            'status_hujan' => $sensorData['status_hujan'] ?? 'cerah'
        ];
        
        return $this->updateDevice($data, $id);
    }

    /**
     * ✅ PERBAIKAN: Get device info for dashboard
     */
    public function getDashboardInfo($idDevice = null)
    {
        $device = $this->getDevice($idDevice);
        
        if ($device) {
            $device['online'] = $this->isOnline($idDevice);
            $device['is_online'] = $device['online'];
            $device['last_seen'] = $device['last_update'] ?? date('Y-m-d H:i:s');
            
            // Add human readable last update
            $lastUpdate = strtotime($device['last_seen']);
            $now = time();
            $diff = $now - $lastUpdate;
            
            if ($diff < 60) {
                $device['last_seen_human'] = $diff . ' detik yang lalu';
            } elseif ($diff < 3600) {
                $device['last_seen_human'] = floor($diff / 60) . ' menit yang lalu';
            } elseif ($diff < 86400) {
                $device['last_seen_human'] = floor($diff / 3600) . ' jam yang lalu';
            } else {
                $device['last_seen_human'] = date('d M Y H:i', $lastUpdate);
            }
        }
        
        return $device;
    }
}