<?php

namespace App\Http\Controllers\StaffOffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sakit;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PengajuanSakitController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // Ambil data sakits dari database
        $sakits = Sakit::where('dibuat_oleh', $user->id)->get();

        return view('staff-office.pengajuan-sakit.index', compact('sakits'));
    }

    public function create()
    {
        $roles = ['admin', 'direktur', 'manager-operasional', 'manager-territory', 'manager-keuangan', 'area-manager', 'kepala-cabang', 'kepala-gudang'];
        $users = User::whereIn('role', $roles)->get();
        return view('staff-office.pengajuan-sakit.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mulai_sakit' => 'required|date',
            'selesai_sakit' => 'required|date|after_or_equal:mulai_sakit',
            'alasan' => 'required|string',
            'disetujui_oleh' => 'required|exists:users,id',
            'diketahui_oleh' => 'required|exists:users,id',
        ]);

        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk membuat pengajuan sakit.');
        }

        // Hitung jumlah hari sakit yang diajukan
        $startDate = Carbon::parse($request->mulai_sakit);
        $endDate = Carbon::parse($request->selesai_sakit);
        $daysRequested = $startDate->diffInDays($endDate) + 1;

        // Validasi surat dokter jika hari sakit lebih dari 3 hari
        if ($daysRequested > 3) {
            $request->validate([
                'surat_dokter' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);
        } else {
            $request->validate([
                'surat_dokter' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);
        }

        DB::beginTransaction();

        try {
            $sakit = new Sakit();
            $sakit->mulai_sakit = $request->mulai_sakit;
            $sakit->selesai_sakit = $request->selesai_sakit;
            $sakit->alasan = $request->alasan;
            $sakit->disetujui_oleh = $request->disetujui_oleh;
            $sakit->diketahui_oleh = $request->diketahui_oleh;
            $sakit->setujui = "tunggu";
            $sakit->ketahui = "tunggu";
            $sakit->dibuat_oleh = $user->id;

            if ($request->hasFile('surat_dokter')) {
                $file = $request->file('surat_dokter');
                $filename = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('surat_dokter', $filename, 'public');
                $sakit->surat_dokter = $filePath;
            }

            $sakit->save();

            DB::commit();

            Log::info('Pengajuan sakit berhasil ditambahkan.', ['sakit' => $sakit]);

            return redirect()->route('staff-office.pengajuan-sakit.index')->with('success', 'Pengajuan sakit berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Pengajuan sakit gagal disimpan.', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan. Pengajuan sakit gagal disimpan.');
        }
    }


    public function edit($id)
    {
        $sakit = Sakit::findOrFail($id);
        $roles = ['admin', 'direktur', 'manager-operasional', 'manager-territory', 'manager-keuangan', 'area-manager', 'kepala-cabang', 'kepala-gudang'];
        $users = User::whereIn('role', $roles)->get();
        return view('staff-office.pengajuan-sakit.edit', compact('sakit', 'users'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'mulai_sakit' => 'required|date',
            'selesai_sakit' => 'required|date|after_or_equal:mulai_sakit',
            'alasan' => 'required|string',
            'disetujui_oleh' => 'required|exists:users,id',
            'diketahui_oleh' => 'required|exists:users,id',
        ]);

        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk mengupdate pengajuan sakit.');
        }

        // Hitung jumlah hari sakit yang diajukan
        $startDate = Carbon::parse($request->mulai_sakit);
        $endDate = Carbon::parse($request->selesai_sakit);
        $daysRequested = $startDate->diffInDays($endDate) + 1;

        // Validasi surat dokter jika hari sakit lebih dari 3 hari
        if ($daysRequested > 3) {
            $request->validate([
                'surat_dokter' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);
        } else {
            $request->validate([
                'surat_dokter' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);
        }

        DB::beginTransaction();

        try {
            $sakit = Sakit::findOrFail($id);
            $sakit->mulai_sakit = $request->mulai_sakit;
            $sakit->selesai_sakit = $request->selesai_sakit;
            $sakit->alasan = $request->alasan;
            $sakit->disetujui_oleh = $request->disetujui_oleh;
            $sakit->diketahui_oleh = $request->diketahui_oleh;
            $sakit->setujui = "tunggu";
            $sakit->ketahui = "tunggu";
            $sakit->dibuat_oleh = Auth::user()->id;

            if ($request->hasFile('surat_dokter')) {
                $file = $request->file('surat_dokter');
                $filename = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('surat_dokter', $filename, 'public');
                $sakit->surat_dokter = $filePath;
            }

            $sakit->save();

            DB::commit();

            Log::info('Pengajuan sakit berhasil diperbarui.', ['sakit' => $sakit]);

            return redirect()->route('staff-office.pengajuan-sakit.index')->with('success', 'Pengajuan sakit berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Pengajuan sakit gagal diperbarui.', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan. Pengajuan sakit gagal diperbarui.');
        }
    }


    public function destroy($id)
    {
        $sakit = Sakit::findOrFail($id);
        $sakit->delete();

        Log::info('Pengajuan sakit berhasil dihapus.', ['sakit_id' => $id]);

        return redirect()->route('staff-office.pengajuan-sakit.index')->with('success', 'Pengajuan sakit berhasil dihapus.');
    }

    public function view($id)
    {
        // Temukan pengajuan sakit berdasarkan ID
        $sakit = Sakit::findOrFail($id);

        // Hitung lamanya sakit dalam hari
        $mulaiSakit = new \DateTime($sakit->mulai_sakit);
        $selesaiSakit = new \DateTime($sakit->selesai_sakit);
        $durasiSakit = $mulaiSakit->diff($selesaiSakit)->days + 1; // Tambah 1 hari karena inklusif

        // Hitung tanggal kembali bekerja
        $tanggalKembali = $selesaiSakit->modify('+1 day')->format('d F Y');

        // Path ke template dokumen yang sudah ada
        $templatePath = public_path('surat/pengajuan-sakit/Pengajuan_Sakit.docx');

        // Membuat objek PHPWord dari template
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

        // Mendefinisikan format tanggal dengan nama bulan dalam bahasa Indonesia
        setlocale(LC_TIME, 'id_ID.utf8');

        // Mengganti placeholder dengan nilai yang sesuai
        $templateProcessor->setValue('DisetujuiOleh', $sakit->disetujuiOleh->nama ?? 'User Tidak Ditemukan');
        $templateProcessor->setValue('nama', $sakit->dibuatOleh->nama);
        $templateProcessor->setValue('position', $sakit->dibuatOleh->position);
        $templateProcessor->setValue('tanggal_mulai', strftime('%d %B %Y', strtotime($sakit->mulai_sakit)));
        $templateProcessor->setValue('tanggal_selesai', strftime('%d %B %Y', strtotime($sakit->selesai_sakit)));
        $templateProcessor->setValue('alasan', $sakit->alasan);
        $templateProcessor->setImageValue('ttd', [
            'path' => $sakit->dibuatOleh->ttd, 
            'width' => 100,
            'height' => 100 
        ]);

        $templateProcessor->setValue('durasi_sakit', $durasiSakit); // Menampilkan durasi sakit dalam hari
        $templateProcessor->setValue('tanggal_kembali', $tanggalKembali); // Menampilkan tanggal kembali bekerja
        $templateProcessor->setValue('created_at', strftime('%d %B %Y', strtotime($sakit->created_at))); 

        // Path untuk menyimpan file
        $fileName = 'Surat_Pengajuan_Sakit_' . $sakit->id . '.docx';
        $filePath = public_path('surat_pengajuan_sakit/' . $fileName);

        // Simpan dokumen yang telah diisi ke file baru
        try {
            $templateProcessor->saveAs($filePath);
        } catch (\Exception $e) {
            // Jika terjadi kesalahan, kembalikan respons dengan pesan kesalahan
            return back()->with('error', 'Gagal menyimpan file: ' . $e->getMessage());
        }

        // Pastikan file telah tersimpan dengan benar sebelum mendownloadnya
        if (file_exists($filePath)) {
            // Tampilkan surat pengajuan sakit
            return response()->download($filePath)->deleteFileAfterSend(true);
        } else {
            // Jika file tidak dapat disimpan, berikan respons yang sesuai
            return back()->with('error', 'Gagal menyimpan file.');
        }
    }

    public function resubmit($id)
    {
        return $this->edit($id);
    }
}
