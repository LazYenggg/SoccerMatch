<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\JadwalModel;

class Jadwal extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        // Query Lengkap (Join Jadwal + Tim + Pertandingan)
        // Kita butuh 'status' dari tabel pertandingan
        $jadwal = $db->table('jadwal')
            ->select('jadwal.*, t1.nama_tim as tim_home, t1.logo as logo_home, t2.nama_tim as tim_away, t2.logo as logo_away, p.status, p.skor_a, p.skor_b')
            ->join('tim as t1', 't1.id = jadwal.home')
            ->join('tim as t2', 't2.id = jadwal.away')
            ->join('pertandingan as p', 'p.id = jadwal.id', 'left') // Left join agar jadwal yang belum ada di tabel pertandingan tetap muncul
            ->orderBy('jadwal.tanggal', 'ASC')
            ->get()->getResultArray();

        $data = [
            'title'  => 'Jadwal Pertandingan | SoccerMatch',
            'jadwal' => $jadwal
        ];

        return view('user/jadwal', $data);
    }

    // Method Detail Pertandingan (Live Room)
    public function detail($id)
    {
        $db = \Config\Database::connect();

        $match = $db->table('jadwal')
            ->select('jadwal.*, t1.nama_tim as home_team, t1.logo as home_logo, t2.nama_tim as away_team, t2.logo as away_logo, p.status, p.skor_a, p.skor_b')
            ->join('tim as t1', 't1.id = jadwal.home')
            ->join('tim as t2', 't2.id = jadwal.away')
            ->join('pertandingan as p', 'p.id = jadwal.id', 'left')
            ->where('jadwal.id', $id)
            ->get()->getRowArray();

        if (!$match) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => $match['home_team'] . ' vs ' . $match['away_team'],
            'match' => $match
        ];

        return view('user/live_view', $data);
    }
}