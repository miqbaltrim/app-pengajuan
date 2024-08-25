<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function showProfile()
    {
        $role = Auth::user()->role;

        switch ($role) {
            case 'admin':
                // Logika untuk profil admin
                return view('admin.profile');
                break;

            case 'direktur':
                // Logika untuk profil admin
                return view('direktur.profile');
                break;

            case 'manager-operasional':
                // Logika untuk profil admin
                return view('manager-operasional.profile');
                break;

            case 'area-manager':
                // Logika untuk profil admin
                return view('area-manager.profile');
                break;

            case 'manager-keuangan':
                // Logika untuk profil admin
                return view('manager-keuangan.profile');
                break;

            case 'kepala-gudang':
                // Logika untuk profil admin
                return view('kepala-gudang.profile');
                break;

            case 'staff-office':
                // Logika untuk profil admin
                return view('staff-office.profile');
                break;

            case 'gudang':
                // Logika untuk profil admin
                return view('gudang.profile');
                break;
            
            default:
                // Jika tidak ada peran yang cocok, arahkan ke halaman profil default
                return view('profiles.default');
        }
    }

    public function updateProfile(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'nama' => 'required|string|max:255',
        'email' => 'required|string|email|max:255',
        'password' => 'nullable|string|min:8',
        'alamat' => 'nullable|string|max:255',
        'telepon' => 'nullable|string|max:20',
        'position' => 'nullable|string|max:255',
        'photo' => 'nullable|image|mimes:jpeg,png|max:2048', // Menambahkan validasi jenis file
        'ttd' => 'nullable|image|mimes:jpeg,png|max:2048', // Validasi untuk TTD
    ]);

    // Perbarui data pengguna dengan data baru dari formulir
    $user->nama = $request->nama;
    $user->email = $request->email;
    if (!is_null($request->password)) {
        $user->password = bcrypt($request->password);
    }
    $user->alamat = $request->alamat;
    $user->telepon = $request->telepon;
    $user->position = $request->position;

    if ($request->hasFile('photo')) {
        // Simpan berkas ke dalam direktori public/storage/photo
        $photoPath = $request->file('photo')->store('public/photo/' . $user->id);
        // Ambil nama file yang disimpan
        $fileName = basename($photoPath);
        // Simpan path foto ke dalam kolom photo di basis data
        $user->photo = 'photo/' . $user->id . '/' . $fileName;
    }

    // Mengelola pengunggahan TTD
    if ($request->hasFile('ttd')) {
        $ttdPath = $request->file('ttd')->store('public/ttd/' . $user->id);
        $ttdFileName = basename($ttdPath);
        // Simpan path TTD ke dalam kolom ttd di basis data
        $user->ttd = 'ttd/' . $user->id . '/' . $ttdFileName;
    }

    $user->save();

    return redirect()->back()->with('success', 'Profile updated successfully.');
}


public function uploadPhoto(Request $request)
{
    $request->validate([
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // tambahkan validasi foto
        'ttd' => 'nullable|image|mimes:jpeg,png|max:2048', // tambahkan validasi TTD
    ]);

    $user = Auth::user();

    // Mengelola pengunggahan foto profil
    if ($request->hasFile('photo')) {
        $file = $request->file('photo');
        $photoFileName = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/profiles'), $photoFileName);
        
        // Simpan path foto ke dalam database
        $user->photo = 'uploads/profiles/' . $photoFileName;
    }

    // Mengelola pengunggahan TTD
    if ($request->hasFile('ttd')) {
        $file = $request->file('ttd');
        $ttdFileName = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/ttd'), $ttdFileName);
        
        // Simpan path TTD ke dalam database
        $user->ttd = 'uploads/ttd/' . $ttdFileName;
    }

    // Simpan perubahan pada model pengguna
    $user->save();

    return redirect()->back()->with('success', 'Profile updated successfully.');
}




}
