<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\JadwalModel;
use App\Models\TimModel;

class Jadwal extends BaseController
{
    protected $jadwalModel;
    protected $timModel;

    public function __construct()
    {
        $this->jadwalModel = new JadwalModel();
        $this->timModel = new TimModel();
    }

    public function index()
    {
        $data = [
            'title'  => 'Kelola Jadwal | Admin',
            'jadwal' => $this->jadwalModel->getJadwalLengkap()
        ];

        return view('admin/jadwal/index', $data);
    }

    public function tambah()
    {
        $data = [
            'title' => 'Tambah Jadwal Baru',
            // Ambil semua tim
            'tim'   => $this->timModel->orderBy('nama_tim', 'ASC')->findAll(),
            // Ambil daftar liga unik untuk filter dropdown
            'ligas' => $this->timModel->distinct()->select('liga')->orderBy('liga', 'ASC')->findAll()
        ];

        return view('admin/jadwal/tambah', $data);
    }

    public function simpan()
    {
        // 1. Validasi Input Dasar
        if (!$this->validate([
            'home' => 'required',
            'away' => 'required',
            'tanggal' => 'required'
        ])) {
            return redirect()->back()->withInput()->with('error', 'Data Home, Away, dan Tanggal wajib diisi!');
        }

        $idHome = $this->request->getVar('home');
        $idAway = $this->request->getVar('away');

        // 2. Validasi Tim Sama
        if ($idHome == $idAway) {
            return redirect()->back()->withInput()->with('error', 'Tim Home dan Away tidak boleh sama!');
        }

        // 3. Validasi Beda Liga
        $teamHome = $this->timModel->find($idHome);
        $teamAway = $this->timModel->find($idAway);

        if ($teamHome['liga'] != $teamAway['liga']) { // Asumsi kolom di DB namanya 'liga'
            return redirect()->back()->withInput()->with('error', "Gagal! {$teamHome['nama_tim']} dan {$teamAway['nama_tim']} beda liga.");
        }

        // 4. VALIDASI DUPLIKAT KANDANG (Rule: Arsenal vs MU home tidak boleh 2x)
        $cekKandang = $this->jadwalModel->where('home', $idHome)->where('away', $idAway)->countAllResults();
        if ($cekKandang > 0) {
            return redirect()->back()->withInput()->with('error', "Gagal! {$teamHome['nama_tim']} sudah pernah menjadi tuan rumah melawan {$teamAway['nama_tim']}.");
        }

        // 5. VALIDASI MAX PERTEMUAN (Rule: Max 2x ketemu per musim - Home & Away)
        // Cek berapa kali mereka sudah bertemu (baik home maupun away)
        $db = \Config\Database::connect();
        $totalKetemu = $db->table('jadwal')
            ->groupStart()
                ->where('home', $idHome)->where('away', $idAway)
            ->groupEnd()
            ->orGroupStart()
                ->where('home', $idAway)->where('away', $idHome)
            ->groupEnd()
            ->countAllResults();

        if ($totalKetemu >= 2) {
            return redirect()->back()->withInput()->with('error', "Gagal! Kedua tim sudah bertemu 2 kali (Home & Away) musim ini.");
        }

        // Venue Otomatis
        $venueFix = $teamHome['stadion'];

        $this->jadwalModel->save([
            'tanggal' => $this->request->getVar('tanggal'),
            'home'    => $idHome,
            'away'    => $idAway,
            'venue'   => $venueFix,
        ]);

        return redirect()->to('/dashboard/jadwal')->with('success', 'Jadwal berhasil ditambahkan!');
    }

    // ... (Method edit, update, hapus biarkan sama seperti sebelumnya)
    public function edit($id) { return view('admin/jadwal/edit', ['title'=>'Edit', 'jadwal'=>$this->jadwalModel->find($id), 'tim'=>$this->timModel->findAll()]); }
    public function update($id) { /* Kode Update sama, tambahkan validasi seperti simpan() jika mau strict */ }
    public function hapus($id) { $this->jadwalModel->delete($id); return redirect()->to('/dashboard/jadwal')->with('success', 'Dihapus'); }
}