<?php

use App\Models\LogAktivitasModel;

if (!function_exists('simpanLog')) {

    function simpanLog($aktivitas, $device = null)
    {
        $session = session();

        // Jangan simpan log jika belum login
        if (!$session->has('id_admin')) {
            return;
        }

        $model = new LogAktivitasModel();

        $model->insert([
            'id_admin'   => $session->get('id_admin'),
            'username'   => $session->get('nama'), // atau 'username' jika kamu menyimpannya di session
            'aktivitas'  => $aktivitas,
            'device_id'  => $device,
            'ip_address' => service('request')->getIPAddress()
        ]);
    }
}