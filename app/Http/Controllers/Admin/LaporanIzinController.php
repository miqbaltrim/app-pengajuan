<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\Izin;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LaporanIzinController extends BaseController
{
    /**
     * Menampilkan daftar laporan izin
     */
    public function index()
    {
        $users = User::all();
        $laporanIzin = [];

        foreach ($users as $user) {
            $izins = Izin::where('dibuat_oleh', $user->id)->get();

            $jumlahHariIzin = 0;
            foreach ($izins as $izin) {
                $mulaiIzin = Carbon::parse($izin->mulai_izin);
                $selesaiIzin = Carbon::parse($izin->selesai_izin);
                $jumlahHariIzin += $mulaiIzin->diffInDays($selesaiIzin) + 1;
            }

            $laporanIzin[$user->id] = [
                'nama' => $user->nama,
                'tahun' => Carbon::now()->year,
                'position' => $user->position,
                'jumlah_hari_izin' => $jumlahHariIzin,
            ];
        }

        return view('admin.laporan-izin.index', ['laporanIzin' => array_values($laporanIzin)]);
    }

    /**
     * Mencari laporan izin berdasarkan nama dan tahun
     */
    public function search(Request $request)
    {
        $nama = $request->nama;
        $tahun = $request->tahun;

        $query = User::query();

        if ($nama) {
            $query->where('nama', 'like', '%' . $nama . '%');
        }

        $users = $query->get();
        $laporanIzin = [];

        foreach ($users as $user) {
            $izins = Izin::where('dibuat_oleh', $user->id);

            if ($tahun) {
                $izins->whereYear('mulai_izin', $tahun);
            }

            $izins = $izins->get();
            $jumlahHariIzin = 0;

            foreach ($izins as $izin) {
                $mulaiIzin = Carbon::parse($izin->mulai_izin);
                $selesaiIzin = Carbon::parse($izin->selesai_izin);
                $jumlahHariIzin += $mulaiIzin->diffInDays($selesaiIzin) + 1;
            }

            $laporanIzin[$user->id] = [
                'nama' => $user->nama,
                'tahun' => $tahun ?? Carbon::now()->year,
                'position' => $user->position,
                'jumlah_hari_izin' => $jumlahHariIzin,
            ];
        }

        return view('admin.laporan-izin.index', ['laporanIzin' => array_values($laporanIzin)]);
    }

    public function searchByMonth(Request $request)
    {
        $tahun = $request->input('tahun');
        $bulan = $request->input('bulan');

        $query = Izin::with('dibuatOleh');

        if ($tahun) {
            $query->whereYear('mulai_izin', $tahun);
        }

        if ($bulan) {
            $query->whereMonth('mulai_izin', $bulan);
        }

        $riwayatIzin = $query->orderBy('mulai_izin', 'desc')->get();

        return view('admin.laporan-izin.riwayat-izin', compact('riwayatIzin'));
    }

    /**
     * Reset data izin
     */
    public function reset()
    {
        // Mendapatkan tahun sekarang
        $currentYear = Carbon::now()->year;

        // Mendapatkan pengguna yang memiliki data izin untuk tahun selanjutnya
        $usersWithIzinNextYear = Izin::whereYear('mulai_izin', '>', $currentYear)
            ->distinct('dibuat_oleh')
            ->pluck('dibuat_oleh');

        // Menghitung ulang data terpakai untuk pengguna yang memiliki data izin untuk tahun selanjutnya
        foreach ($usersWithIzinNextYear as $userId) {
            $jumlahHariIzin = Izin::where('dibuat_oleh', $userId)
                ->whereYear('mulai_izin', '>', $currentYear)
                ->sum(function ($izin) {
                    $mulaiIzin = Carbon::parse($izin->mulai_izin);
                    $selesaiIzin = Carbon::parse($izin->selesai_izin);
                    return $mulaiIzin->diffInDays($selesaiIzin) + 1; // Tambah 1 karena termasuk hari terakhir
                });

            // Update jumlah hari izin tersisa menjadi nol
            User::where('id', $userId)->update(['jml_izin' => 0]);
        }

        // Redirect kembali ke halaman index dengan pesan berhasil
        return redirect()->route('admin.laporan-izin.index')->with('success', 'Data izin untuk tahun selanjutnya berhasil direset.');
    }

    /**
     * Menampilkan riwayat izin
     */
    public function riwayatIzin()
    {
        $riwayatIzin = Izin::with('dibuatOleh')
            ->where('setujui', '!=', null)
            ->orderBy('mulai_izin', 'desc')
            ->get();

        return view('admin.laporan-izin.riwayat-izin', compact('riwayatIzin'));
    }
}
