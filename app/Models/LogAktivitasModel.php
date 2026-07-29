<?php

namespace App\Models;

use CodeIgniter\Model;

class LogAktivitasModel extends Model
{
    protected $table = 'log_aktivitas';

    protected $primaryKey = 'id_log';

    protected $returnType = 'array';

    protected $allowedFields = [
        'id_admin',
        'username',
        'aktivitas',
        'device_id',
        'ip_address',
        'created_at'
    ];
}