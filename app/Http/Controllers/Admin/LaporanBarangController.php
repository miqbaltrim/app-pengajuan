<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\User;
use App\Models\Barang;
use Carbon\Carbon;

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
        'nama' => 'nullable|string',
        'tahun' => 'nullable|integer|min:1900|max:' . date('Y'),
        'bulan' => 'nullable|integer|min:1|max:12',
    ]);

    // Ambil data pengajuan barang berdasarkan nama, tahun, dan bulan
    $nama = $request->input('nama');
    $tahun = $request->input('tahun');
    $bulan = $request->input('bulan');

    $pengajuanBarang = Pengajuan::query();

    if ($nama) {
        $pengajuanBarang->whereHas('user', function ($query) use ($nama) {
            $query->where('nama', 'like', "%$nama%");
        });
    }

    if ($tahun) {
        $pengajuanBarang->whereYear('created_at', $tahun);
    }

    if ($bulan) {
        $pengajuanBarang->whereMonth('created_at', $bulan);
    }

    $pengajuanBarang = $pengajuanBarang->get();

    // Tampilkan hasil pencarian ke halaman view
    return view('admin.laporan-barang.index', compact('pengajuanBarang'));
}
}

