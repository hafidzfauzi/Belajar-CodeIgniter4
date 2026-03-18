<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class UserController extends ResourceController
{
    // Inisialisasi model dan format response otomatis ke JSON
    protected $modelName = 'App\Models\UserModel';
    protected $format    = 'json';

    // GET /users -> Ambil semua data
    public function index()
    {
        return $this->respond($this->model->findAll());
    }

    // GET /users/{id} -> Ambil 1 user tertentu
    public function show($id = null)
    {
        $data = $this->model->find($id);
        if ($data) {
            return $this->respond($data);
        }
        return $this->failNotFound('Data user tidak ditemukan.');
    }

    // POST /users -> Tambah user baru
    public function create()
    {
        $data = $this->request->getPost();

        // Enkripsi password sebelum disimpan ke database
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        if ($this->model->insert($data)) {
            return $this->respondCreated([
                'status'  => 201,
                'message' => 'User berhasil dibuat'
            ]);
        }
        return $this->fail($this->model->errors());
    }

    // PUT /users/{id} -> Update data user
    public function update($id = null)
    {
        // Untuk method PUT, CI4 menggunakan getRawInput()
        $data = $this->request->getRawInput();

        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        if ($this->model->update($id, $data)) {
            return $this->respond([
                'status'  => 200,
                'message' => 'Data user berhasil diupdate'
            ]);
        }
        return $this->failNotFound('Data gagal diupdate atau ID tidak ditemukan.');
    }

    // DELETE /users/{id} -> Hapus user
    public function delete($id = null)
    {
        $data = $this->model->find($id);
        if ($data) {
            $this->model->delete($id);
            return $this->respondDeleted([
                'status'  => 200,
                'message' => 'Data user berhasil dihapus'
            ]);
        }
        return $this->failNotFound('Data tidak ditemukan.');
    }
}