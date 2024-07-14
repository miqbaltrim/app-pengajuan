<?php

namespace App\Http\Controllers\AreaManager;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\User;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class BarangApprovalController extends Controller
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

        return view('area-manager.approve.barang', compact('pengajuanBarang'));
    }

    public function detail($id)
    {
        // Temukan pengajuan barang berdasarkan ID
        $pengajuan = Pengajuan::findOrFail($id);

        return view('area-manager.approve.detailBarang', compact('pengajuan'));
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