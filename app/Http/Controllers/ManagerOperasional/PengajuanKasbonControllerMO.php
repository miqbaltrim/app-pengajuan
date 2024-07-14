<?php

namespace App\Http\Controllers\ManagerOperasional;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Gaji;
use App\Models\Kasbon;
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

class PengajuanKasbonControllerMO extends Controller
{
    public function index()
    {
        // Dapatkan pengguna yang sedang login
        $user = Auth::user();

        // Ambil semua data pengajuan kasbon yang diajukan oleh pengguna yang sedang login
        $kasbons = Kasbon::where('user_id', $user->id)->get();
        
        // Kembalikan view pengajuan kasbon dengan data pengajuan kasbon yang telah diambil
        return view('manager-operasional.pengajuan-kasbon.index', compact('kasbons'));
    }
    
    public function create()
    {
        // Dapatkan pengguna yang sedang login
        $user = Auth::user();

        // Pastikan pengguna telah login sebelum melanjutkan
        if (!$user) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk membuat pengajuan.');
        }

        // Kembalikan view pembuatan kasbon dengan data pengguna
        return view('manager-operasional.pengajuan-kasbon.create', compact('user'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'keterangan' => 'required|string',
            'jml_kasbon' => 'required|numeric',
            'cicilan' => 'required|numeric',
            'disetujui_oleh' => 'required|in:admin,direktur,manager-operasional,manager-territory,manager-keuangan,area-manager,kepala-cabang,kepala-gudang',
            'diketahui_oleh' => 'required|in:admin,direktur,manager-operasional,manager-territory,manager-keuangan,area-manager,kepala-cabang,kepala-gudang',
        ]);

        // Dapatkan pengguna yang sedang login
        $user = Auth::user();

        // Pastikan pengguna telah login sebelum melanjutkan
        if (!$user) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk membuat pengajuan.');
        }

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Temukan ID pengguna berdasarkan peran yang dipilih untuk disetujui oleh pengguna
            $disetujuiOlehUser = User::where('role', $request->disetujui_oleh)->firstOrFail();
            
            // Jika peran yang dipilih sama dengan peran pengguna yang sedang login, gunakan pengguna yang sedang login sebagai yang menyetujui
            if ($user->role == $request->disetujui_oleh) {
                $penggunaSetuju = $user;
            } else {
                // Temukan pengguna yang memiliki peran yang sama dengan yang dipilih sebagai disetujui
                $penggunaSetuju = User::where('role', $request->disetujui_oleh)->firstOrFail();
            }

            // Temukan ID pengguna berdasarkan peran yang dipilih untuk diketahui oleh pengguna
            $diketahuiOlehUser = User::where('role', $request->diketahui_oleh)->firstOrFail();

            // Simpan data ke database
            $kasbon = new Kasbon();
            $kasbon->user_id = $user->id; // Mengisi field user_id dengan ID pengguna yang sedang login
            $kasbon->keterangan = $request->input('keterangan');
            $kasbon->jml_kasbon = $request->input('jml_kasbon');
            $kasbon->cicilan = $request->input('cicilan');
            $kasbon->dana_dari = $request->input('dana_dari');
            $kasbon->disetujui_oleh = $disetujuiOlehUser->id; // Simpan ID pengguna yang melakukan persetujuan
            $kasbon->diketahui_oleh = $diketahuiOlehUser->id; // Simpan ID pengguna yang melakukan persetujuan
            // sisanya disesuaikan dengan kebutuhan

            // Simpan data dan tampilkan pesan sukses atau error
            $kasbon->save();

            // Commit transaksi jika semua operasi berhasil
            DB::commit();

            return redirect()->route('manager-operasional.pengajuan-kasbon.index')->with('success', 'Kasbon berhasil dibuat.');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();

            return redirect()->back()->with('error', 'Gagal membuat kasbon. Silakan coba lagi.');
        }
    }

    public function edit($id)
    {
        // Dapatkan data kasbon berdasarkan ID
        $kasbon = Kasbon::findOrFail($id);
        
        // Kembalikan view edit kasbon dengan data kasbon yang ditemukan
        return view('manager-operasional.pengajuan-kasbon.edit', compact('kasbon'));
    }

    public function update(Request $request, $id)
{
    // Validasi input
    $request->validate([
        'keterangan' => 'required|string',
        'jml_kasbon' => 'required|numeric',
        'cicilan' => 'required|numeric',
        'disetujui_oleh' => 'required|in:admin,direktur,manager-operasional,manager-territory,manager-keuangan,area-manager,kepala-cabang,kepala-gudang',
        'diketahui_oleh' => 'required|in:admin,direktur,manager-operasional,manager-territory,manager-keuangan,area-manager,kepala-cabang,kepala-gudang',
    ]);

    // Dapatkan data kasbon berdasarkan ID
    $kasbon = Kasbon::findOrFail($id);

    // Dapatkan ID pengguna yang sesuai dengan peran yang dipilih untuk disetujui oleh
    $disetujuiOlehUser = User::where('role', $request->disetujui_oleh)->firstOrFail();
    
    // Dapatkan ID pengguna yang sesuai dengan peran yang dipilih untuk diketahui oleh
    $diketahuiOlehUser = User::where('role', $request->diketahui_oleh)->firstOrFail();

    // Update data kasbon
    $kasbon->keterangan = $request->input('keterangan');
    $kasbon->jml_kasbon = $request->input('jml_kasbon');
    $kasbon->cicilan = $request->input('cicilan');
    $kasbon->dana_dari = $request->input('dana_dari');
    $kasbon->disetujui_oleh = $disetujuiOlehUser->id; // Gunakan ID pengguna yang sesuai dengan peran yang dipilih
    $kasbon->diketahui_oleh = $diketahuiOlehUser->id; // Gunakan ID pengguna yang sesuai dengan peran yang dipilih
    // sisanya disesuaikan dengan kebutuhan

    // Simpan perubahan
    $kasbon->save();

    // Redirect dengan pesan sukses
    return redirect()->route('manager-operasional.pengajuan-kasbon.index')->with('success', 'Kasbon berhasil diperbarui.');
}

    public function destroy($id)
    {
        // Dapatkan data kasbon berdasarkan ID
        $kasbon = Kasbon::findOrFail($id);

        // Hapus data kasbon
        $kasbon->delete();

        // Redirect dengan pesan sukses
        return redirect()->route('manager-operasional.pengajuan-kasbon.index')->with('success', 'Kasbon berhasil dihapus.');
    }

    public function download($id)
{
    \Log::info('Download function called.');

    // Dapatkan data kasbon berdasarkan ID
    $kasbon = Kasbon::findOrFail($id);
    \Log::info('Kasbon found: ', ['id' => $kasbon->id]);

    // Path template dokumen
    $templatePath = public_path('surat/pengajuan-kasbon/Pengajuan_Kasbon_Template.docx');
    \Log::info('Template path: ' . $templatePath);

    // Pastikan template file ada
    if (!file_exists($templatePath)) {
        \Log::error('Template file not found.');
        return back()->with('error', 'Template file not found.');
    }

    // Membuat objek PHPWord dari template
    $templateProcessor = new TemplateProcessor($templatePath);

    // Mengisi placeholder dengan data kasbon
    $templateProcessor->setValue('Nama', optional($kasbon->user)->nama);
    $templateProcessor->setValue('PosisiUser', optional($kasbon->user)->position);
    $templateProcessor->setValue('Keterangan', $kasbon->keterangan);
    $templateProcessor->setValue('JumlahKasbon', 'Rp ' . number_format($kasbon->jml_kasbon, 0, ',', '.'));
    $templateProcessor->setValue('Cicilan', $kasbon->cicilan);
    $templateProcessor->setValue('DisetujuiOleh', optional($kasbon->disetujuiOleh)->nama);
    $templateProcessor->setValue('DiketahuiOleh', optional($kasbon->diketahuiOleh)->nama);
    $templateProcessor->setValue('PosisiDisetujui', optional($kasbon->disetujuiOleh)->position);
    $templateProcessor->setValue('PosisiDiketahui', optional($kasbon->diketahuiOleh)->position);


    $templateProcessor->setImageValue('TtdDibuat', [
        'path' => $kasbon->user->ttd, 
        'width' => 100,
        'height' => 100 
    ]);
    $templateProcessor->setImageValue('TtdSetujui', [
        'path' => $kasbon->disetujuiOleh->ttd, 
        'width' => 100,
        'height' => 100 
    ]);
    $templateProcessor->setImageValue('TtdKetahui', [
        'path' => $kasbon->diketahuiOleh->ttd, // Path gambar TTD pengguna yang diketahui
        'width' => 100, // Lebar gambar dalam dokumen (opsional)
        'height' => 100 // Tinggi gambar dalam dokumen (opsional)
    ]);
    $templateProcessor->setValue('Tanggal', $kasbon->created_at->format('d F Y'));

    // Menghasilkan nama file
    $fileName = 'Surat_Pengajuan_Kasbon_' . $kasbon->id . '.docx';
    $filePath = public_path('surat_pengajuan_kasbon/' . $fileName);


    // Simpan dokumen yang telah diisi ke file baru
    try {
        $templateProcessor->saveAs($filePath);
        \Log::info('File saved successfully.');
    } catch (\Exception $e) {
        \Log::error('Error saving file: ' . $e->getMessage());
        return back()->with('error', 'Gagal menyimpan file: ' . $e->getMessage());
    }

    // Pastikan file telah tersimpan dengan benar sebelum mendownloadnya
    if (file_exists($filePath)) {
        \Log::info('File exists, preparing download.');
        return response()->download($filePath)->deleteFileAfterSend(true);
    } else {
        \Log::error('File not found after saving.');
        return back()->with('error', 'Gagal menyimpan file atau file tidak ditemukan.');
    }
}


}
