<?php

namespace App\Http\Controllers\Gudang;

use Illuminate\Http\Request;
use App\Models\Barang;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::all();
        return view('barang.index', compact('barangs'));
    }

    public function create()
    {
        return view('barang.create');
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_barang' => 'required',
            'qty' => 'required|integer',
            'harga_satuan' => 'required|numeric',
            'total' => 'required|numeric',
        ]);

        // Simpan data ke database
        Barang::create($request->all());

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        return view('barang.edit', compact('barang'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'nama_barang' => 'required',
            'qty' => 'required|integer',
            'harga_satuan' => 'required|numeric',
            'total' => 'required|numeric',
        ]);

        // Temukan data barang yang akan diperbarui
        $barang = Barang::findOrFail($id);
        
        // Perbarui data barang dengan data baru
        $barang->update($request->all());

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        // Cari barang berdasarkan ID
        $barang = Barang::findOrFail($id);
        
        // Hapus barang
        $barang->delete();

        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus.');
    }
}
