<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // 1. Cek Login Dulu
        if (! session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu!');
        }

        // 2. Cek Role (PENTING!)
        if (session()->get('role') != 'admin') {
            // Kalau dia User biasa tapi coba buka /dashboard, tendang ke Home
            return redirect()->to('/')->with('error', '⛔ Akses Ditolak! Halaman ini khusus Admin.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}