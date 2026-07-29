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
        'last_update'
    ];
}