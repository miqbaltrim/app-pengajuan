<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ajucuti;
use App\Models\Pengajuan;
use App\Models\Barang;
use App\Models\Pengumuman;
use Illuminate\Support\Facades\Storage;

class DashboardControllerAdmin extends Controller
{
    public function index()
    {
        // Mengambil data riwayat cuti
        $riwayatCuti = Ajucuti::orderBy('created_at', 'desc')->get();
        $pengumumans = Pengumuman::orderBy('created_at', 'desc')->get();
        // Menampilkan view dashboard.blade.php di dalam direktori admin
        return view('admin.dashboard', compact('riwayatCuti', 'pengumumans'));
     }
    
    // Fungsi untuk menambahkan pengumuman baru
    public function postAnnouncement(Request $request)
    {
        // Validasi data pengumuman
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048', // Gambar harus berupa file gambar dengan maksimal 2MB
            'caption' => 'required|string|max:255', // Caption harus berupa teks dengan maksimal 255 karakter
        ]);

        // Simpan gambar ke dalam penyimpanan
        $imagePath = $request->file('image')->store('public/image-pengumuman');

        // Ubah path untuk menyimpan ke direktori public
        $publicPath = str_replace('public/', '', $imagePath);

        // Buat pengumuman baru
        $pengumuman = new Pengumuman();
        $pengumuman->image = $publicPath;
        $pengumuman->caption = $request->caption;
        $pengumuman->save();

        // Redirect kembali ke halaman dashboard dengan pesan sukses
        return redirect()->route('admin.dashboard')->with('success', 'Pengumuman berhasil ditambahkan.');
    }

    // Fungsi untuk menampilkan halaman form edit pengumuman
    public function editAnnouncement($id)
    {
        // Temukan pengumuman berdasarkan ID
        $pengumuman = Pengumuman::findOrFail($id);
        // Tampilkan halaman form edit pengumuman dengan data pengumuman yang ditemukan
        return view('admin.edit_pengumuman', compact('pengumuman'));
    }

    // Fungsi untuk menyimpan perubahan pada pengumuman yang diedit
    public function updateAnnouncement(Request $request, $id)
    {
        // Validasi data pengumuman
        $request->validate([
            'caption' => 'required|string|max:255', // Caption harus berupa teks dengan maksimal 255 karakter
        ]);

        // Temukan pengumuman berdasarkan ID
        $pengumuman = Pengumuman::findOrFail($id);

        // Jika ada gambar baru yang diunggah
        if ($request->hasFile('image')) {
            // Validasi gambar baru
            $request->validate([
                'image' => 'required|image|max:2048', // Gambar harus berupa file gambar dengan maksimal 2MB
            ]);

            // Hapus gambar lama dari penyimpanan
            Storage::delete($pengumuman->image);

            // Simpan gambar baru ke dalam penyimpanan
            $imagePath = $request->file('image')->store('public/pengumuman');
            $pengumuman->image = $imagePath;
        }

        // Update caption pengumuman
        $pengumuman->caption = $request->caption;
        $pengumuman->save();

        // Redirect kembali ke halaman dashboard dengan pesan sukses
        return redirect()->route('admin.dashboard')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    // Fungsi untuk menghapus pengumuman
    public function deleteAnnouncement($id)
    {
        // Temukan pengumuman berdasarkan ID
        $pengumuman = Pengumuman::findOrFail($id);

        // Hapus gambar dari penyimpanan
        Storage::delete($pengumuman->image);

        // Hapus pengumuman dari database
        $pengumuman->delete();

        // Redirect kembali ke halaman dashboard dengan pesan sukses
        return redirect()->route('admin.dashboard')->with('success', 'Pengumuman berhasil dihapus.');
    }

    // Fungsi untuk menyimpan komentar pada pengumuman
    public function storeComment(Request $request, $id)
    {
        // Validasi data komentar
        $request->validate([
            'comment' => 'required|string|max:255', // Komentar harus berupa teks dengan maksimal 255 karakter
        ]);

        // Temukan pengumuman berdasarkan ID
        $pengumuman = Pengumuman::findOrFail($id);

        // Buat komentar baru
        $comment = new Comment();
        $comment->pengumuman_id = $pengumuman->id;
        $comment->comment = $request->comment;
        $comment->save();

        // Redirect kembali ke halaman dashboard dengan pesan sukses
        return redirect()->route('admin.dashboard')->with('success', 'Komentar berhasil ditambahkan.');
    }
}