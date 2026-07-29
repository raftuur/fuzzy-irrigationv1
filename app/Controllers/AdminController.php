<?php

namespace App\Controllers;

use App\Models\AdminModel;

class AdminController extends BaseController
{
    protected $admin;

    public function __construct()
    {
        $this->admin = new AdminModel();
    }

    public function index()
    {
        $keyword = $this->request->getGet('keyword');

        if ($keyword) {
            $admins = $this->admin
                ->groupStart()
                ->like('nama', $keyword)
                ->orLike('username', $keyword)
                ->groupEnd()
                ->paginate(10);
        } else {
            $admins = $this->admin->paginate(10);
        }

        return view('admin/index', [
            'title' => 'Data Admin',
            'admins' => $admins,
            'pager' => $this->admin->pager,
            'keyword' => $keyword
        ]);
    }

    public function create()
    {
        return view('admin/create', [
            'title' => 'Tambah Admin'
        ]);
    }

    public function store()
    {
        $rules = [
            'nama' => 'required',
            'username' => 'required|is_unique[data_admin.username]',
            'password' => 'required|min_length[4]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->admin->save([
            'nama'     => $this->request->getPost('nama'),
            'username' => $this->request->getPost('username'),
            'password' => md5($this->request->getPost('password')),
        ]);

        return redirect()->to('/admin')->with('success', 'Data admin berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $admin = $this->admin->find($id);

        if (!$admin) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('admin/edit', [
            'title' => 'Edit Admin',
            'admin' => $admin,
        ]);
    }

    public function update($id)
    {
        $admin = $this->admin->find($id);

        if (!$admin) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $rules = [
            'nama' => 'required',
            'username' => "required|is_unique[data_admin.username,id_admin,{$id}]",
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama'     => $this->request->getPost('nama'),
            'username' => $this->request->getPost('username'),
        ];

        $password = $this->request->getPost('password');

        if (!empty($password)) {
            $data['password'] = md5($password);
        }

        $this->admin->update($id, $data);

        return redirect()->to('/admin')->with('success', 'Data admin berhasil diperbarui.');
    }

    public function delete($id)
    {
        $admin = $this->admin->find($id);

        if (!$admin) {
            return redirect()->to('/admin')
                ->with('error', 'Data tidak ditemukan.');
        }

        $this->admin->delete($id);

        return redirect()->to('/admin')
            ->with('success', 'Data admin berhasil dihapus.');
    }

    public function detail($id)
    {
        $admin = $this->admin->find($id);

        if (!$admin) {
            return redirect()->to('/admin')
                ->with('error', 'Data admin tidak ditemukan.');
        }

        return view('admin/detail', [
            'title' => 'Detail Admin',
            'admin' => $admin
        ]);
    }
}