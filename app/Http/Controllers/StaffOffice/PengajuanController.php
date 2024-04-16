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

class PengajuanController extends Controller
{
    public function index()
    {
        $pengajuans = Pengajuan::all();
        return view('staff-office.pengajuan-barang.index', compact('pengajuans'));
    }

    public function create()
    {
        // Ambil nilai terakhir dari nomor referensi
        $lastReferenceNumber = Pengajuan::latest()->value('nomor_referensi');

        // Pisahkan kata "pengajuan-" dari nomor referensi terakhir
        $lastNumber = explode('-', $lastReferenceNumber)[1];

        // Tambahkan 1 ke nomor referensi terakhir
        $nextNumber = intval($lastNumber) + 1;

        // Buat nomor referensi baru
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
        // Temukan ID pengguna berdasarkan peran yang dipilih untuk diketahui oleh pengguna
        $diketahuiOlehUser = User::where('role', $request->diketahui_oleh)->firstOrFail();

        // Simpan data ke tabel pengajuans
        $pengajuan = new Pengajuan();
        $pengajuan->nomor_referensi = 'pengajuan-' . (Pengajuan::latest()->value('id') + 1);
        $pengajuan->dibuat_oleh = $user->id;
        $pengajuan->disetujui_oleh = $disetujuiOlehUser->id;
        $pengajuan->diketahui_oleh = $diketahuiOlehUser->id;
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


    // Tambahkan fungsi edit, update, dan delete sesuai kebutuhan
}
