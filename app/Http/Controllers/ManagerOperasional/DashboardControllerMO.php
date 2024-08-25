<?php

namespace App\Http\Controllers\ManagerOperasional;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ajucuti;
use App\Models\Izin;
use App\Models\Sakit;
use App\Models\Kasbon;
use App\Models\Pengajuan;
use App\Models\Pengumuman;
use Illuminate\Support\Facades\Auth;

class DashboardControllerMO extends Controller
{
    public function index()
    {
        // Mendapatkan ID pengguna yang sedang login
        $userId = Auth::id();
        $role = Auth::user()->role;

        // Mengambil data riwayat cuti
        $riwayatCuti = Ajucuti::orderBy('created_at', 'desc')->get();

        // Mengambil data pengumuman
        $pengumumans = Pengumuman::all();

        // Mengambil semua pengajuan yang perlu di-approve oleh manajer
        $pengajuanCuti = Ajucuti::where('status', 'tunggu')
            ->orderBy('created_at', 'desc')
            ->get();

        $pengajuanIzin = Izin::where(function($query) use ($userId) {
            $query->where('setujui', 'tunggu')
                  ->orWhere('ketahui', 'tunggu');
        })->orderBy('created_at', 'desc')->get();

        $pengajuanSakit = Sakit::where(function($query) use ($userId) {
            $query->where('setujui', 'tunggu')
                  ->orWhere('ketahui', 'tunggu');
        })->orderBy('created_at', 'desc')->get();

        $pengajuanKasbon = Kasbon::where(function($query) use ($userId) {
            $query->where('setujui', 'tunggu')
                  ->orWhere('ketahui', 'tunggu');
        })->orderBy('created_at', 'desc')->get();

        $pengajuanBarang = Pengajuan::where(function($query) use ($userId) {
            $query->where('setujui', 'tunggu')
                  ->orWhere('ketahui', 'tunggu');
        })->orderBy('created_at', 'desc')->get();

        // Gabungkan semua pengajuan dan ambil 10 pengajuan terbaru
        $notifikasiPengajuan = $pengajuanCuti->merge($pengajuanIzin)
            ->merge($pengajuanSakit)
            ->merge($pengajuanKasbon)
            ->merge($pengajuanBarang)
            ->sortByDesc('created_at')
            ->take(10);

        // Menampilkan view dashboard.blade.php di dalam direktori manager-operasional
        return view('manager-operasional.dashboard', compact('riwayatCuti', 'notifikasiPengajuan', 'pengumumans'));
    }
}
