<?php

namespace App\Http\Controllers\AreaManager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Izin;
use Illuminate\Support\Facades\Auth;

class IzinApprovalControllerAM extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil daftar pengajuan izin yang statusnya 'tunggu'
        $izins = Izin::with(['dibuatOleh', 'disetujuiOleh', 'diketahuiOleh'])
            ->where(function ($query) use ($user) {
                $query->where(function ($q) use ($user) {
                    $q->where('setujui', 'tunggu')
                      ->where('disetujui_oleh', $user->id);
                })->orWhere(function ($q) use ($user) {
                    $q->where('ketahui', 'tunggu')
                      ->where('diketahui_oleh', $user->id);
                });
            })->get();

        // Ambil riwayat pengajuan izin yang sudah diproses
        $riwayatIzins = Izin::with(['dibuatOleh', 'disetujuiOleh', 'diketahuiOleh'])
            ->where(function ($query) use ($user) {
                $query->where('setujui', '!=', 'tunggu')
                      ->orWhere('ketahui', '!=', 'tunggu');
            })->get();

        // Kembalikan view dengan data pengajuan izin yang telah diambil
        return view('area-manager.approve.izin', compact('izins', 'riwayatIzins'));
    }

    public function disetujuiTerima($id)
    {
        $izin = Izin::findOrFail($id);
        $user = Auth::user();

        if ($izin->disetujui_oleh == $user->id) {
            $izin->setujui = 'diterima';
            $izin->save();

            return redirect()->back()->with('success', 'Pengajuan izin disetujui.');
        }

        return redirect()->back()->with('error', 'Anda tidak memiliki wewenang untuk menyetujui pengajuan ini.');
    }

    public function disetujuiTolak($id)
    {
        $izin = Izin::findOrFail($id);
        $user = Auth::user();

        if ($izin->disetujui_oleh == $user->id) {
            $izin->setujui = 'ditolak';
            $izin->save();

            return redirect()->back()->with('success', 'Pengajuan izin ditolak.');
        }

        return redirect()->back()->with('error', 'Anda tidak memiliki wewenang untuk menolak pengajuan ini.');
    }

    public function diketahuiTerima($id)
    {
        $izin = Izin::findOrFail($id);
        $user = Auth::user();

        if ($izin->diketahui_oleh == $user->id) {
            $izin->ketahui = 'diterima';
            $izin->save();

            return redirect()->back()->with('success', 'Pengajuan izin diketahui.');
        }

        return redirect()->back()->with('error', 'Anda tidak memiliki wewenang untuk mengetahui pengajuan ini.');
    }

    public function diketahuiTolak($id)
    {
        $izin = Izin::findOrFail($id);
        $user = Auth::user();

        if ($izin->diketahui_oleh == $user->id) {
            $izin->ketahui = 'ditolak';
            $izin->save();

            return redirect()->back()->with('success', 'Pengajuan izin ditolak.');
        }

        return redirect()->back()->with('error', 'Anda tidak memiliki wewenang untuk menolak pengajuan ini.');
    }
}
