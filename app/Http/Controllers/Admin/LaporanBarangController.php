<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\User;
use App\Models\Barang;

class LaporanBarangController extends Controller
{
    public function index()
    {
        // Retrieve data for the view
        $pengajuanBarang = Pengajuan::all(); // Adjust the query according to your needs

        // Pass data to the view
        return view('admin.laporan-barang.index', compact('pengajuanBarang'));
    }

    public function detail($id)
    {
        $pengajuan = Pengajuan::findOrFail($id); // Mengambil data pengajuan berdasarkan ID
        return view('admin.laporan-barang.detail', compact('pengajuan'));
    }

    public function search(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string',
        ]);

        // Ambil data pengajuan barang berdasarkan nama karyawan
        $nama = $request->nama;
        $pengajuanBarang = Pengajuan::whereHas('user', function ($query) use ($nama) {
            $query->where('nama', 'like', "%$nama%");
        })->get();

        // Tampilkan hasil pencarian ke halaman view
        return view('admin.laporan-barang.index', compact('pengajuanBarang'));
    }
}

