<?php

namespace App\Http\Controllers\StaffOffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ajucuti;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class PengajuanCutiController extends Controller
{
    public function index()
    {
        // Mengambil data pengajuan cuti
        $ajucutis = Ajucuti::all();

        // Menghitung total cuti yang tersisa untuk pengguna saat ini
        $totalCuti = 12; // Jumlah cuti awal
        foreach ($ajucutis as $ajucuti) {
            if ($ajucuti->id_user == Auth::id()) {
                // Menghitung jumlah hari cuti yang diambil
                $mulaiCuti = strtotime($ajucuti->mulai_cuti);
                $selesaiCuti = strtotime($ajucuti->selesai_cuti);
                $hariCuti = ($selesaiCuti - $mulaiCuti) / (60 * 60 * 24);

                // Mengurangi jumlah cuti yang diambil dari total cuti
                $totalCuti -= $hariCuti;
            }
        }

        // Menampilkan view index.blade.php di dalam direktori staff-office/pengajuan-cuti
        return view('staff-office.pengajuan-cuti.index', compact('ajucutis', 'totalCuti'));
    }

    public function create()
    {
        return view('staff-office.pengajuan-cuti.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'mulai_cuti' => 'required|date',
            'selesai_cuti' => 'required|date',
            'alasan' => 'required|string',
            'approved' => 'required|in:admin,direktur,manager-operasional,manager-territory,manager-keuangan,area-manager,kepala-cabang,kepala-gudang',
        ]);

        // Mengisi model Ajucuti dengan data dari formulir
        $ajucuti = new Ajucuti();
        $ajucuti->id_user = Auth::id(); // Menyertakan ID pengguna saat ini
        $ajucuti->mulai_cuti = $request->mulai_cuti;
        $ajucuti->selesai_cuti = $request->selesai_cuti;
        $ajucuti->alasan = $request->alasan;
        $ajucuti->approved = $request->approved;
        $ajucuti->save();

        return redirect()->route('staff-office.pengajuan-cuti.index')->with('success', 'Pengajuan cuti berhasil diajukan.');
    }

    // Function untuk menampilkan halaman edit
    public function edit($id)
    {
        // Mengambil data pengajuan cuti berdasarkan ID
        $ajucuti = Ajucuti::findOrFail($id);

        // Menampilkan view edit.blade.php di dalam direktori staff-office/pengajuan-cuti
        return view('staff-office.pengajuan-cuti.edit', compact('ajucuti'));
    }

    // Function untuk mengupdate data pengajuan cuti
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

        // Mengisi data Ajucuti dengan data dari form
        $ajucuti->mulai_cuti = $request->mulai_cuti;
        $ajucuti->selesai_cuti = $request->selesai_cuti;
        $ajucuti->alasan = $request->alasan;
        $ajucuti->approved = $request->approved;

        // Menyimpan perubahan
        $ajucuti->save();

        return redirect()->route('staff-office.pengajuan-cuti.index')->with('success', 'Pengajuan cuti berhasil diperbarui.');
    }

    // Function untuk menghapus data pengajuan cuti
    public function destroy($id)
    {
        // Mengambil data pengajuan cuti berdasarkan ID
        $ajucuti = Ajucuti::findOrFail($id);

        // Menghapus data pengajuan cuti
        $ajucuti->delete();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('staff-office.pengajuan-cuti.index')->with('success', 'Data pengajuan cuti berhasil dihapus.');
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
            return redirect()->route('staff-office.pengajuan-cuti.index')->with('success', 'Pengajuan cuti berhasil disetujui.');
        }

        // Jika pengguna tidak memiliki hak atau pengajuan cuti tidak dalam status pending, redirect kembali dengan pesan error
        return redirect()->route('staff-office.pengajuan-cuti.index')->with('error', 'Anda tidak memiliki izin untuk menyetujui pengajuan cuti ini.');
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

