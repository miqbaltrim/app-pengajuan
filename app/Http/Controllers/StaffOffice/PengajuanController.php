<?php

namespace App\Http\Controllers\StaffOffice;

use App\Http\Controllers\Controller; // Import Controller dari namespace yang benar
use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\Barang;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

class PengajuanController extends Controller
{
    public function index()
{
    // Dapatkan pengguna yang sedang login
    $user = Auth::user();
    $pengajuans = $user->pengajuans()->paginate(10);
    
    return view('staff-office.pengajuan-barang.index', compact('pengajuans'));
}

    public function notifikasiPengajuan()
{
    $notifikasiPengajuan = Pengajuan::where('setujui', 'diterima')
                                    ->where('ketahui', 'diterima')
                                    ->get();

    return view('staff-office.notifikasi-pengajuan', compact('notifikasiPengajuan'));
}

    public function create()
    {
        $lastReferenceNumber = Pengajuan::latest()->value('nomor_referensi');
        $lastNumber = 0;
        if ($lastReferenceNumber) {
            $lastNumber = explode('-', $lastReferenceNumber)[1];
        }
        $nextNumber = intval($lastNumber) + 1;
        $nextReferenceNumber = 'pengajuan-' . $nextNumber;

        return view('staff-office.pengajuan-barang.create', compact('nextReferenceNumber'));
    }

    public function store(Request $request)
{
    // Validasi input
    $request->validate([
        'nama_barang.*' => 'required',
        'qty.*' => 'required|integer',
        'harga_satuan.*' => 'required|numeric',
        'total.*' => 'required|numeric',
        'disetujui_oleh' => 'required|in:direktur,manager-operasional,manager-territory,manager-keuangan,area-manager,kepala-cabang,kepala-gudang',
        'diketahui_oleh' => 'required|in:direktur,manager-operasional,manager-territory,manager-keuangan,area-manager,kepala-cabang,kepala-gudang',
        'alasan' => 'required|string',
    ]);

    // Dapatkan pengguna yang sedang login
    $user = Auth::user();

    // Pastikan pengguna telah login sebelum melanjutkan
    if (!$user) {
        return redirect()->route('login')->with('error', 'Anda harus login untuk membuat pengajuan.');
    }

    // Mulai transaksi database
    DB::beginTransaction();

    try {
        // Temukan ID pengguna berdasarkan peran yang dipilih untuk disetujui oleh pengguna
        $disetujuiOlehUser = User::where('role', $request->disetujui_oleh)->firstOrFail();
        
        // Jika peran yang dipilih sama dengan peran pengguna yang sedang login, gunakan pengguna yang sedang login sebagai yang menyetujui
        if ($user->role == $request->disetujui_oleh) {
            $penggunaSetuju = $user;
        } else {
            // Temukan pengguna yang memiliki peran yang sama dengan yang dipilih sebagai disetujui
            $penggunaSetuju = User::where('role', $request->disetujui_oleh)->firstOrFail();
        }

        // Temukan ID pengguna berdasarkan peran yang dipilih untuk diketahui oleh pengguna
        $diketahuiOlehUser = User::where('role', $request->diketahui_oleh)->firstOrFail();

        // Ambil nilai terakhir dari nomor referensi
        $lastReferenceNumber = Pengajuan::latest()->value('nomor_referensi');

        // Inisialisasi $lastNumber dengan nilai default jika $lastReferenceNumber tidak ada
        $lastNumber = 0;

        // Pisahkan kata "pengajuan-" dari nomor referensi terakhir jika $lastReferenceNumber ada
        if ($lastReferenceNumber) {
            $lastNumber = explode('-', $lastReferenceNumber)[1];
        }

        // Tambahkan 1 ke nomor referensi terakhir
        $nextNumber = intval($lastNumber) + 1;

        // Buat nomor referensi baru
        $nextReferenceNumber = 'pengajuan-' . $nextNumber;

        // Simpan data ke tabel pengajuans
        $pengajuan = new Pengajuan();
        $pengajuan->nomor_referensi = $nextReferenceNumber;
        $pengajuan->dibuat_oleh = $user->id;
        $pengajuan->disetujui_oleh = $disetujuiOlehUser->id;
        $pengajuan->diketahui_oleh = $diketahuiOlehUser->id;
        $pengajuan->setujui = "tunggu";
        $pengajuan->ketahui = "tunggu";
        $pengajuan->alasan = $request->alasan;
        $pengajuan->save();

        // Simpan data ke tabel barangs dengan menetapkan pengajuan_id
        foreach ($request->nama_barang as $key => $nama_barang) {
            $barang = new Barang();
            $barang->nama_barang = $nama_barang;
            $barang->qty = $request->qty[$key];
            $barang->harga_satuan = $request->harga_satuan[$key];
            $barang->total = $request->total[$key];
            $barang->pengajuan_id = $pengajuan->id; // Tetapkan pengajuan_id yang baru saja dibuat
            $barang->save();
        }

        // Commit transaksi database
        DB::commit();

        return redirect()->route('staff-office.pengajuan-barang.index')->with('success', 'Pengajuan berhasil ditambahkan.');
    } catch (\Exception $e) {
        // Rollback transaksi jika terjadi kesalahan
        DB::rollback();
        return redirect()->back()->with('error', 'Terjadi kesalahan. Pengajuan gagal disimpan.');
    }
}
    public function user()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function edit($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        // Load view edit dengan data pengajuan yang akan diedit
        return view('staff-office.pengajuan-barang.edit', compact('pengajuan'));
    }

    public function update(Request $request, $id)
{
    // Validasi input
    $request->validate([
        'nama_barang.*' => 'required',
        'qty.*' => 'required|integer',
        'harga_satuan.*' => 'required|numeric',
        'total.*' => 'required|numeric',
        'disetujui_oleh' => 'required|in:direktur,manager-operasional,manager-territory,manager-keuangan,area-manager,kepala-cabang,kepala-gudang',
        'diketahui_oleh' => 'required|in:direktur,manager-operasional,manager-territory,manager-keuangan,area-manager,kepala-cabang,kepala-gudang',
        'alasan' => 'required|string',
    ]);

    // Mulai transaksi database
    DB::beginTransaction();

    try {
        // Ambil data pengajuan yang akan diupdate atau tambahkan data baru jika tidak ada
        $pengajuan = Pengajuan::findOrNew($id);

        // Update data pengajuan dengan data baru
        // Temukan ID pengguna berdasarkan peran yang dipilih untuk disetujui oleh pengguna
        $disetujuiOlehUser = User::where('role', $request->disetujui_oleh)->firstOrFail();
        // Temukan ID pengguna berdasarkan peran yang dipilih untuk diketahui oleh pengguna
        $diketahuiOlehUser = User::where('role', $request->diketahui_oleh)->firstOrFail();

        // Simpan data pengajuan
        $pengajuan->nomor_referensi = 'pengajuan-' . (Pengajuan::latest()->value('id') + 1);
        $pengajuan->disetujui_oleh = $disetujuiOlehUser->id;
        $pengajuan->diketahui_oleh = $diketahuiOlehUser->id;
        $pengajuan->setujui = "tunggu";
        $pengajuan->ketahui = "tunggu";
        $pengajuan->alasan = $request->alasan;
        $pengajuan->save();

        // Hapus semua barang terkait pengajuan yang lama
        $pengajuan->barangs()->delete();

        // Simpan data barang baru atau perbarui data barang yang sudah ada
        foreach ($request->nama_barang as $key => $nama_barang) {
            $barang = new Barang([
                'nama_barang' => $nama_barang,
                'qty' => $request->qty[$key],
                'harga_satuan' => $request->harga_satuan[$key],
                'total' => $request->total[$key],
            ]);
            $pengajuan->barangs()->save($barang);
        }

        // Commit transaksi database
        DB::commit();

        return redirect()->route('staff-office.pengajuan-barang.index')->with('success', 'Pengajuan berhasil diperbarui.');
    } catch (\Exception $e) {
        // Rollback transaksi jika terjadi kesalahan
        DB::rollback();
        return redirect()->back()->with('error', 'Terjadi kesalahan. Pengajuan gagal diperbarui.');
    }
}

    public function destroy($id)
    {
        // Cari pengajuan berdasarkan ID
        $pengajuan = Pengajuan::findOrFail($id);

        // Hapus pengajuan
        $pengajuan->delete();

        return redirect()->route('staff-office.pengajuan-barang.index')->with('success', 'Pengajuan berhasil dihapus.');
    }

    public function deleteItem($id, $item_id)
    {
        // Temukan pengajuan berdasarkan ID
        $pengajuan = Pengajuan::findOrFail($id);

        // Temukan barang yang akan dihapus berdasarkan ID
        $barang = Barang::findOrFail($item_id);

        // Pastikan barang tersebut termasuk dalam pengajuan yang dimaksud
        if ($barang->pengajuan_id != $pengajuan->id) {
            return redirect()->back()->with('error', 'Barang yang dimaksud tidak terkait dengan pengajuan ini.');
        }

        // Hapus barang
        $barang->delete();

        return redirect()->back()->with('success', 'Barang berhasil dihapus dari pengajuan.');
    }


    public function view($id)
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
    $templateProcessor->setValue('Alasan', $pengajuan->alasan);

    // Menggabungkan nama dan peran untuk disetujui oleh dan diketahui oleh
    $disetujuiOleh = $pengajuan->disetujuiOleh->nama;
    $diketahuiOleh = $pengajuan->diketahuiOleh->nama;
    $disetujuiOlehPosition = $pengajuan->disetujuiOleh->position;
    $diketahuiOlehPosition = $pengajuan->diketahuiOleh->position;
    $disetujuiPhoto = $pengajuan->disetujuiOleh->photo;
    $diketahuiPhoto = $pengajuan->diketahuiOleh->photo;
    $templateProcessor->setValue('DisetujuiOleh', $disetujuiOleh ?? 'User Tidak Ditemukan');
    $templateProcessor->setValue('DiketahuiOleh', $diketahuiOleh ?? 'User Tidak Ditemukan');
    $templateProcessor->setValue('PositionSetujui', $disetujuiOlehPosition ?? 'User Tidak Ditemukan');
    $templateProcessor->setValue('PositionKetahui', $diketahuiOlehPosition ?? 'User Tidak Ditemukan');
    // Menambahkan placeholder untuk gambar TTD dan mengisi nilainya dengan path gambar TTD dari pengguna
    $templateProcessor->setImageValue('TtdDibuat', [
        'path' => $pengajuan->dibuatOleh->ttd, 
        'width' => 100,
        'height' => 100 
    ]);
    $templateProcessor->setImageValue('TtdSetujui', [
        'path' => $pengajuan->disetujuiOleh->ttd, 
        'width' => 100,
        'height' => 100 
    ]);
    $templateProcessor->setImageValue('TtdKetahui', [
        'path' => $pengajuan->diketahuiOleh->ttd, // Path gambar TTD pengguna yang diketahui
        'width' => 100, // Lebar gambar dalam dokumen (opsional)
        'height' => 100 // Tinggi gambar dalam dokumen (opsional)
    ]);
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

public function uploadNota(Request $request, $id)
{
    // Validasi file yang diunggah, harus berformat PDF dengan ukuran maksimal 2MB
    $request->validate([
        'bukti_nota' => 'required|mimes:pdf|max:2048',
    ]);

    // Temukan pengajuan berdasarkan ID
    $pengajuan = Pengajuan::findOrFail($id);

    // Simpan file PDF ke dalam direktori 'bukti_notas' di penyimpanan publik
    $filePath = $request->file('bukti_nota')->store('bukti_notas', 'public');

    // Perbarui kolom 'bukti_nota' di tabel pengajuans dengan path file yang diunggah
    $pengajuan->bukti_nota = $filePath;
    $pengajuan->save();

    // Kembalikan respon dengan pesan sukses
    return redirect()->route('staff-office.pengajuan-barang.index')->with('success', 'Bukti nota berhasil diunggah.');
}







}
