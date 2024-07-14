<?php

namespace App\Http\Controllers\ManagerKeuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gaji;
use App\Models\User;

class GajiController extends Controller
{
    public function index()
    {
        // Mengambil semua data pengguna (users)
        $users = User::all();

        // Mengarahkan ke view untuk menampilkan data gaji
        return view('manager-keuangan.data-gaji.index', ['users' => $users]);
    }

    // Method untuk menyimpan data gaji
    public function store(Request $request, User $user)
    {
        // Validasi input
        $request->validate([
            'gaji' => 'required|numeric',
        ]);

        // Simpan data gaji untuk pengguna yang dipilih
        $user->gaji()->updateOrCreate([], ['gaji' => $request->gaji]);

        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Data gaji berhasil disimpan.');
    }
    
}
