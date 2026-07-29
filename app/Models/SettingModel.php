<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table = 'setting';
    protected $primaryKey = 'id_setting';

    protected $returnType = 'array';

    protected $allowedFields = [

        'dry_a',
        'wet_a',

        'dry_b',
        'wet_b',

        'dry_c',
        'wet_c',

        'dry_d',
        'wet_d',

        'rain_threshold',

        'soil_dry',
        'soil_moist',

        'temp_normal',
        'temp_hot',

        'max_duration'
    ];
}