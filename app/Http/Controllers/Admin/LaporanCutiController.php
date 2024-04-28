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
        // Retrieve all users
        $users = User::all();

        // Initialize arrays to store aggregated data
        $laporanCuti = [];

        // Iterate over each user
        foreach ($users as $user) {
            // Calculate total terpakai and sisa for each user
            $ajucutis = Ajucuti::where('id_user', $user->id)->get();

            $terpakai = 0;
            foreach ($ajucutis as $ajucuti) {
                $mulaiCuti = Carbon::parse($ajucuti->mulai_cuti);
                $akhirCuti = Carbon::parse($ajucuti->selesai_cuti);
                $terpakai += $mulaiCuti->diffInDays($akhirCuti) + 1; // Tambah 1 karena termasuk hari terakhir
            }

            // Calculate total sisa cuti
            $sisa = $user->jml_cuti - $terpakai;

            // Store aggregated data for each user
            $laporanCuti[] = [
                'nama' => $user->nama,
                'tahun' => Carbon::now()->year, // You can change this if needed
                'position' => $user->position,
                'terpakai' => $terpakai,
                'sisa' => $sisa
            ];
        }

        // Pass the aggregated data to the view
        return view('admin.laporan-cuti.index', compact('laporanCuti'));
    }


public function search(Request $request)
{
    $nama = $request->nama;
    $tahun = $request->tahun;

    $query = Ajucuti::query();

    // Jika ada nama yang dimasukkan, tambahkan kondisi pencarian berdasarkan nama
    if ($nama) {
        $query->whereHas('user', function ($userQuery) use ($nama) {
            $userQuery->where('nama', 'like', '%' . $nama . '%');
        });
    }

    // Jika ada tahun yang dimasukkan, tambahkan kondisi pencarian berdasarkan tahun
    if ($tahun) {
        $query->whereYear('mulai_cuti', $tahun);
    }

    // Eksekusi query dan ambil hasil
    $ajucutis = $query->get();

    // Menghitung jumlah hari terpakai dan sisa cuti
    $totalCuti = User::sum('jml_cuti'); // Mengambil total cuti dari database jml_cuti
    foreach ($ajucutis as $ajucuti) {
        $mulaiCuti = Carbon::parse($ajucuti->mulai_cuti);
        $selesaiCuti = Carbon::parse($ajucuti->selesai_cuti);
        $hariCuti = $selesaiCuti->diffInDays($mulaiCuti) + 1; // Tambah 1 karena termasuk hari terakhir

        // Mengurangi jumlah cuti yang diambil dari total cuti
        $totalCuti -= $hariCuti;
    }

    // Initialize $laporanCuti array
    $laporanCuti = [];

    // If searching by year and there are no results, set $laporanCuti to an empty array
    if ($tahun && $ajucutis->isEmpty()) {
        return view('admin.laporan-cuti.index', compact('ajucutis', 'nama', 'tahun', 'totalCuti', 'laporanCuti'));
    }

    // If not searching by year or there are search results, continue with normal data processing
    foreach ($ajucutis as $ajucuti) {
        $mulaiCuti = Carbon::parse($ajucuti->mulai_cuti);
        $akhirCuti = Carbon::parse($ajucuti->selesai_cuti);

        $tahun = $mulaiCuti->format('Y');
        $terpakai = $mulaiCuti->diffInDays($akhirCuti) + 1;
        $sisaCuti = 12 - $terpakai;

        $laporanCuti[] = [
            'nama' => $ajucuti->user->nama,
            'tahun' => $tahun,
            'position' => $ajucuti->user->position,
            'terpakai' => $terpakai,
            'sisa' => $sisaCuti
        ];
    }

    // Mengirim hasil pencarian ke view beserta jumlah hari terpakai dan sisa cuti
    return view('admin.laporan-cuti.index', compact('ajucutis', 'nama', 'tahun', 'totalCuti', 'laporanCuti'));
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


}