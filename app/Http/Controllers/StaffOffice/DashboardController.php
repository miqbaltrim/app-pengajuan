<?php

namespace App\Http\Controllers\StaffOffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ajucuti;
use App\Models\Izin;
use App\Models\Sakit;
use App\Models\Kasbon;
use App\Models\Pengajuan;
use App\Models\Pengumuman;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Mengambil data riwayat cuti
        $riwayatCuti = Ajucuti::orderBy('created_at', 'desc')->get();
        $pengumumans = Pengumuman::all();

        // Mengambil data notifikasi semua pengajuan
        $userId = Auth::id();

        // Mengambil semua pengajuan cuti, izin, sakit, kasbon, barang
        $pengajuanCuti = Ajucuti::where('id_user', $userId)->where('status', 'disetujui')->orderBy('created_at', 'desc')->get();
        $pengajuanIzin = Izin::where('dibuat_oleh', $userId)->where('setujui', 'diterima')->orderBy('created_at', 'desc')->get();
        $pengajuanSakit = Sakit::where('dibuat_oleh', $userId)->where('setujui', 'diterima')->orderBy('created_at', 'desc')->get();
        $pengajuanKasbon = Kasbon::where('user_id', $userId)->where('setujui', 'diterima')->orderBy('created_at', 'desc')->get();
        $pengajuanBarang = Pengajuan::where('dibuat_oleh', $userId)->where('setujui', 'diterima')->where('ketahui', 'diterima')->orderBy('created_at', 'desc')->get();

        // Gabungkan semua pengajuan dan ambil 10 pengajuan terbaru
        $notifikasiPengajuan = $pengajuanCuti->merge($pengajuanIzin)->merge($pengajuanSakit)->merge($pengajuanKasbon)->merge($pengajuanBarang)->sortByDesc('created_at')->take(10);

        // Menampilkan view dashboard.blade.php di dalam direktori staff-office
        return view('staff-office.dashboard', compact('riwayatCuti', 'notifikasiPengajuan', 'pengumumans'));
    }
}
