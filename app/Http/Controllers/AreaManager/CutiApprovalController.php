<?php

namespace App\Http\Controllers\AreaManager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ajucuti;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CutiApprovalController extends Controller
{
    public function index()
    {
        // Pastikan pengguna memiliki peran sebagai area manager
        if (!Auth::user()->hasRole('area-manager')) {
            return redirect()->route('login')->with('error', 'Anda tidak memiliki akses.');
        }

        // Mendapatkan daftar pengajuan cuti yang belum disetujui dan sesuai dengan peran pengguna yang sedang login
        $ajucutis = Ajucuti::whereIn('status', ['tunggu', 'disetujui', 'ditolak'])
                            ->where('approved', Auth::user()->role)
                            ->get();

        // Menampilkan view approve/cuti.blade.php di dalam direktori area-manager
        return view('area-manager.approve.cuti', compact('ajucutis'));
    }

    public function reset()
    {
        // Reset jumlah cuti untuk pengguna yang terkait dengan 'staff-office'
        $users = User::where('role', 'staff-office')->get();
        foreach ($users as $user) {
            $user->update(['jml_cuti' => 12]);
        }

        // Redirect kembali ke halaman index dengan pesan berhasil
        return redirect()->route('staff-office.pengajuan-cuti.index')->with('success', 'Jumlah cuti berhasil direset.');
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
