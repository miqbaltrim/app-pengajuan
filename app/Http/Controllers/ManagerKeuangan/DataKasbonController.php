<?php

namespace App\Http\Controllers\ManagerKeuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kasbon;
use Illuminate\Support\Facades\DB;

class DataKasbonController extends Controller
{
    public function index()
    {
        // Ambil semua data kasbon
        $kasbons = Kasbon::with('user')->get();
        
        // Kembalikan view dengan data kasbon
        return view('manager-keuangan.data-kasbon.index', compact('kasbons'));
    }

    public function updateStatusCicilan($id)
    {
        // Dapatkan data kasbon berdasarkan ID
        $kasbon = Kasbon::findOrFail($id);

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Update status cicilan menjadi lunas
            $kasbon->status_cicilan = 'lunas';

            // Update sisa cicilan menjadi 0
            $kasbon->sisa_cicilan = 0;

            // Simpan perubahan
            $kasbon->save();

            // Commit transaksi jika semua operasi berhasil
            DB::commit();

            return redirect()->route('manager-keuangan.data-kasbon.index')->with('success', 'Status cicilan berhasil diperbarui menjadi lunas dan sisa cicilan menjadi 0.');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();

            return redirect()->back()->with('error', 'Gagal memperbarui status cicilan. Silakan coba lagi.');
        }
    }
}

