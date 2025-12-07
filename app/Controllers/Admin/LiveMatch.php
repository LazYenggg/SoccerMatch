<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class LiveMatch extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index($id_jadwal)
    {
        // 1. Cek & Buat Data Pertandingan jika belum ada
        $cek = $this->db->table('pertandingan')->where('id', $id_jadwal)->countAllResults();
        
        if ($cek == 0) {
            $infoJadwal = $this->db->table('jadwal')->where('id', $id_jadwal)->get()->getRowArray();
            $this->db->table('pertandingan')->insert([
                'id' => $id_jadwal,
                'home' => $infoJadwal['home'],
                'away' => $infoJadwal['away'],
                'tanggal' => $infoJadwal['tanggal'],
                'venue' => $infoJadwal['venue'],
                'status' => 'belum',
                'skor_a' => 0,
                'skor_b' => 0,
                'menit_berjalan' => 0
            ]);
        }

        // 2. Ambil Data
        $match = $this->db->table('jadwal')
            ->select('jadwal.*, t1.nama_tim as home_team, t1.id as home_id, t1.logo as home_logo, t2.nama_tim as away_team, t2.id as away_id, t2.logo as away_logo, p.status, p.skor_a, p.skor_b, p.waktu_mulai_real')
            ->join('tim as t1', 't1.id = jadwal.home')
            ->join('tim as t2', 't2.id = jadwal.away')
            ->join('pertandingan as p', 'p.id = jadwal.id', 'left')
            ->where('jadwal.id', $id_jadwal)
            ->get()->getRowArray();

        // 3. HITUNG DETIK BERJALAN
        $elapsedSeconds = 0;
        if ($match['status'] == 'berlangsung' && !empty($match['waktu_mulai_real'])) {
            $elapsedSeconds = time() - strtotime($match['waktu_mulai_real']);
        } elseif ($match['status'] == 'halftime') {
            $elapsedSeconds = 45 * 60; 
        } elseif ($match['status'] == 'selesai') {
            $elapsedSeconds = 90 * 60;
        }

        // 4. DETEKSI BABAK (Untuk Tombol HT/FT)
        // Cek apakah event 'second_half' sudah pernah dicatat?
        $isBabak2 = $this->db->table('match_events')
                         ->where('id_jadwal', $id_jadwal)
                         ->where('jenis_event', 'second_half')
                         ->countAllResults() > 0;

        $pemainHome = $this->db->table('pemain')->where('id_tim', $match['home_id'])->get()->getResultArray();
        $pemainAway = $this->db->table('pemain')->where('id_tim', $match['away_id'])->get()->getResultArray();

        $events = $this->db->table('match_events')
            ->select('match_events.*, pemain.nama_pemain')
            ->join('pemain', 'pemain.id = match_events.id_pemain', 'left')
            ->where('id_jadwal', $id_jadwal)
            ->orderBy('created_at', 'DESC')
            ->get()->getResultArray();

        $data = [
            'title' => 'Live Console: ' . $match['home_team'] . ' vs ' . $match['away_team'],
            'match' => $match,
            'pemain_home' => $pemainHome,
            'pemain_away' => $pemainAway,
            'events' => $events,
            'elapsed_seconds' => $elapsedSeconds,
            'isBabak2' => $isBabak2 // <-- Kirim status babak ke View
        ];

        return view('admin/pertandingan/live_console', $data);
    }

    public function start($id_jadwal)
    {
        $now = date('Y-m-d H:i:s');
        $this->db->table('pertandingan')->where('id', $id_jadwal)->update([
            'status' => 'berlangsung',
            'waktu_mulai_real' => $now
        ]);
        $this->logEvent($id_jadwal, null, null, 'kickoff', 0, 'Pertandingan Dimulai');
        return redirect()->to("/dashboard/live/$id_jadwal");
    }

    public function halftime($id_jadwal)
    {
        $this->db->table('pertandingan')->where('id', $id_jadwal)->update(['status' => 'halftime']);
        $this->logEvent($id_jadwal, null, null, 'halftime', 45, 'Babak Pertama Selesai');
        return redirect()->to("/dashboard/live/$id_jadwal");
    }

    public function second_half($id_jadwal)
    {
        $startTimeBabak2 = date('Y-m-d H:i:s', strtotime('-45 minutes'));
        $this->db->table('pertandingan')->where('id', $id_jadwal)->update([
            'status' => 'berlangsung',
            'waktu_mulai_real' => $startTimeBabak2
        ]);
        $this->logEvent($id_jadwal, null, null, 'second_half', 46, 'Babak Kedua Dimulai');
        return redirect()->to("/dashboard/live/$id_jadwal");
    }

    public function add_event()
    {
        $id_jadwal = $this->request->getPost('id_jadwal');
        $jenis     = $this->request->getPost('jenis'); 
        $id_tim    = $this->request->getPost('id_tim'); // Tim PELAKU
        $id_pemain = $this->request->getPost('id_pemain');
        $menit     = $this->request->getPost('menit');

        // Validasi Waktu Mundur
        $lastEvent = $this->db->table('match_events')
            ->where('id_jadwal', $id_jadwal)
            ->orderBy('menit', 'DESC')
            ->get()->getRow();

        if ($lastEvent && $menit < $lastEvent->menit) {
            return redirect()->back()->with('error', "Gagal! Waktu tidak boleh mundur dari menit " . $lastEvent->menit);
        }

        // Validasi Pemain
        if (!empty($id_pemain)) {
            $cekPemain = $this->db->table('pemain')->where('id', $id_pemain)->where('id_tim', $id_tim)->countAllResults();
            if ($cekPemain == 0) {
                return redirect()->back()->with('error', "Gagal! Pemain yang dipilih bukan dari tim tersebut.");
            }
        }

        $this->logEvent($id_jadwal, $id_tim, $id_pemain, $jenis, $menit);

        if ($jenis == 'goal' || $jenis == 'penalty_goal' || $jenis == 'own_goal') {
            $this->updateSkor($id_jadwal);
        }

        return redirect()->to("/dashboard/live/$id_jadwal");
    }

    public function delete_event($id_event, $id_jadwal)
    {
        $this->db->table('match_events')->delete(['id' => $id_event]);
        $this->updateSkor($id_jadwal);
        return redirect()->to("/dashboard/live/$id_jadwal")->with('success', 'Event dibatalkan.');
    }

    public function end($id_jadwal)
    {
        $this->db->table('pertandingan')->where('id', $id_jadwal)->update(['status' => 'selesai']);
        $this->logEvent($id_jadwal, null, null, 'fulltime', 90, 'Pertandingan Selesai');
        return redirect()->to("/dashboard/live/$id_jadwal");
    }

    private function logEvent($id_jadwal, $id_tim, $id_pemain, $jenis, $menit, $ket = null)
    {
        $this->db->table('match_events')->insert([
            'id_jadwal' => $id_jadwal, 'id_tim' => $id_tim, 'id_pemain' => $id_pemain,
            'jenis_event' => $jenis, 'menit' => $menit, 'keterangan'=> $ket
        ]);
    }

    private function updateSkor($id_jadwal)
    {
        $jadwal = $this->db->table('jadwal')->where('id', $id_jadwal)->get()->getRow();
        
        // Hitung Gol Home
        $golHome = $this->db->table('match_events')
            ->where('id_jadwal', $id_jadwal)->where('id_tim', $jadwal->home)
            ->whereIn('jenis_event', ['goal', 'penalty_goal'])->countAllResults(); // Gol Biasa
        $golHome += $this->db->table('match_events')
            ->where('id_jadwal', $id_jadwal)->where('id_tim', $jadwal->away)->where('jenis_event', 'own_goal')->countAllResults(); // Hadiah Own Goal

        // Hitung Gol Away
        $golAway = $this->db->table('match_events')
            ->where('id_jadwal', $id_jadwal)->where('id_tim', $jadwal->away)
            ->whereIn('jenis_event', ['goal', 'penalty_goal'])->countAllResults();
        $golAway += $this->db->table('match_events')
            ->where('id_jadwal', $id_jadwal)->where('id_tim', $jadwal->home)->where('jenis_event', 'own_goal')->countAllResults();

        // Update Tabel Pertandingan
        $this->db->table('pertandingan')->where('id', $id_jadwal)->update([
            'skor_a' => $golHome,
            'skor_b' => $golAway
        ]);

        // === [PENTING] HITUNG ULANG KLASEMEN OTOMATIS ===
        $this->recalculateKlasemen();
    }

    // === FUNGSI HITUNG KLASEMEN (Logic 3 Poin) ===
    private function recalculateKlasemen()
    {
        // 1. Reset Semua data klasemen jadi 0 dulu
        $this->db->query("UPDATE klasemen SET main=0, menang=0, seri=0, kalah=0, gm=0, gk=0, poin=0");

        // 2. Ambil semua pertandingan yang SUDAH SELESAI atau SEDANG BERLANGSUNG (Live)
        // Kita hitung yang Live juga biar klasemennya Real-time
        $matches = $this->db->table('pertandingan')
                        ->whereIn('status', ['selesai', 'berlangsung', 'halftime'])
                        ->get()->getResultArray();

        foreach($matches as $m) {
            $home = $m['home'];
            $away = $m['away'];
            $scoreA = $m['skor_a'];
            $scoreB = $m['skor_b'];

            // Logika Poin
            // Home Menang
            if ($scoreA > $scoreB) {
                // Update Home (Menang +1, Poin +3)
                $this->updateTeamStats($home, 'menang', 3, $scoreA, $scoreB);
                // Update Away (Kalah +1, Poin +0)
                $this->updateTeamStats($away, 'kalah', 0, $scoreB, $scoreA);
            }
            // Away Menang
            elseif ($scoreB > $scoreA) {
                // Update Home (Kalah +1, Poin +0)
                $this->updateTeamStats($home, 'kalah', 0, $scoreA, $scoreB);
                // Update Away (Menang +1, Poin +3)
                $this->updateTeamStats($away, 'menang', 3, $scoreB, $scoreA);
            }
            // Seri
            else {
                // Update Home (Seri +1, Poin +1)
                $this->updateTeamStats($home, 'seri', 1, $scoreA, $scoreB);
                // Update Away (Seri +1, Poin +1)
                $this->updateTeamStats($away, 'seri', 1, $scoreB, $scoreA);
            }
        }
    }

    private function updateTeamStats($idTim, $hasil, $poin, $goalFor, $goalAgainst)
    {
        // Query SQL langsung biar cepat (Increment values)
        // main + 1
        // menang/seri/kalah + 1
        // gm + goalFor
        // gk + goalAgainst
        // poin + poin
        $sql = "UPDATE klasemen SET 
                main = main + 1,
                $hasil = $hasil + 1,
                gm = gm + $goalFor,
                gk = gk + $goalAgainst,
                poin = poin + $poin
                WHERE id_tim = ?";
        
        $this->db->query($sql, [$idTim]);
    }
}