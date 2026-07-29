<?php

namespace App\Controllers;

use App\Models\SettingModel;

class SettingController extends BaseController
{
    protected $setting;

    public function __construct()
    {
        $this->setting = new SettingModel();
    }

    public function index()
    {
        $data['setting'] = [];

        foreach ($this->setting->findAll() as $row) {
            $data['setting'][$row['setting_key']] = $row['setting_value'];
        }

        return view('kontrol', $data);
    }

    public function save()
    {
        foreach ($this->request->getPost() as $key => $value) {

            if ($key == csrf_token()) {
                continue;
            }

            $this->setting->setValue($key, $value);
        }

        return redirect()->back()->with(
            'success',
            'Pengaturan berhasil disimpan.'
        );
    }
}