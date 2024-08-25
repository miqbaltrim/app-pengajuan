<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\Sakit;
use App\Models\User;
use Carbon\Carbon;

class LaporanSakitController extends BaseController
{
    /**
     * Menampilkan daftar laporan sakit
     */
    public function index()
    {
        $users = User::all();
        $laporanSakit = [];

        foreach ($users as $user) {
            $sakits = Sakit::where('dibuat_oleh', $user->id)->get();

            $jumlahHariSakit = 0;
            foreach ($sakits as $sakit) {
                $mulaiSakit = Carbon::parse($sakit->mulai_sakit);
                $selesaiSakit = Carbon::parse($sakit->selesai_sakit);
                $jumlahHariSakit += $mulaiSakit->diffInDays($selesaiSakit) + 1;
            }

            $laporanSakit[$user->id] = [
                'nama' => $user->nama,
                'tahun' => Carbon::now()->year,
                'position' => $user->position,
                'jumlah_hari_sakit' => $jumlahHariSakit,
            ];
        }

        return view('admin.laporan-sakit.index', ['laporanSakit' => array_values($laporanSakit)]);
    }

    /**
     * Mencari laporan sakit berdasarkan nama dan tahun
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
    $laporanSakit = [];

    foreach ($users as $user) {
        $sakits = Sakit::where('dibuat_oleh', $user->id);

        if ($tahun) {
            $sakits->whereYear('mulai_sakit', $tahun);
        }

        $sakits = $sakits->get();
        $jumlahHariSakit = 0;

        foreach ($sakits as $sakit) {
            $mulaiSakit = Carbon::parse($sakit->mulai_sakit);
            $selesaiSakit = Carbon::parse($sakit->selesai_sakit);
            $jumlahHariSakit += $mulaiSakit->diffInDays($selesaiSakit) + 1;
        }

        $laporanSakit[$user->id] = [
            'nama' => $user->nama,
            'tahun' => $tahun ?? Carbon::now()->year,
            'position' => $user->position,
            'jumlah_hari_sakit' => $jumlahHariSakit,
        ];
    }

    return view('admin.laporan-sakit.index', ['laporanSakit' => array_values($laporanSakit)]);
}

    /**
     * Mencari laporan sakit berdasarkan tahun dan bulan
     */
    public function searchByMonth(Request $request)
    {
        $tahun = $request->input('tahun');
        $bulan = $request->input('bulan');

        $query = Sakit::with('dibuatOleh', 'disetujuiOleh', 'diketahuiOleh');

        if ($tahun) {
            $query->whereYear('mulai_sakit', $tahun);
        }

        if ($bulan) {
            $query->whereMonth('mulai_sakit', $bulan);
        }

        $riwayatSakit = $query->orderBy('mulai_sakit', 'desc')->get();

        return view('admin.laporan-sakit.riwayat-sakit', compact('riwayatSakit'));
    }

    /**
     * Reset data sakit
     */
    public function reset()
    {
        // Mendapatkan tahun sekarang
        $currentYear = Carbon::now()->year;

        // Mendapatkan pengguna yang memiliki data sakit untuk tahun selanjutnya
        $usersWithSakitNextYear = Sakit::whereYear('mulai_sakit', '>', $currentYear)
            ->distinct('dibuat_oleh')
            ->pluck('dibuat_oleh');

        // Menghitung ulang data terpakai untuk pengguna yang memiliki data sakit untuk tahun selanjutnya
        foreach ($usersWithSakitNextYear as $userId) {
            $jumlahHariSakit = Sakit::where('dibuat_oleh', $userId)
                ->whereYear('mulai_sakit', '>', $currentYear)
                ->sum(function ($sakit) {
                    $mulaiSakit = Carbon::parse($sakit->mulai_sakit);
                    $selesaiSakit = Carbon::parse($sakit->selesai_sakit);
                    return $mulaiSakit->diffInDays($selesaiSakit) + 1; // Tambah 1 karena termasuk hari terakhir
                });

            // Update jumlah hari sakit tersisa menjadi nol
            User::where('id', $userId)->update(['jml_sakit' => 0]);
        }

        // Redirect kembali ke halaman index dengan pesan berhasil
        return redirect()->route('admin.laporan-sakit.index')->with('success', 'Data sakit untuk tahun selanjutnya berhasil direset.');
    }

    /**
     * Menampilkan riwayat sakit
     */
    public function riwayatSakit()
    {
        $riwayatSakit = Sakit::with('dibuatOleh')
            ->where('setujui', '!=', null)
            ->orderBy('mulai_sakit', 'desc')
            ->get();

        return view('admin.laporan-sakit.riwayat-sakit', compact('riwayatSakit'));
    }
}
