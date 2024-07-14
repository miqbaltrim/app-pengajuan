<?php

namespace App\Http\Controllers\ManagerOperasional;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ajucuti;
use App\Models\Pengajuan;
use App\Models\Barang;
use App\Models\Pengumuman;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\Row;
use PhpOffice\PhpWord\Element\Cell;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class DashboardControllerMO extends Controller
{
    public function index()
    {
        // Mengambil data riwayat cuti
        $riwayatCuti = Ajucuti::orderBy('created_at', 'desc')->get();
        $pengumumans = Pengumuman::all();

        // Mengambil data notifikasi pengajuan yang disetujui dan diketahui
        // Mendapatkan ID pengguna yang sedang login
$userId = Auth::id();

// Mengambil notifikasi pengajuan yang terkait dengan pengguna yang sedang login
$notifikasiPengajuan = Pengajuan::where('setujui', 'diterima')
    ->where('ketahui', 'diterima')
    ->whereHas('dibuatOleh', function ($query) use ($userId) {
        $query->where('id', $userId);
    })
    ->latest() // Mengurutkan berdasarkan tanggal pembuatan terbaru
    ->limit(3)  // Membatasi hasil menjadi 3 data terbaru
    ->get();

        // Menampilkan view dashboard.blade.php di dalam direktori manager-operasional
        return view('manager-operasional.dashboard', compact('riwayatCuti', 'notifikasiPengajuan', 'pengumumans'));
    }

    // Metode untuk menampilkan surat pengajuan barang
    public function downloadSurat($id)
    {
        // Temukan pengajuan barang berdasarkan ID
    $pengajuan = Pengajuan::findOrFail($id);
    
    // Path template dokumen
    $templatePath = public_path('surat/pengajuan-barang/Pengajuan_Barang_Template.docx');

    // Membuat objek PHPWord dari template
    $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

    // Mengisi placeholder dengan data pengajuan barang
    $templateProcessor->setValue('NomorReferensi', $pengajuan->nomor_referensi);
    $templateProcessor->setValue('DibuatOleh', $pengajuan->dibuatOleh->nama ?? 'User Tidak Ditemukan');

    // Menggabungkan nama dan peran untuk disetujui oleh dan diketahui oleh
    $disetujuiOleh = $pengajuan->disetujuiOleh->nama;
    $diketahuiOleh = $pengajuan->diketahuiOleh->nama;
    $disetujuiOlehPosition = $pengajuan->disetujuiOleh->position;
    $diketahuiOlehPosition = $pengajuan->diketahuiOleh->position;
    $templateProcessor->setValue('DisetujuiOleh', $disetujuiOleh ?? 'User Tidak Ditemukan');
    $templateProcessor->setValue('DiketahuiOleh', $diketahuiOleh ?? 'User Tidak Ditemukan');
    $templateProcessor->setValue('PositionSetujui', $disetujuiOlehPosition ?? 'User Tidak Ditemukan');
    $templateProcessor->setValue('PositionKetahui', $diketahuiOlehPosition ?? 'User Tidak Ditemukan');

    // Menambahkan teks tanggal
    $tanggal = date('d F Y'); // Format tanggal sesuai kebutuhan Anda
    $templateProcessor->setValue('Tanggal', $tanggal);

    // Mengisi tabel di template dengan data barang
    $tableData = [];
    $totalPengajuan = 0; // Total pengajuan
    foreach ($pengajuan->barangs as $barang) {
        $totalBarang = $barang->qty * $barang->harga_satuan;
        $totalPengajuan += $totalBarang; // Menambahkan total harga barang ke total pengajuan
        $tableData[] = [
            'nama_barang' => $barang->nama_barang,
            'qty' => $barang->qty,
            'harga_satuan' => 'Rp ' . number_format($barang->harga_satuan, 0, ',', '.'),
            'total' => 'Rp ' . number_format($totalBarang, 0, ',', '.')
        ];
    }

    // Mengisi data ke dalam tabel di template
    $templateProcessor->cloneRowAndSetValues('nama_barang', $tableData);
    $templateProcessor->cloneRowAndSetValues('qty', $tableData);
    $templateProcessor->cloneRowAndSetValues('harga_satuan', $tableData);
    $templateProcessor->cloneRowAndSetValues('total', $tableData);

    // Mengisi total pengajuan ke dalam template dalam format rupiah
    $templateProcessor->setValue('TotalPengajuan', 'Rp ' . number_format($totalPengajuan, 0, ',', '.'));

    // Menghasilkan nama file
    $fileName = 'Surat_Pengajuan_Barang_' . $pengajuan->id . '.docx';
    $filePath = public_path('surat_pengajuan_barang/' . $fileName);

    // Simpan dokumen yang telah diisi ke file baru
    try {
        $templateProcessor->saveAs($filePath);
    } catch (\Exception $e) {
        // Jika terjadi kesalahan, kembalikan respons dengan pesan kesalahan
        return back()->with('error', 'Gagal menyimpan file: ' . $e->getMessage());
    }

    // Pastikan file telah tersimpan dengan benar sebelum mendownloadnya
    if (file_exists($filePath)) {
        // Tampilkan surat pengajuan barang
        return response()->download($filePath)->deleteFileAfterSend(true);
    } else {
        // Jika file tidak dapat disimpan, berikan respons yang sesuai
        return back()->with('error', 'Gagal menyimpan file atau file tidak ditemukan.');
    }
}
}