<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Config\Services;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $key = getenv('JWT_SECRET'); // Ambil secret key dari .env, default 'bts2026' jika tidak ada
        $header = $request->getHeaderLine('Authorization');
        $token = null;

        // Ekstrak token dari header Authorization: Bearer <token>
        if (!empty($header)) {
            if (preg_match('/Bearer\s(\S+)/', $header, $matches)) {
                $token = $matches[1];
            }
        }

        // Cek apakah token ada
        if (is_null($token) || empty($token)) {
            $response = Services::response();
            $response->setJSON(['message' => 'Akses ditolak, Token tidak ditemukan']);
            return $response->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        try {
            // Verifikasi token
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
        } catch (\Exception $ex) {
            $response = Services::response();
            $response->setJSON(['message' => 'Akses ditolak, Token tidak valid atau sudah expired']);
            return $response->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak perlu diisi
    }
}