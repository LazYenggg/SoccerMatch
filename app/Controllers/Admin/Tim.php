<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TimModel;

class Tim extends BaseController
{
    protected $timModel;

    public function __construct()
    {
        $this->timModel = new TimModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Kelola Data Tim',
            // Urutkan berdasarkan Liga lalu Nama Tim
            'tims'  => $this->timModel->orderBy('liga', 'ASC')->orderBy('nama_tim', 'ASC')->findAll()
        ];

        return view('admin/tim/index', $data);
    }

    public function tambah()
    {
        $data = ['title' => 'Tambah Tim Baru'];
        return view('admin/tim/tambah', $data);
    }

    public function simpan()
    {
        if (!$this->validate([
            'nama_tim' => 'required|is_unique[tim.nama_tim]',
            'kota'     => 'required',
            'stadion'  => 'required',
            'liga'     => 'required',
            'logo'     => [
                'rules' => 'uploaded[logo]|max_size[logo,1024]|is_image[logo]|mime_in[logo,image/jpg,image/jpeg,image/png,image/svg+xml]',
                'errors' => [
                    'uploaded' => 'Pilih gambar logo terlebih dahulu',
                    'max_size' => 'Ukuran gambar terlalu besar (Max 1MB)',
                    'is_image' => 'File yang dipilih bukan gambar',
                    'mime_in'  => 'File yang dipilih bukan gambar valid'
                ]
            ]
        ])) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        // Handle Upload Gambar
        $fileLogo = $this->request->getFile('logo');
        
        // Pindahkan file ke folder public/img
        // Kita pakai nama random biar aman
        $namaLogo = $fileLogo->getRandomName(); 
        $fileLogo->move('img', $namaLogo);

        $this->timModel->save([
            'nama_tim' => $this->request->getVar('nama_tim'),
            'kota'     => $this->request->getVar('kota'),
            'stadion'  => $this->request->getVar('stadion'),
            'liga'     => $this->request->getVar('liga'),
            'logo'     => $namaLogo
        ]);

        return redirect()->to('/dashboard/tim')->with('success', 'Tim berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $tim = $this->timModel->find($id);
        if (empty($tim)) {
            return redirect()->to('/dashboard/tim')->with('error', 'Tim tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Data Tim',
            'tim'   => $tim
        ];

        return view('admin/tim/edit', $data);
    }

    public function update($id)
    {
        // Cek dulu apakah user upload logo baru?
        $fileLogo = $this->request->getFile('logo');
        $timLama  = $this->timModel->find($id);

        // Aturan validasi
        $rules = [
            'nama_tim' => "required", // Uniqueness diabaikan kalau nama tidak berubah, logic simple aja
            'kota'     => 'required',
            'stadion'  => 'required',
            'liga'     => 'required'
        ];

        // Jika ada file yang diupload, tambah rules
        if ($fileLogo->getError() != 4) {
            $rules['logo'] = 'max_size[logo,1024]|is_image[logo]|mime_in[logo,image/jpg,image/jpeg,image/png,image/svg+xml]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        $namaLogo = $timLama['logo']; // Default pake lama

        if ($fileLogo->getError() != 4) {
            // Upload yang baru
            $namaLogo = $fileLogo->getRandomName();
            $fileLogo->move('img', $namaLogo);

            // Hapus yang lama (Opsional, kalau mau hemat storage)
            // if ($timLama['logo'] != 'default.png') {
            //    unlink('img/' . $timLama['logo']);
            // }
        }

        $this->timModel->update($id, [
            'nama_tim' => $this->request->getVar('nama_tim'),
            'kota'     => $this->request->getVar('kota'),
            'stadion'  => $this->request->getVar('stadion'),
            'liga'     => $this->request->getVar('liga'),
            'logo'     => $namaLogo
        ]);

        return redirect()->to('/dashboard/tim')->with('success', 'Data Tim berhasil diupdate!');
    }

    public function hapus($id)
    {
        $this->timModel->delete($id);
        return redirect()->to('/dashboard/tim')->with('success', 'Tim berhasil dihapus!');
    }
}