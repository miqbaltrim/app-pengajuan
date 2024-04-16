<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseController extends Controller
{
    /**
     * Membuat koneksi ke database.
     *
     * @return \Illuminate\Http\Response
     */
    public function connectDatabase()
    {
        // Ubah nilai host, username, password, dan nama_database sesuai dengan pengaturan Anda
        $host = "localhost";
        $username = "username";
        $password = "password";
        $database = "nama_database";

        try {
            // Membuat koneksi ke database
            $conn = new \mysqli($host, $username, $password, $database);

            // Cek koneksi berhasil atau tidak
            if ($conn->connect_error) {
                die("Koneksi ke database gagal: " . $conn->connect_error);
            } else {
                // Koneksi berhasil
                echo "Koneksi ke database berhasil!";
            }
        } catch (\Exception $e) {
            // Tangani jika terjadi exception
            die("Terjadi kesalahan: " . $e->getMessage());
        }
    }
}
