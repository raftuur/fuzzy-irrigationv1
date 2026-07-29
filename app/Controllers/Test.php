<?php

namespace App\Controllers;

use Config\Database;

class Test extends BaseController
{
    public function index()
    {
        try {
            $db = Database::connect();

            $query = $db->query("SELECT DATABASE() AS db");
            $result = $query->getRow();

            echo "<h2>✅ Koneksi Database Berhasil</h2>";
            echo "<p>Database aktif : <b>{$result->db}</b></p>";
        } catch (\Throwable $e) {
            echo "<h2>❌ Koneksi Database Gagal</h2>";
            echo "<pre>";
            echo $e->getMessage();
            echo "</pre>";
        }
    }
}