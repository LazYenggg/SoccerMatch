<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Skor extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        // Ambil jadwal yang statusnya BUKAN 'selesai' (agar admin fokus ke yang aktif)
        // Atau ambil semua tapi urutkan yang live di atas
        $matches = $db->table('jadwal')
            ->select('jadwal.*, t1.nama_tim as home_team, t1.logo as home_logo, t2.nama_tim as away_team, t2.logo as away_logo, p.status, p.skor_a, p.skor_b')
            ->join('tim as t1', 't1.id = jadwal.home')
            ->join('tim as t2', 't2.id = jadwal.away')
            ->join('pertandingan as p', 'p.id = jadwal.id', 'left')
            ->orderBy('FIELD(p.status, "berlangsung", "halftime", "belum", "selesai")') // Prioritaskan yang LIVE
            ->orderBy('jadwal.tanggal', 'ASC')
            ->get()->getResultArray();

        $data = [
            'title' => 'Pilih Pertandingan | Input Skor',
            'matches' => $matches
        ];

        return view('admin/skor/index', $data);
    }
}