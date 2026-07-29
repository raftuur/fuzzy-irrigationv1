<?php

namespace App\Models;

use CodeIgniter\Model;

class RiwayatModel extends Model
{
    protected $table = 'data_riwayat';

    protected $primaryKey = 'id_riwayat';

    protected $returnType = 'array';

    protected $allowedFields = [
        'tanggal',
        'suhu',
        'kelembapan',
        'tanah_a',
        'tanah_b',
        'tanah_c',
        'tanah_d',
        'status_hujan',
        'mode',
        'zona',
        'durasi_penyiraman'
    ];

    // Optional: Tambahkan timestamps jika tabel memiliki created_at/updated_at
    // protected $useTimestamps = true;
    // protected $createdField = 'created_at';
    // protected $updatedField = 'updated_at';

    // Optional: Format tanggal
    // protected $dateFormat = 'datetime';

    // Optional: Validasi jika diperlukan
    // protected $validationRules = [
    //     'tanggal' => 'required',
    //     'suhu' => 'numeric',
    //     'kelembapan' => 'numeric',
    // ];
}