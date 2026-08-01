<?php

namespace App\Models;

use CodeIgniter\Model;

class KontrolModel extends Model
{
    protected $table = 'data_kontrol';
    protected $primaryKey = 'id_kontrol';
    protected $returnType = 'array';
    
    protected $allowedFields = [
        'mode',
        'pompa',
        'zona'
    ];

    // ✅ PERBAIKAN: Auto timestamps
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $dateFormat = 'datetime';

    // ✅ PERBAIKAN: Validasi data
    protected $validationRules = [
        'mode' => 'required|in_list[otomatis,manual]',
        'pompa' => 'required|in_list[on,off]',
        'zona' => 'permit_empty|in_list[A,B,C,D,-]'
    ];

    protected $validationMessages = [
        'mode' => [
            'required' => 'Mode harus diisi',
            'in_list' => 'Mode harus otomatis atau manual'
        ],
        'pompa' => [
            'required' => 'Status pompa harus diisi',
            'in_list' => 'Status pompa harus on atau off'
        ],
        'zona' => [
            'in_list' => 'Zona harus A, B, C, D, atau -'
        ]
    ];

    // ✅ PERBAIKAN: Default values
    protected $defaults = [
        'mode' => 'otomatis',
        'pompa' => 'off',
        'zona' => '-'
    ];

    /**
     * ✅ PERBAIKAN: Get kontrol with default if empty
     */
    public function getKontrol()
    {
        $kontrol = $this->find(1);
        
        if (!$kontrol) {
            // Insert default
            $this->insert($this->defaults);
            $kontrol = $this->find(1);
        }
        
        return $kontrol;
    }

    /**
     * ✅ PERBAIKAN: Update kontrol safely
     */
    public function updateKontrol($data)
    {
        // Validasi data
        $validData = [];
        
        if (isset($data['mode']) && in_array($data['mode'], ['otomatis', 'manual'])) {
            $validData['mode'] = $data['mode'];
        }
        
        if (isset($data['pompa']) && in_array($data['pompa'], ['on', 'off'])) {
            $validData['pompa'] = $data['pompa'];
        }
        
        if (isset($data['zona']) && in_array($data['zona'], ['A', 'B', 'C', 'D', '-'])) {
            $validData['zona'] = $data['zona'];
        }
        
        if (empty($validData)) {
            return false;
        }
        
        $kontrol = $this->find(1);
        
        if ($kontrol) {
            return $this->update(1, $validData);
        } else {
            $validData = array_merge($this->defaults, $validData);
            return $this->insert($validData);
        }
    }

    /**
     * ✅ PERBAIKAN: Get current mode
     */
    public function getMode()
    {
        $kontrol = $this->getKontrol();
        return $kontrol['mode'] ?? 'otomatis';
    }

    /**
     * ✅ PERBAIKAN: Get current pompa status
     */
    public function getPompaStatus()
    {
        $kontrol = $this->getKontrol();
        return $kontrol['pompa'] ?? 'off';
    }

    /**
     * ✅ PERBAIKAN: Get current zona
     */
    public function getZona()
    {
        $kontrol = $this->getKontrol();
        return $kontrol['zona'] ?? '-';
    }

    /**
     * ✅ PERBAIKAN: Check if manual mode
     */
    public function isManual()
    {
        return $this->getMode() === 'manual';
    }

    /**
     * ✅ PERBAIKAN: Check if pompa is on
     */
    public function isPompaOn()
    {
        return $this->getPompaStatus() === 'on';
    }
}