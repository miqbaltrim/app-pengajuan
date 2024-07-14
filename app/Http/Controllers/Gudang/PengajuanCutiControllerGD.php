<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ajucuti;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Facades\DB;

class PengajuanCutiControllerGD extends Controller
{
    public function index()
    {
        // Mendapatkan data pengajuan cuti pengguna saat ini
        $ajucutis = Ajucuti::where('id_user', Auth::id())->get();

        // Mengambil jumlah cuti default dari database
        $user = User::find(Auth::id());
        $totalCuti = $user->jml_cuti;

        // Memulai transaksi database
        DB::beginTransaction();

        try {
            // Menghitung total cuti yang tersisa untuk pengguna saat ini
            foreach ($ajucutis as $ajucuti) {
                if ($ajucuti->id_user == Auth::id()) {
                    // Menghitung jumlah hari cuti yang diambil
                    $mulaiCuti = strtotime($ajucuti->mulai_cuti);
                    $selesaiCuti = strtotime($ajucuti->selesai_cuti);
                    $hariCuti = ($selesaiCuti - $mulaiCuti) / (60 * 60 * 24) + 1; // Jumlah hari termasuk tanggal selesai

                    // Jika pengajuan cuti disetujui, kurangi jumlah cuti yang diambil dari total cuti
                    if ($ajucuti->status == 'disetujui') {
                        $totalCuti -= $hariCuti;
                    }
                }
            }

            // Commit transaksi database
            DB::commit();
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();
            // Tangani kesalahan
            // ...
        }
    // Menampilkan view index.blade.php di dalam direktori gudang/pengajuan-cuti
    return view('gudang.pengajuan-cuti.index', compact('ajucutis', 'totalCuti'));
}
public function reset()
{
    // Mendapatkan data pengguna saat ini
    $user = User::find(Auth::id());

    // Memulai transaksi database
    DB::beginTransaction();

    try {
        // Reset nilai jml_cuti ke nilai default (misalnya: 12)
        $user->jml_cuti = 12; // Atur nilai default sesuai kebutuhan Anda
        $user->save();

        // Menghapus semua data pengajuan cuti pengguna saat ini
        Ajucuti::where('id_user', Auth::id())->delete();

        // Commit transaksi database
        DB::commit();

        // Redirect dengan pesan sukses
        return redirect()->route('gudang.pengajuan-cuti.index')->with('success', 'Jumlah cuti berhasil direset.');
    } catch (\Exception $e) {
        // Rollback transaksi jika terjadi kesalahan dan tangani kesalahan
        DB::rollback();
        // Tangani kesalahan
        // ...
        return redirect()->back()->with('error', 'Terjadi kesalahan saat mereset jumlah cuti.');
    }
}

    public function riwayatDashboard()
    {
        // Mengambil semua riwayat cuti dari model Ajucuti
        $riwayatCuti = Ajucuti::all();

        // Menampilkan view index.blade.php di dalam direktori gudang/pengajuan-cuti
        return view('gudang.dashboard', compact('riwayatCuti'));
    }

    public function create()
    {
        return view('gudang.pengajuan-cuti.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'mulai_cuti' => 'required|date',
            'selesai_cuti' => 'required|date',
            'alasan' => 'required|string',
            'approved' => 'required|in:admin,direktur,manager-operasional,manager-territory,manager-keuangan,area-manager,kepala-cabang,kepala-gudang',
        ]);

        // Mendapatkan jumlah cuti default dari database untuk pengguna saat ini
        $user = User::find(Auth::id());
        $totalCuti = $user->jml_cuti;

        // Memulai transaksi database
        DB::beginTransaction();

        try {
            // Menghitung total cuti yang telah diajukan untuk pengguna saat ini
            $ajucutis = Ajucuti::where('id_user', Auth::id())->get();
            foreach ($ajucutis as $ajucuti) {
                if ($ajucuti->status == 'disetujui') {
                    // Menghitung jumlah hari cuti yang diambil
                    $mulaiCuti = strtotime($ajucuti->mulai_cuti);
                    $selesaiCuti = strtotime($ajucuti->selesai_cuti);
                    $hariCuti = ($selesaiCuti - $mulaiCuti) / (60 * 60 * 24);

                    // Mengurangi jumlah cuti yang diambil dari total cuti
                    $totalCuti -= $hariCuti;
                }
            }

            // Menghitung jumlah hari cuti yang diajukan pada pengajuan ini
            $mulaiCuti = strtotime($request->mulai_cuti);
            $selesaiCuti = strtotime($request->selesai_cuti);
            $hariCuti = ($selesaiCuti - $mulaiCuti) / (60 * 60 * 24);

            // Memeriksa apakah pengguna mencoba mengambil cuti melebihi jatahnya
            if ($totalCuti - $hariCuti < 0) {
                // Jika ya, rollback transaksi dan kembalikan dengan pesan error
                DB::rollback();
                return redirect()->back()->with('error', 'Maaf, Anda mencoba mengambil cuti melebihi jatah cuti yang tersisa.');
            }

            // Membuat pengajuan cuti baru
            $ajucuti = new Ajucuti();
            $ajucuti->id_user = Auth::id();
            $ajucuti->mulai_cuti = $request->mulai_cuti;
            $ajucuti->selesai_cuti = $request->selesai_cuti;
            $ajucuti->alasan = $request->alasan;
            $ajucuti->approved = $request->approved;
            $ajucuti->save();

            // Commit transaksi
            DB::commit();
            
            // Redirect dengan pesan sukses
            return redirect()->route('gudang.pengajuan-cuti.index')->with('success', 'Pengajuan cuti berhasil diajukan.');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan dan tangani kesalahan
            DB::rollback();
            // Tangani kesalahan
            // ...
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengajukan cuti.');
        }
    }

    // Function untuk menampilkan halaman edit
    public function edit($id)
    {
        // Mengambil data pengajuan cuti berdasarkan ID
        $ajucuti = Ajucuti::findOrFail($id);

        // Menampilkan view edit.blade.php di dalam direktori gudang/pengajuan-cuti
        return view('gudang.pengajuan-cuti.edit', compact('ajucuti'));
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'mulai_cuti' => 'required|date',
        'selesai_cuti' => 'required|date',
        'alasan' => 'required|string',
        'approved' => 'required|in:admin,direktur,manager-operasional,manager-territory,manager-keuangan,area-manager,kepala-cabang,kepala-gudang',
    ]);

    // Mengambil data Ajucuti berdasarkan ID
    $ajucuti = Ajucuti::findOrFail($id);

    // Mendapatkan jumlah cuti default dari database untuk pengguna saat ini
    $user = User::find(Auth::id());
    $totalCuti = $user->jml_cuti;

    // Memulai transaksi database
    DB::beginTransaction();

    try {
        // Menghitung total cuti yang telah diajukan untuk pengguna saat ini
        $ajucutis = Ajucuti::where('id_user', Auth::id())->get();
        foreach ($ajucutis as $cuti) {
            if ($cuti->status == 'disetujui') {
                // Menghitung jumlah hari cuti yang diambil
                $mulaiCuti = strtotime($cuti->mulai_cuti);
                $selesaiCuti = strtotime($cuti->selesai_cuti);
                $hariCuti = ($selesaiCuti - $mulaiCuti) / (60 * 60 * 24);

                // Mengurangi jumlah cuti yang diambil dari total cuti
                $totalCuti -= $hariCuti;
            }
        }

        // Menghitung jumlah hari cuti yang diajukan pada update ini
        $mulaiCuti = strtotime($request->mulai_cuti);
        $selesaiCuti = strtotime($request->selesai_cuti);
        $hariCuti = ($selesaiCuti - $mulaiCuti) / (60 * 60 * 24);

        // Memeriksa apakah pengguna mencoba mengambil cuti melebihi jatahnya
        if ($hariCuti > $totalCuti) {
            // Jika ya, rollback transaksi dan kembalikan dengan pesan error
            DB::rollback();
            return redirect()->back()->with('error', 'Maaf, Anda mencoba mengambil cuti melebihi jatah cuti yang tersisa.');
        }

        // Mengisi data Ajucuti dengan data dari form
        $ajucuti->mulai_cuti = $request->mulai_cuti;
        $ajucuti->selesai_cuti = $request->selesai_cuti;
        $ajucuti->alasan = $request->alasan;
        $ajucuti->approved = $request->approved;

        // Menyimpan perubahan
        $ajucuti->save();

        // Commit transaksi
        DB::commit();

        // Redirect dengan pesan sukses
        return redirect()->route('gudang.pengajuan-cuti.index')->with('success', 'Pengajuan cuti berhasil diperbarui.');
    } catch (\Exception $e) {
        // Rollback transaksi jika terjadi kesalahan dan tangani kesalahan
        DB::rollback();
        // Tangani kesalahan
        // ...
        return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui pengajuan cuti.');
    }
}


    // Function untuk menghapus data pengajuan cuti
    public function destroy($id)
    {
        // Mengambil data pengajuan cuti berdasarkan ID
        $ajucuti = Ajucuti::findOrFail($id);

        // Menghapus data pengajuan cuti
        $ajucuti->delete();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('gudang.pengajuan-cuti.index')->with('success', 'Data pengajuan cuti berhasil dihapus.');
    }

    public function approve(Request $request, $id)
    {
        // Mengambil data pengajuan cuti berdasarkan ID
        $ajucuti = Ajucuti::findOrFail($id);

        // Memeriksa apakah pengguna memiliki hak untuk menyetujui pengajuan cuti
        if (in_array(Auth::user()->role, ['direktur', 'manager-operasional', 'manager-territory', 'manager-keuangan', 'area-manager', 'kepala-cabang', 'kepala-gudang']) && $ajucuti->status == 'pending') {
            // Menyetujui pengajuan cuti
            $ajucuti->status = 'approved';
            $ajucuti->save();

            // Redirect kembali ke halaman index dengan pesan sukses
            return redirect()->route('gudang.pengajuan-cuti.index')->with('success', 'Pengajuan cuti berhasil disetujui.');
        }

        // Jika pengguna tidak memiliki hak atau pengajuan cuti tidak dalam status pending, redirect kembali dengan pesan error
        return redirect()->route('gudang.pengajuan-cuti.index')->with('error', 'Anda tidak memiliki izin untuk menyetujui pengajuan cuti ini.');
    }

    public function view($id)
{
    // Temukan pengajuan cuti berdasarkan ID
    $ajucuti = Ajucuti::findOrFail($id);

    // Hitung lamanya cuti dalam hari
    $mulaiCuti = new \DateTime($ajucuti->mulai_cuti);
    $selesaiCuti = new \DateTime($ajucuti->selesai_cuti);
    $durasiCuti = $mulaiCuti->diff($selesaiCuti)->days + 1; // Tambah 1 hari karena inklusif

    // Hitung tanggal kembali bekerja
    $tanggalKembali = $selesaiCuti->modify('+1 day')->format('d F Y');

    // Path ke template dokumen yang sudah ada
    $templatePath = public_path('surat/pengajuan-cuti/Surat-Pengajuan.docx');

    // Membuat objek PHPWord dari template
    $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

    // Mendefinisikan format tanggal dengan nama bulan dalam bahasa Indonesia
    setlocale(LC_TIME, 'id_ID.utf8');

    // Mengganti placeholder dengan nilai yang sesuai
    $templateProcessor->setValue('nama', $ajucuti->user->nama);
    $templateProcessor->setValue('position', $ajucuti->user->position);
    $templateProcessor->setValue('tanggal_mulai', strftime('%d %B %Y', strtotime($ajucuti->mulai_cuti)));
    $templateProcessor->setValue('tanggal_selesai', strftime('%d %B %Y', strtotime($ajucuti->selesai_cuti)));
    $templateProcessor->setValue('alasan', $ajucuti->alasan);
    $templateProcessor->setImageValue('ttd', ['path' => public_path($ajucuti->user->ttd), 'width' => 100, 'height' => 100]);
    $templateProcessor->setValue('durasi_cuti', $durasiCuti); // Menampilkan durasi cuti dalam hari
    $templateProcessor->setValue('tanggal_kembali', $tanggalKembali); // Menampilkan tanggal kembali bekerja
    $templateProcessor->setValue('created_at', strftime('%d %B %Y', strtotime($ajucuti->created_at))); 

    // Path untuk menyimpan file
    $fileName = 'Surat_Pengajuan_Cuti_' . $ajucuti->id . '.docx';
    $filePath = public_path('surat_pengajuan_cuti/' . $fileName);

    // Simpan dokumen yang telah diisi ke file baru
    try {
        $templateProcessor->saveAs($filePath);
    } catch (\Exception $e) {
        // Jika terjadi kesalahan, kembalikan respons dengan pesan kesalahan
        return back()->with('error', 'Gagal menyimpan file: ' . $e->getMessage());
    }

    // Pastikan file telah tersimpan dengan benar sebelum mendownloadnya
    if (file_exists($filePath)) {
        // Tampilkan surat pengajuan cuti
        return response()->download($filePath)->deleteFileAfterSend(true);
    } else {
        // Jika file tidak dapat disimpan, berikan respons yang sesuai
        return back()->with('error', 'Gagal menyimpan file.');
    }
}

}

