<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\User;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class BarangApprovalControllerHR extends Controller
{
    public function index()
    {
        // Ambil daftar pengajuan barang yang belum disetujui atau belum direspon
        $pengajuanBarang = Pengajuan::with('user')
            ->where(function ($query) {
                $query->where('setujui', 'tunggu')
                    ->orWhere('ketahui', 'tunggu');
            })
            ->get();

            // Ambil riwayat pengajuan barang yang sudah diproses
        $riwayatPengajuan = Pengajuan::with('user')
        ->where(function ($query) {
            $query->where('setujui', '!=', 'tunggu')
                  ->orWhere('ketahui', '!=', 'tunggu');
        })
        ->get();

        return view('admin.approve.barang', compact('pengajuanBarang', 'riwayatPengajuan'));
    }

    public function detail($id)
    {
        // Temukan pengajuan barang berdasarkan ID
        $pengajuan = Pengajuan::findOrFail($id);

        return view('admin.approve.detailBarang', compact('pengajuan'));
    }

    public function disetujuiTerima($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        $pengajuan->setujui = 'diterima';
        $pengajuan->save();

        return redirect()->back()->with('success', 'Pengajuan disetujui.');
    }

    public function disetujuiTolak($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        $pengajuan->setujui = 'ditolak';
        $pengajuan->save();

        return redirect()->back()->with('success', 'Pengajuan ditolak.');
    }

    public function diketahuiTerima($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        $pengajuan->ketahui = 'diterima';
        $pengajuan->save();

        return redirect()->back()->with('success', 'Pengajuan diketahui.');
    }

    public function diketahuiTolak($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        $pengajuan->ketahui = 'ditolak';
        $pengajuan->save();

        return redirect()->back()->with('success', 'Pengajuan ditolak.');
    }
    
}
