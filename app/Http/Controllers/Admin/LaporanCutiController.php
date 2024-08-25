<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\Ajucuti;
use App\Models\User; // Tambahkan model User
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LaporanCutiController extends BaseController
{
    public function index()
{
    $users = User::all();
    $laporanCuti = [];

    foreach ($users as $user) {
        $ajucutis = Ajucuti::where('id_user', $user->id)->get();

        $terpakai = 0;
        foreach ($ajucutis as $ajucuti) {
            $mulaiCuti = Carbon::parse($ajucuti->mulai_cuti);
            $akhirCuti = Carbon::parse($ajucuti->selesai_cuti);
            $terpakai += $mulaiCuti->diffInDays($akhirCuti) + 1;
        }

        $sisa = 12 - $terpakai;
        $sisa = $sisa < 0 ? 0 : $sisa; // Ensure the remaining leave is not negative

        $laporanCuti[$user->id] = [
            'nama' => $user->nama,
            'tahun' => Carbon::now()->year,
            'position' => $user->position,
            'terpakai' => $terpakai,
            'sisa' => $sisa
        ];
    }

    return view('admin.laporan-cuti.index', ['laporanCuti' => array_values($laporanCuti)]);
}

public function search(Request $request)
{
    $nama = $request->nama;
    $tahun = $request->tahun;

    $query = User::query();

    if ($nama) {
        $query->where('nama', 'like', '%' . $nama . '%');
    }

    $users = $query->get();
    $laporanCuti = [];

    foreach ($users as $user) {
        $ajucutis = Ajucuti::where('id_user', $user->id);

        if ($tahun) {
            $ajucutis->whereYear('mulai_cuti', $tahun);
        }

        $ajucutis = $ajucutis->get();
        $terpakai = 0;

        foreach ($ajucutis as $ajucuti) {
            $mulaiCuti = Carbon::parse($ajucuti->mulai_cuti);
            $akhirCuti = Carbon::parse($ajucuti->selesai_cuti);
            $terpakai += $mulaiCuti->diffInDays($akhirCuti) + 1;
        }

        $sisa = 12 - $terpakai;
        $sisa = $sisa < 0 ? 0 : $sisa; // Ensure the remaining leave is not negative

        $laporanCuti[$user->id] = [
            'nama' => $user->nama,
            'tahun' => $tahun ?? Carbon::now()->year,
            'position' => $user->position,
            'terpakai' => $terpakai,
            'sisa' => $sisa
        ];
    }

    return view('admin.laporan-cuti.index', ['laporanCuti' => array_values($laporanCuti)]);
}

public function searchByMonth(Request $request)
    {
        $tahun = $request->input('tahun');
        $bulan = $request->input('bulan');

        $query = Ajucuti::with('user');

        if ($tahun) {
            $query->whereYear('mulai_cuti', $tahun);
        }

        if ($bulan) {
            $query->whereMonth('mulai_cuti', $bulan);
        }

        $riwayatCuti = $query->orderBy('mulai_cuti', 'desc')->get();

        return view('admin.laporan-cuti.riwayat-cuti', compact('riwayatCuti'));
    }

public function reset()
{
    // Mendapatkan tahun sekarang
    $currentYear = Carbon::now()->year;

    // Mendapatkan pengguna yang memiliki data cuti untuk tahun selanjutnya
    $usersWithCutiNextYear = Ajucuti::whereYear('mulai_cuti', '>', $currentYear)
                                    ->distinct('id_user')
                                    ->pluck('id_user');

    // Menghitung ulang data terpakai untuk pengguna yang memiliki data cuti untuk tahun selanjutnya
    foreach ($usersWithCutiNextYear as $userId) {
        $terpakai = Ajucuti::where('id_user', $userId)
                            ->whereYear('mulai_cuti', '>', $currentYear)
                            ->sum(function ($ajucuti) {
                                $mulaiCuti = Carbon::parse($ajucuti->mulai_cuti);
                                $akhirCuti = Carbon::parse($ajucuti->selesai_cuti);
                                return $mulaiCuti->diffInDays($akhirCuti) + 1; // Tambah 1 karena termasuk hari terakhir
                            });

        // Update jumlah cuti tersisa menjadi 12 dan data terpakai menjadi nol
        User::where('id', $userId)->update(['jml_cuti' => 12]);
    }

    // Redirect kembali ke halaman index dengan pesan berhasil
    return redirect()->route('admin.laporan-cuti.index')->with('success', 'Jumlah cuti untuk tahun selanjutnya berhasil direset.');
}

public function riwayatCuti()
    {
        $riwayatCuti = Ajucuti::with('user')
            ->where('approved', '!=', null)
            ->orderBy('mulai_cuti', 'desc')
            ->get();

        return view('admin.laporan-cuti.riwayat-cuti', compact('riwayatCuti'));
    }

}