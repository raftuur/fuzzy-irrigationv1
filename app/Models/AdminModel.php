<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table = 'data_admin';
    protected $primaryKey = 'id_admin';

    protected $allowedFields = [
        'nama',
        'username',
        'password'
    ];

    protected $returnType = 'array';
}