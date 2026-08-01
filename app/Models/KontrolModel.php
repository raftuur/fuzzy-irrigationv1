<?php

namespace App\Models;

use CodeIgniter\Model;

class KontrolModel extends Model
{
    protected $table = 'kontrol';

    protected $primaryKey = 'id_kontrol';

    protected $allowedFields = [
        'mode',
        'pompa',
        'zona'
    ];
}