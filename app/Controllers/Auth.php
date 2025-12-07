<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Auth extends BaseController
{
    public function index()
    {
        // Kalau sudah login, lempar sesuai role
        if (session()->get('logged_in')) {
            return $this->redirectBasedOnRole();
        }
        return view('auth/login');
    }

    public function process()
    {
        $session = session();
        $db = \Config\Database::connect();
        
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        $query = $db->table('users')->where('username', $username)->get();
        $data = $query->getRowArray();

        if ($data) {
            if (password_verify($password, $data['password'])) {
                $sessData = [
                    'id'        => $data['id'],
                    'username'  => $data['username'],
                    'role'      => $data['role'],
                    'logged_in' => TRUE
                ];
                $session->set($sessData);
                
                return $this->redirectBasedOnRole();
            } else {
                return redirect()->back()->with('error', 'Password Salah!');
            }
        } else {
            return redirect()->back()->with('error', 'Username tidak ditemukan!');
        }
    }

    private function redirectBasedOnRole()
    {
        if (session()->get('role') == 'admin') {
            return redirect()->to('/dashboard')->with('success', 'Selamat Datang, Admin!');
        } else {
            return redirect()->to('/')->with('success', 'Login Berhasil!');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    public function register()
    {
        if (session()->get('logged_in')) {
            return $this->redirectBasedOnRole();
        }
        return view('auth/register');
    }

    public function process_register()
    {
        // Definisi Rules dengan Pesan Bahasa Indonesia
        $rules = [
            'username' => [
                'rules' => 'required|min_length[4]|is_unique[users.username]',
                'errors' => [
                    'required'   => 'Username wajib diisi.',
                    'min_length' => 'Username minimal harus 4 karakter.',
                    'is_unique'  => 'Username ini sudah terpakai. Silakan pilih yang lain.'
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[4]',
                'errors' => [
                    'required'   => 'Password wajib diisi.',
                    'min_length' => 'Password minimal harus 4 karakter.'
                ]
            ],
            'password_conf' => [
                'rules' => 'matches[password]',
                'errors' => [
                    'matches' => 'Konfirmasi password tidak cocok dengan password.'
                ]
            ]
        ];

        // Jalankan Validasi
        if (!$this->validate($rules)) {
            // Ambil error dalam bentuk list
            $validation = \Config\Services::validation();
            return redirect()->back()->withInput()->with('error', $validation->listErrors());
        }

        // Simpan ke DB
        $db = \Config\Database::connect();
        $db->table('users')->insert([
            'username' => $this->request->getVar('username'),
            'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
            'role'     => 'user' 
        ]);

        return redirect()->to('/login')->with('success', 'Registrasi Berhasil! Silakan Login.');
    }
}