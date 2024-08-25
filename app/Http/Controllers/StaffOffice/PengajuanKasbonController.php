<?php

namespace App\Http\Controllers\StaffOffice;

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

class PengajuanKasbonController extends Controller
{
    public function index()
    {
        // Dapatkan pengguna yang sedang login
        $user = Auth::user();

        // Ambil semua data pengajuan kasbon yang diajukan oleh pengguna yang sedang login
        $kasbons = Kasbon::where('user_id', $user->id)->get();
        
        // Kembalikan view pengajuan kasbon dengan data pengajuan kasbon yang telah diambil
        return view('staff-office.pengajuan-kasbon.index', compact('kasbons'));
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
        return view('staff-office.pengajuan-kasbon.create', compact('user'));
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

        // Validasi gaji pengguna
        $gaji = Gaji::where('user_id', $user->id)->value('gaji');
        if ($gaji === null) {
            return redirect()->back()->with('error', 'Gaji pengguna tidak ditemukan.');
        }

        // Cek total kasbon yang sudah diajukan dan belum lunas
        $totalKasbonDiajukan = Kasbon::where('user_id', $user->id)->where('status_cicilan', 'belum lunas')->sum('jml_kasbon');

        // Periksa apakah total kasbon yang diajukan (termasuk yang baru) melebihi gaji
        if (($totalKasbonDiajukan + $request->jml_kasbon) > $gaji) {
            return redirect()->back()->with('error', 'Kasbon anda yang sebelumnya belum lunas.');
        }

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Temukan ID pengguna berdasarkan peran yang dipilih untuk disetujui
            $disetujuiOlehUser = User::where('role', $request->disetujui_oleh)->firstOrFail();
            $diketahuiOlehUser = User::where('role', $request->diketahui_oleh)->firstOrFail();

            // Simpan data ke database
            $kasbon = new Kasbon();
            $kasbon->user_id = $user->id;
            $kasbon->keterangan = $request->input('keterangan');
            $kasbon->jml_kasbon = $request->input('jml_kasbon');
            $kasbon->cicilan = $request->input('cicilan');
            $kasbon->sisa_cicilan = $request->input('jml_kasbon'); // Set nilai awal sisa_cicilan sama dengan jumlah kasbon
            $kasbon->disetujui_oleh = $disetujuiOlehUser->id;
            $kasbon->diketahui_oleh = $diketahuiOlehUser->id;
            $kasbon->status_cicilan = 'belum lunas'; // Set default status cicilan

            // Simpan data dan tampilkan pesan sukses atau error
            $kasbon->save();

            // Commit transaksi jika semua operasi berhasil
            DB::commit();

            return redirect()->route('staff-office.pengajuan-kasbon.index')->with('success', 'Kasbon berhasil dibuat.');
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
        return view('staff-office.pengajuan-kasbon.edit', compact('kasbon'));
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

        // Validasi gaji pengguna
        $gaji = Gaji::where('user_id', $kasbon->user_id)->value('gaji');
        if ($gaji === null) {
            return redirect()->back()->with('error', 'Gaji pengguna tidak ditemukan.');
        }

        // Cek total kasbon yang sudah diajukan dan belum lunas serta mengurangi kasbon yang akan diupdate
        $totalKasbonDiajukan = Kasbon::where('user_id', $kasbon->user_id)->where('status_cicilan', 'belum lunas')->sum('jml_kasbon') - $kasbon->jml_kasbon;

        // Validasi apakah jumlah kasbon yang diupdate melebihi gaji
        if (($totalKasbonDiajukan + $request->jml_kasbon) > $gaji) {
            return redirect()->back()->withErrors(['jml_kasbon' => 'Jumlah kasbon tidak boleh melebihi gaji.']);
        }

        // Dapatkan ID pengguna yang sesuai dengan peran yang dipilih untuk disetujui oleh
        $disetujuiOlehUser = User::where('role', $request->disetujui_oleh)->firstOrFail();
        $diketahuiOlehUser = User::where('role', $request->diketahui_oleh)->firstOrFail();

        // Update data kasbon
        $kasbon->keterangan = $request->input('keterangan');
        $kasbon->jml_kasbon = $request->input('jml_kasbon');
        $kasbon->cicilan = $request->input('cicilan');
        $kasbon->sisa_cicilan = $request->input('jml_kasbon'); // Update nilai sisa_cicilan
        $kasbon->disetujui_oleh = $disetujuiOlehUser->id;
        $kasbon->diketahui_oleh = $diketahuiOlehUser->id;

        // Simpan perubahan
        $kasbon->save();

        // Redirect dengan pesan sukses
        return redirect()->route('staff-office.pengajuan-kasbon.index')->with('success', 'Kasbon berhasil diperbarui.');
    }

    public function destroy($id)
    {
        // Dapatkan data kasbon berdasarkan ID
        $kasbon = Kasbon::findOrFail($id);

        // Hapus data kasbon
        $kasbon->delete();

        // Redirect dengan pesan sukses
        return redirect()->route('staff-office.pengajuan-kasbon.index')->with('success', 'Kasbon berhasil dihapus.');
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
