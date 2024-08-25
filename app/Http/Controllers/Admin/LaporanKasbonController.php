<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kasbon;
use App\Models\User;
use Carbon\Carbon;
use DB;


class LaporanKasbonController extends Controller
{
    public function index()
    {
        // Retrieve data for the view
        $kasbonPengajuan = Kasbon::all(); // Adjust the query according to your needs

        // Pass data to the view
        return view('admin.laporan-kasbon.index', compact('kasbonPengajuan'));
    }

    public function detail($id)
    {
        $kasbon = Kasbon::with(['user', 'disetujuiOleh', 'diketahuiOleh'])->findOrFail($id); // Mengambil data kasbon beserta relasi yang diperlukan
        return response()->json($kasbon);
    }


    public function search(Request $request)
{
    \Log::info('Search method called');

    // Validasi input
    $request->validate([
        'nama' => 'nullable|string',
        'tahun' => 'nullable|integer|min:1900|max:' . date('Y'),
        'bulan' => 'nullable|integer|min:1|max:12',
    ]);

    // Ambil data kasbon berdasarkan nama, tahun, dan bulan
    $nama = $request->input('nama');
    $tahun = $request->input('tahun');
    $bulan = $request->input('bulan');

    $kasbonPengajuan = Kasbon::query();

    if ($nama) {
        $kasbonPengajuan->whereHas('user', function ($query) use ($nama) {
            $query->where('nama', 'like', "%$nama%");
        });
    }

    if ($tahun) {
        $kasbonPengajuan->whereYear('created_at', $tahun);
    }

    if ($bulan) {
        $kasbonPengajuan->whereMonth('created_at', $bulan);
    }

    $kasbonPengajuan = $kasbonPengajuan->get();

    // Tampilkan hasil pencarian ke halaman view
    return view('admin.laporan-kasbon.index', compact('kasbonPengajuan'));
}
public function update(Request $request, $id)
{
    DB::beginTransaction();

    try {
        $request->validate([
            'sisa_cicilan' => 'required|string',
        ]);

        // Menghapus simbol Rp dan titik dari input sebelum menyimpan ke database
        $sisa_cicilan = str_replace(['Rp ', '.'], '', $request->input('sisa_cicilan'));
        $sisa_cicilan = is_numeric($sisa_cicilan) ? (float) $sisa_cicilan : 0;

        $kasbon = Kasbon::findOrFail($id);

        // Perbarui sisa cicilan
        $kasbon->sisa_cicilan = $sisa_cicilan;

        // Variable untuk pesan popup
        $message = 'Sisa cicilan berhasil diperbarui.';

        // Cek apakah sisa cicilan sudah 0
        if ($kasbon->sisa_cicilan <= 0) {
            $kasbon->status_cicilan = 'lunas';
            $message = 'Kasbon ' . $kasbon->user->nama . ' sudah lunas';
        } else {
            $kasbon->status_cicilan = 'belum lunas';
        }

        $kasbon->save();

        DB::commit();
        return redirect()->back()->with('success', $message);
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Error updating sisa_cicilan: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui sisa cicilan.');
    }
}
public function print($id)
    {
        // Ambil data kasbon berdasarkan ID
        $kasbon = Kasbon::with(['user', 'disetujuiOleh', 'diketahuiOleh'])->findOrFail($id);

        // Render view untuk PDF
        $html = view('admin.laporan-kasbon.print', compact('kasbon'))->render();

        // Inisialisasi jsPDF di view
        return view('admin.laporan-kasbon.print', compact('html'));
    }
    
}

