<?php

namespace App\Http\Controllers\KepalaGudang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kasbon;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\Row;
use PhpOffice\PhpWord\Element\Cell;


class KasbonApprovalControllerKG extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil daftar pengajuan kasbon yang statusnya 'tunggu'
        $kasbons = Kasbon::with(['user', 'disetujuiOleh', 'diketahuiOleh'])
            ->where(function ($query) use ($user) {
                $query->where(function ($q) use ($user) {
                    $q->where('setujui', 'tunggu')
                      ->where('disetujui_oleh', $user->id);
                })->orWhere(function ($q) use ($user) {
                    $q->where('ketahui', 'tunggu')
                      ->where('diketahui_oleh', $user->id);
                });
            })->get();

        // Kembalikan view dengan data pengajuan kasbon yang telah diambil
        return view('kepala-gudang.approve.kasbon', compact('kasbons'));
    }

    public function disetujuiTerima($id)
    {
        $kasbon = Kasbon::findOrFail($id);
        $kasbon->setujui = 'diterima';
        $kasbon->save();

        return redirect()->back()->with('success', 'Pengajuan kasbon disetujui.');
    }

    public function disetujuiTolak($id)
    {
        $kasbon = Kasbon::findOrFail($id);
        $kasbon->setujui = 'ditolak';
        $kasbon->save();

        return redirect()->back()->with('success', 'Pengajuan kasbon ditolak.');
    }

    public function diketahuiTerima($id)
    {
        $kasbon = Kasbon::findOrFail($id);
        $kasbon->ketahui = 'diterima';
        $kasbon->save();

        return redirect()->back()->with('success', 'Pengajuan kasbon diketahui.');
    }

    public function diketahuiTolak($id)
    {
        $kasbon = Kasbon::findOrFail($id);
        $kasbon->ketahui = 'ditolak';
        $kasbon->save();

        return redirect()->back()->with('success', 'Pengajuan kasbon ditolak.');
    }
}