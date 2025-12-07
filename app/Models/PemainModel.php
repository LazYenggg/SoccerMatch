<?php

namespace App\Models;

use CodeIgniter\Model;

class PemainModel extends Model
{
    protected $table            = 'pemain';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields    = ['id_tim', 'nama_pemain', 'posisi'];

    // Fungsi untuk mengambil data pemain + nama timnya
    public function getPemainLengkap()
    {
        return $this->select('pemain.*, tim.nama_tim, tim.logo')
                    ->join('tim', 'tim.id = pemain.id_tim')
                    ->orderBy('tim.nama_tim', 'ASC')
                    ->orderBy('pemain.posisi', 'ASC')
                    ->findAll();
    }
}