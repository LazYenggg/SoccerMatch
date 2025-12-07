<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class TestDb extends BaseController
{
    public function index()
    {
        // Coba konek ke database
        $db = \Config\Database::connect();
        
        // Cek apakah tabel tim ada isinya
        try {
            $query = $db->query("SELECT * FROM tim LIMIT 5");
            $results = $query->getResultArray();

            echo "<h1>Koneksi Database SUKSES! 🚀</h1>";
            echo "<h3>Contoh Data Tim:</h3>";
            echo "<pre>";
            print_r($results);
            echo "</pre>";
        } catch (\Throwable $e) {
            echo "<h1>Koneksi GAGAL 😭</h1>";
            echo $e->getMessage();
        }
    }
}