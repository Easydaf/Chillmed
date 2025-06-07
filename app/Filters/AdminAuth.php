<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Cek apakah user sudah login
        if (!session()->get('isLoggedIn')) {
            // Jika belum login, redirect ke halaman login dengan pesan error
            return redirect()->to('/')->with('error', 'Anda harus login untuk mengakses halaman ini.');
        }

        // Cek apakah user adalah admin
        // 'user' adalah key di session yang menyimpan data user setelah login
        if (session()->get('user')['role'] !== 'admin') {
            // Jika bukan admin, redirect ke dashboard user biasa dengan pesan error
            return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki izin untuk mengakses halaman admin.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak ada aksi setelah request
    }
}