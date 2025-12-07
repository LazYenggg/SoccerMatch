<?php

namespace App\Models;

use CodeIgniter\Model;

class KlasemenModel extends Model
{
    protected $table = 'klasemen';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_tim', 'main', 'menang', 'seri', 'kalah', 'gm', 'gk', 'poin'];

    // Fungsi untuk mengambil klasemen per Liga (atau Global)
    public function getKlasemenLengkap()
    {
        return $this->select('klasemen.*, tim.nama_tim, tim.logo, tim.liga')
                    ->join('tim', 'tim.id = klasemen.id_tim')
                    // Urutkan berdasarkan Poin Tertinggi, lalu Selisih Gol (GM - GK)
                    ->orderBy('poin', 'DESC')
                    ->orderBy('gm', 'DESC') 
                    ->findAll();
    }
}