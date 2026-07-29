<?php

namespace App\Controllers;

use App\Models\AdminModel;

class Auth extends BaseController
{
    public function index()
    {
        return view('auth/login');
    }

    public function login()
{
    $username = $this->request->getPost('username');
    $password = md5($this->request->getPost('password'));

    $adminModel = new AdminModel();

    $admin = $adminModel
        ->where('username', $username)
        ->where('password', $password)
        ->first();

    if ($admin) {
        session()->set([
            'id_admin' => $admin['id_admin'],
            'nama'     => $admin['nama'],
            'username' => $admin['username'],
            'login'    => true,
        ]);

        helper('log');
        simpanLog('Login ke sistem');

        return redirect()->to('/dashboard');
    } else {
        return redirect()->back()->with('error', 'Username atau Password salah.');
    }
}

    public function logout()
    {
        helper('log');
        simpanLog('Logout');

        session()->destroy();

        return redirect()->to('/');
    }
}