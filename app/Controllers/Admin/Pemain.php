<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PemainModel;
use App\Models\TimModel;

class Pemain extends BaseController
{
    protected $pemainModel;
    protected $timModel;

    public function __construct()
    {
        $this->pemainModel = new PemainModel();
        $this->timModel = new TimModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Kelola Data Pemain',
            'pemain' => $this->pemainModel->getPemainLengkap()
        ];

        return view('admin/pemain/index', $data);
    }

    public function tambah()
    {
        $data = [
            'title' => 'Tambah Pemain Baru',
            'tim'   => $this->timModel->orderBy('nama_tim', 'ASC')->findAll()
        ];

        return view('admin/pemain/tambah', $data);
    }

    public function simpan()
    {
        if (!$this->validate([
            'nama_pemain' => 'required',
            'posisi'      => 'required',
            'id_tim'      => 'required'
        ])) {
            return redirect()->back()->withInput()->with('error', 'Semua data wajib diisi!');
        }

        $this->pemainModel->save([
            'nama_pemain' => $this->request->getVar('nama_pemain'),
            'posisi'      => $this->request->getVar('posisi'),
            'id_tim'      => $this->request->getVar('id_tim')
        ]);

        return redirect()->to('/dashboard/pemain')->with('success', 'Pemain berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $pemain = $this->pemainModel->find($id);
        
        if (empty($pemain)) {
            return redirect()->to('/dashboard/pemain')->with('error', 'Data tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Pemain',
            'pemain'=> $pemain,
            'tim'   => $this->timModel->orderBy('nama_tim', 'ASC')->findAll()
        ];

        return view('admin/pemain/edit', $data);
    }

    public function update($id)
    {
        if (!$this->validate([
            'nama_pemain' => 'required',
            'posisi'      => 'required',
            'id_tim'      => 'required'
        ])) {
            return redirect()->back()->withInput()->with('error', 'Semua data wajib diisi!');
        }

        $this->pemainModel->update($id, [
            'nama_pemain' => $this->request->getVar('nama_pemain'),
            'posisi'      => $this->request->getVar('posisi'),
            'id_tim'      => $this->request->getVar('id_tim')
        ]);

        return redirect()->to('/dashboard/pemain')->with('success', 'Data pemain berhasil diperbarui!');
    }

    public function hapus($id)
    {
        $this->pemainModel->delete($id);
        return redirect()->to('/dashboard/pemain')->with('success', 'Pemain berhasil dihapus!');
    }
}