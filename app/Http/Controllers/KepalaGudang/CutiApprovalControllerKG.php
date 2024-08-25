<?php

namespace App\Http\Controllers\KepalaGudang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ajucuti;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CutiApprovalControllerKG extends Controller
{
    public function index()
    {
        // Pastikan pengguna memiliki peran sebagai area manager
        if (!Auth::user()->hasRole('kepala-gudang')) {
            return redirect()->route('login')->with('error', 'Anda tidak memiliki akses.');
        }

        // Mendapatkan daftar pengajuan cuti yang belum disetujui dan sesuai dengan peran pengguna yang sedang login
        $ajucutis = Ajucuti::whereIn('status', ['tunggu', 'disetujui', 'ditolak'])
                            ->where('approved', Auth::user()->role)
                            ->get();

        // Menampilkan view approve/cuti.blade.php di dalam direktori kepala-gudang
        return view('kepala-gudang.approve.cuti', compact('ajucutis'));
    }

    public function approve(Request $request, $id)
    {
        // Menemukan data pengajuan cuti berdasarkan ID
        $ajucuti = Ajucuti::findOrFail($id);

        // Set status menjadi approved
        $ajucuti->status = 'disetujui';
        $ajucuti->save();

        return redirect()->back()->with('success', 'Pengajuan cuti berhasil disetujui.');
    }

    public function reject(Request $request, $id)
    {
        // Menemukan data pengajuan cuti berdasarkan ID
        $ajucuti = Ajucuti::findOrFail($id);

        // Set status menjadi ditolak
        $ajucuti->status = 'ditolak';
        $ajucuti->save();

        return redirect()->back()->with('success', 'Pengajuan cuti berhasil ditolak.');
    }
}
