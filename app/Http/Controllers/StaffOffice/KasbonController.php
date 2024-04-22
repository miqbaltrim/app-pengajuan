<?php

namespace App\Http\Controllers\StaffOffice;

use Illuminate\Http\Request;
use App\Models\Kasbon;

class KasbonController extends Controller
{
    public function index()
    {
        $kasbons = Kasbon::all();
        return view('staff-office.pengajuan-kasbon.index', compact('kasbons'));
    }

    public function create()
    {
        return view('kasbon.create');
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required',
            'divisi' => 'required',
            'keterangan' => 'required',
            'jumlah_kasbon' => 'required|numeric',
        ]);

        // Simpan data ke database
        Kasbon::create($request->all());

        return redirect()->route('kasbon.index')->with('success', 'Kasbon berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kasbon = Kasbon::findOrFail($id);
        return view('kasbon.edit', compact('kasbon'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required',
            'divisi' => 'required',
            'keterangan' => 'required',
            'jumlah_kasbon' => 'required|numeric',
        ]);

        // Temukan data kasbon yang akan diperbarui
        $kasbon = Kasbon::findOrFail($id);
        
        // Perbarui data kasbon dengan data baru
        $kasbon->update($request->all());

        return redirect()->route('kasbon.index')->with('success', 'Kasbon berhasil diperbarui.');
    }

    public function destroy($id)
    {
        // Cari kasbon berdasarkan ID
        $kasbon = Kasbon::findOrFail($id);
        
        // Hapus kasbon
        $kasbon->delete();

        return redirect()->route('kasbon.index')->with('success', 'Kasbon berhasil dihapus.');
    }
}
