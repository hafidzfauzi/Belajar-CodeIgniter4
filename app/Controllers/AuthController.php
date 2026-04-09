<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;
use Firebase\JWT\JWT;

class AuthController extends ResourceController
{
    protected $format = 'json';

    // POST /register -> Buat user baru (mirip create di UserController, tapi khusus auth)
    public function register()
    {
        $rules = [
            'username' => 'required',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[5]'
        ];

        // Validasi input
        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }

        $userModel = new UserModel();
        $data = [
            'username' => $this->request->getVar('username'),
            'email'    => $this->request->getVar('email'),
            'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)
        ];

        $userModel->save($data);

        return $this->respondCreated(['message' => 'User berhasil didaftarkan']);
    }

    // POST /login -> Cek email & password, lalu berikan token JWT
    public function login()
    {
        $userModel = new UserModel();
        $email    = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        $user = $userModel->where('email', $email)->first();

        if (is_null($user)) {
            return $this->failUnauthorized('Email tidak ditemukan.');
        }

        $verifyPassword = password_verify($password, $user['password']);
        if (!$verifyPassword) {
            return $this->failUnauthorized('Password salah.');
        }

        // Jika email & password cocok, buat token JWT-nya
        $key = getenv('JWT_SECRET'); // Ambil secret key dari .env, default 'bts2026' jika tidak ada
        $iat = time(); // Waktu token dibuat
        $exp = $iat + 3600; // Waktu token kadaluarsa (1 jam)

        $payload = [
            "iat"   => $iat,
            "exp"   => $exp,
            "uid"   => $user['id'],
            "email" => $user['email']
        ];

        $token = JWT::encode($payload, $key, 'HS256');

        return $this->respond([
            'message' => 'Login Berhasil',
            'token'   => $token
        ]);
    }
}