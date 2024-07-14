<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DataKaryawanController extends Controller
{
    /**
     * Menampilkan data karyawan.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $users = User::all();
        return view('admin.data-karyawan.index', compact('users'));
    }

    public function create()
    {
        return view('admin.data-karyawan.create');
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|string|in:admin,direktur,manager-operasional,manager-territory,manager-keuangan,area-manager,kepala-cabang,kepala-gudang,staff-office,gudang',
            'alamat' => 'required|string',
            'telepon' => 'required|string',
            'position' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Atur validasi untuk foto
        ]);

        try {
            // Simpan data karyawan ke dalam database
            $karyawan = new User();
            $karyawan->nama = $request->nama;
            $karyawan->email = $request->email;
            $karyawan->role = $request->role;
            $karyawan->alamat = $request->alamat;
            $karyawan->telepon = $request->telepon;
            $karyawan->position = $request->position;

            // Simpan foto jika ada
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $photoName = time() . '.' . $photo->getClientOriginalExtension();
                $photo->move(public_path('photos'), $photoName);
                $karyawan->photo = 'photos/' . $photoName;
            }

            // Lakukan penyimpanan data karyawan
            $karyawan->save();

            // Redirect ke halaman yang sesuai setelah penyimpanan berhasil
            return redirect()->route('admin.data-karyawan.index')->with('success', 'Data karyawan berhasil disimpan.');
        } catch (\Exception $e) {
            // Jika terjadi kesalahan, kirimkan pesan error ke view
            return redirect()->back()->withInput()->withErrors(['error' => 'Gagal menyimpan data karyawan. Silakan coba lagi.']);
        }
    }

    public function edit($id)
    {
        // Temukan data pengguna berdasarkan ID
        $user = User::findOrFail($id);

        // Kirim data pengguna ke halaman edit
        return view('admin.data-karyawan.edit', compact('user'));
    }

    public function update(Request $request, $id)
{
    // Validasi data yang dikirim dari form edit
    $request->validate([
        'nama' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,'.$id, // Ignore unique email for current user
        'role' => 'required|string|in:admin,direktur,manager-operasional,manager-territory,manager-keuangan,area-manager,kepala-cabang,kepala-gudang,staff-office,gudang',
        'alamat' => 'required|string',
        'telepon' => 'required|string',
        'position' => 'required|string',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    // Temukan data pengguna berdasarkan ID
    $user = User::findOrFail($id);

    // Perbarui data pengguna
    $user->nama = $request->nama;
    $user->email = $request->email;
    $user->role = $request->role;
    $user->alamat = $request->alamat;
    $user->telepon = $request->telepon;
    $user->position = $request->position;

    // Simpan foto jika ada
    if ($request->hasFile('photo')) {
        $photo = $request->file('photo');
        $photoName = time() . '.' . $photo->getClientOriginalExtension();
        $photo->move(public_path('photos'), $photoName);
        $user->photo = 'photos/' . $photoName;
    }

    // Simpan perubahan
    $user->save();

    // Redirect ke halaman data karyawan dengan pesan sukses
    return redirect()->route('admin.data-karyawan.index')->with('success', 'Data karyawan berhasil diperbarui.');
}


    // Fungsi untuk menghapus data pengguna berdasarkan ID
    public function destroy($id)
    {
        // Temukan data pengguna berdasarkan ID
        $user = User::findOrFail($id);

        // Hapus data pengguna
        $user->delete();

        // Redirect ke halaman data karyawan dengan pesan sukses
        return redirect()->route('admin.data-karyawan.index')->with('success', 'Data karyawan berhasil dihapus.');
    }

    public function resetPassword($id)
    {
        $user = User::findOrFail($id);
        $user->password = Hash::make('user123');
        $user->save();

        return redirect()->route('admin.data-karyawan.index')->with('success', 'Password berhasil direset.');
    }
}
