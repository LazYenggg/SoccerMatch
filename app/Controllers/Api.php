<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class Api extends ResourceController
{
    protected $format = 'json';

    // Endpoint untuk mengambil data live match
    public function live_match($id_jadwal)
    {
        $db = \Config\Database::connect();

        // 1. Ambil Data Utama
        $match = $db->table('jadwal')
            ->select('jadwal.id, p.status, p.skor_a, p.skor_b, p.waktu_mulai_real, p.menit_berjalan, 
                      t1.nama_tim as home, t2.nama_tim as away, 
                      t1.logo as home_logo, t2.logo as away_logo')
            ->join('tim as t1', 't1.id = jadwal.home')
            ->join('tim as t2', 't2.id = jadwal.away')
            ->join('pertandingan as p', 'p.id = jadwal.id', 'left')
            ->where('jadwal.id', $id_jadwal)
            ->get()->getRowArray();

        if (!$match) return $this->failNotFound('Match not found');

        // 2. Hitung Detik Berjalan (Server Side)
        $elapsed = 0;
        if ($match['status'] == 'berlangsung' && !empty($match['waktu_mulai_real'])) {
            $elapsed = time() - strtotime($match['waktu_mulai_real']);
        } elseif ($match['status'] == 'halftime') {
            $elapsed = 45 * 60;
        } elseif ($match['status'] == 'selesai') {
            $elapsed = 90 * 60;
        }

        // 3. Ambil Timeline Event (Terbaru di atas)
        $events = $db->table('match_events')
            ->select('match_events.*, pemain.nama_pemain')
            ->join('pemain', 'pemain.id = match_events.id_pemain', 'left')
            ->where('id_jadwal', $id_jadwal)
            ->orderBy('created_at', 'DESC')
            ->get()->getResultArray();

        // Format Ulang Event Biar Rapi di JSON
        $timeline = [];
        foreach($events as $e) {
            // Tentukan icon & warna berdasarkan jenis event
            $icon = '📌'; 
            $color = 'gray';
            
            if (in_array($e['jenis_event'], ['goal', 'penalty_goal'])) { $icon = '⚽'; $color = 'green'; }
            elseif ($e['jenis_event'] == 'own_goal') { $icon = '🔄'; $color = 'red'; }
            elseif ($e['jenis_event'] == 'yellow_card') { $icon = '🟨'; $color = 'yellow'; }
            elseif ($e['jenis_event'] == 'red_card') { $icon = '🟥'; $color = 'red'; }
            elseif ($e['jenis_event'] == 'halftime') { $icon = '⏸️'; $color = 'blue'; }
            elseif ($e['jenis_event'] == 'fulltime') { $icon = '🏁'; $color = 'black'; }

            // Teks deskriptif
            $desc = ucwords(str_replace('_', ' ', $e['jenis_event']));
            if ($e['nama_pemain']) $desc .= " - " . $e['nama_pemain'];

            $timeline[] = [
                'menit' => $e['menit'],
                'icon'  => $icon,
                'color' => $color,
                'text'  => $desc,
                'ket'   => $e['keterangan']
            ];
        }

        return $this->respond([
            'status' => 200,
            'match' => [
                'status_str' => strtoupper($match['status']), // BELUM, BERLANGSUNG, DST
                'skor_home'  => (int)$match['skor_a'],
                'skor_away'  => (int)$match['skor_b'],
                'elapsed'    => $elapsed
            ],
            'timeline' => $timeline
        ]);
    }
}