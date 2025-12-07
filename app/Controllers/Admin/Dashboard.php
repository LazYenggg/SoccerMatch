<?php

namespace App\Controllers\Admin; // Namespace khusus Admin

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        // Mengambil Statistik Ringkas untuk Dashboard
        $data = [
            'title'       => 'Dashboard Admin | SoccerMatch',
            'total_tim'   => $db->table('tim')->countAll(),
            'total_match' => $db->table('jadwal')->countAll(),
            'match_live'  => $db->table('pertandingan')->where('status', 'berlangsung')->countAllResults(),
            'match_done'  => $db->table('pertandingan')->where('status', 'selesai')->countAllResults(),
            // Mengambil 5 Pertandingan Terakhir
            'latest_match' => $db->table('jadwal')
                     ->join('tim as t1', 't1.id = jadwal.home')
                     ->join('tim as t2', 't2.id = jadwal.away')
                     // PERBAIKAN DI SINI: Tambahkan ', "belum" as status'
                     ->select('jadwal.*, t1.nama_tim as home_team, t2.nama_tim as away_team, "belum" as status') 
                     ->orderBy('tanggal', 'DESC')
                     ->limit(5)
                     ->get()->getResultArray()
        ];

        return view('admin/dashboard', $data);
    }
}