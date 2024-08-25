<?php

namespace App\Http\Controllers\ManagerOperasional;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sakit;
use Illuminate\Support\Facades\Auth;

class SakitApprovalController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil daftar pengajuan sakit yang statusnya 'tunggu'
        $sakits = Sakit::with(['dibuatOleh', 'disetujuiOleh', 'diketahuiOleh'])
            ->where(function ($query) use ($user) {
                $query->where(function ($q) use ($user) {
                    $q->where('setujui', 'tunggu')
                      ->where('disetujui_oleh', $user->id);
                })->orWhere(function ($q) use ($user) {
                    $q->where('ketahui', 'tunggu')
                      ->where('diketahui_oleh', $user->id);
                });
            })->get();

            // Ambil riwayat pengajuan sakit yang sudah diproses
        $riwayatSakits = Sakit::with(['dibuatOleh', 'disetujuiOleh', 'diketahuiOleh'])
        ->where(function ($query) use ($user) {
            $query->where('setujui', '!=', 'tunggu')
                  ->orWhere('ketahui', '!=', 'tunggu');
        })->get();

        // Kembalikan view dengan data pengajuan sakit yang telah diambil
        return view('manager-operasional.approve.sakit', compact('sakits', 'riwayatSakits'));
    }

    public function disetujuiTerima($id)
    {
        $sakit = Sakit::findOrFail($id);
        $sakit->setujui = 'diterima';
        $sakit->save();

        return redirect()->back()->with('success', 'Pengajuan sakit disetujui.');
    }

    public function disetujuiTolak($id)
    {
        $sakit = Sakit::findOrFail($id);
        $sakit->setujui = 'ditolak';
        $sakit->save();

        return redirect()->back()->with('success', 'Pengajuan sakit ditolak.');
    }

    public function diketahuiTerima($id)
    {
        $sakit = Sakit::findOrFail($id);
        $sakit->ketahui = 'diterima';
        $sakit->save();

        return redirect()->back()->with('success', 'Pengajuan sakit diketahui.');
    }

    public function diketahuiTolak($id)
    {
        $sakit = Sakit::findOrFail($id);
        $sakit->ketahui = 'ditolak';
        $sakit->save();

        return redirect()->back()->with('success', 'Pengajuan sakit ditolak.');
    }
}
