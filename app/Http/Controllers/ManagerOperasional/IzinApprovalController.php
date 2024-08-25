<?php

namespace App\Http\Controllers\ManagerOperasional;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Izin;
use Illuminate\Support\Facades\Auth;

class IzinApprovalController extends Controller
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
        return view('manager-operasional.approve.izin', compact('izins','riwayatIzins'));
    }

    public function disetujuiTerima($id)
    {
        $izin = Izin::findOrFail($id);
        $izin->setujui = 'diterima';
        $izin->save();

        return redirect()->back()->with('success', 'Pengajuan izin disetujui.');
    }

    public function disetujuiTolak($id)
    {
        $izin = Izin::findOrFail($id);
        $izin->setujui = 'ditolak';
        $izin->save();

        return redirect()->back()->with('success', 'Pengajuan izin ditolak.');
    }

    public function diketahuiTerima($id)
    {
        $izin = Izin::findOrFail($id);
        $izin->ketahui = 'diterima';
        $izin->save();

        return redirect()->back()->with('success', 'Pengajuan izin diketahui.');
    }

    public function diketahuiTolak($id)
    {
        $izin = Izin::findOrFail($id);
        $izin->ketahui = 'ditolak';
        $izin->save();

        return redirect()->back()->with('success', 'Pengajuan izin ditolak.');
    }
}
