<?php

namespace App\Models;

use CodeIgniter\Model;

class JadwalModel extends Model
{
    protected $table            = 'jadwal';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['tanggal', 'home', 'away', 'venue'];

    // Fungsi khusus untuk menggabungkan tabel jadwal dengan tabel tim
    public function getJadwalLengkap()
    {
        return $this->select('jadwal.*, t1.nama_tim as tim_home, t1.logo as logo_home, t2.nama_tim as tim_away, t2.logo as logo_away, t1.liga')
                    ->join('tim as t1', 't1.id = jadwal.home')
                    ->join('tim as t2', 't2.id = jadwal.away')
                    ->orderBy('jadwal.tanggal', 'ASC')
                    ->findAll();
    }
}