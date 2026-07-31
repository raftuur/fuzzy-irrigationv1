<?php

namespace App\Controllers;

use App\Models\KontrolModel;

class KontrolController extends BaseController
{
    protected $kontrol;

    public function __construct()
    {
        $this->kontrol = new KontrolModel();
    }

    public function save()
    {
        log_message('error', json_encode($this->request->getPost()));

        // kode lama...
        $mode = $this->request->getPost('mode');
        $pompa = $this->request->getPost('pompa');
        $zona = $this->request->getPost('zona');

        // ... lanjutkan kode yang sudah ada
    }
}