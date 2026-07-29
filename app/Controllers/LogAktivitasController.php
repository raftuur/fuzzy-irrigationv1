<?php

namespace App\Controllers;

use App\Models\LogAktivitasModel;

class LogAktivitasController extends BaseController
{
    protected $log;

    public function __construct()
    {
        $this->log = new LogAktivitasModel();
    }

    public function index()
    {
        $data = [

            'log' => $this->log
                ->orderBy('created_at', 'DESC')
                ->findAll()

        ];

        return view('log/index', $data);
    }
}