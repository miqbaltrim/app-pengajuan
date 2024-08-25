<?php

namespace App\Http\Controllers\StaffOffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Izin;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PengajuanIzinController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $izins = $user->izins()->get();

        return view('staff-office.pengajuan-izin.index', compact('izins'));
    }

    public function create()
    {
        $roles = ['admin','direktur', 'manager-operasional', 'manager-territory', 'manager-keuangan', 'area-manager', 'kepala-cabang', 'kepala-gudang'];
        $users = User::whereIn('role', $roles)->get();
        return view('staff-office.pengajuan-izin.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mulai_izin' => 'required|date',
            'selesai_izin' => 'required|date|after_or_equal:mulai_izin',
            'alasan' => 'required|string',
            'disetujui_oleh' => 'required|exists:users,id',
            'diketahui_oleh' => 'required|exists:users,id',
        ]);

        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk membuat pengajuan izin.');
        }

        // Hitung jumlah hari izin yang diajukan dalam bulan yang sama
        $startDate = Carbon::parse($request->mulai_izin);
        $endDate = Carbon::parse($request->selesai_izin);
        $daysRequested = $startDate->diffInDays($endDate) + 1;

        $totalDaysInMonth = Izin::where('dibuat_oleh', $user->id)
            ->whereMonth('mulai_izin', $startDate->month)
            ->whereYear('mulai_izin', $startDate->year)
            ->sum(DB::raw('DATEDIFF(selesai_izin, mulai_izin) + 1'));

        if ($totalDaysInMonth + $daysRequested > 3) {
            return redirect()->back()->with('error', 'Anda tidak dapat mengajukan izin lebih dari 3 hari dalam sebulan.');
        }

        DB::beginTransaction();

        try {
            $izin = new Izin();
            $izin->mulai_izin = $request->mulai_izin;
            $izin->selesai_izin = $request->selesai_izin;
            $izin->alasan = $request->alasan;
            $izin->disetujui_oleh = $request->disetujui_oleh;
            $izin->diketahui_oleh = $request->diketahui_oleh;
            $izin->setujui = "tunggu";
            $izin->ketahui = "tunggu";
            $izin->dibuat_oleh = $user->id;
            $izin->save();

            DB::commit();
            
            Log::info('Pengajuan izin berhasil ditambahkan.', ['izin' => $izin]);

            return redirect()->route('staff-office.pengajuan-izin.index')->with('success', 'Pengajuan izin berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Pengajuan izin gagal disimpan.', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan. Pengajuan izin gagal disimpan.');
        }
    }

    public function edit($id)
    {
        $izin = Izin::findOrFail($id);
        $roles = ['admin','direktur', 'manager-operasional', 'manager-territory', 'manager-keuangan', 'area-manager', 'kepala-cabang', 'kepala-gudang'];
        $users = User::whereIn('role', $roles)->get();
        return view('staff-office.pengajuan-izin.edit', compact('izin', 'users'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'mulai_izin' => 'required|date',
            'selesai_izin' => 'required|date|after_or_equal:mulai_izin',
            'alasan' => 'required|string',
            'disetujui_oleh' => 'required|exists:users,id',
            'diketahui_oleh' => 'required|exists:users,id',
        ]);

        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk membuat pengajuan izin.');
        }

        // Hitung jumlah hari izin yang diajukan dalam bulan yang sama
        $startDate = Carbon::parse($request->mulai_izin);
        $endDate = Carbon::parse($request->selesai_izin);
        $daysRequested = $startDate->diffInDays($endDate) + 1;

        $totalDaysInMonth = Izin::where('dibuat_oleh', $user->id)
            ->whereMonth('mulai_izin', $startDate->month)
            ->whereYear('mulai_izin', $startDate->year)
            ->sum(DB::raw('DATEDIFF(selesai_izin, mulai_izin) + 1'));

        if ($totalDaysInMonth + $daysRequested > 3) {
            return redirect()->back()->with('error', 'Anda tidak dapat mengajukan izin lebih dari 3 hari dalam sebulan.');
        }

        DB::beginTransaction();

        try {
            $izin = Izin::findOrFail($id);
            $izin->mulai_izin = $request->mulai_izin;
            $izin->selesai_izin = $request->selesai_izin;
            $izin->alasan = $request->alasan;
            $izin->disetujui_oleh = $request->disetujui_oleh;
            $izin->diketahui_oleh = $request->diketahui_oleh;
            $izin->setujui = "tunggu";
            $izin->ketahui = "tunggu";
            $izin->dibuat_oleh = Auth::user()->id;
            $izin->save();

            DB::commit();
            
            Log::info('Pengajuan izin berhasil diperbarui.', ['izin' => $izin]);

            return redirect()->route('staff-office.pengajuan-izin.index')->with('success', 'Pengajuan izin berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Pengajuan izin gagal diperbarui.', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan. Pengajuan izin gagal diperbarui.');
        }
    }

    public function destroy($id)
    {
        $izin = Izin::findOrFail($id);
        $izin->delete();

        Log::info('Pengajuan izin berhasil dihapus.', ['izin_id' => $id]);

        return redirect()->route('staff-office.pengajuan-izin.index')->with('success', 'Pengajuan izin berhasil dihapus.');
    }

    public function view($id)
    {
        // Temukan pengajuan izin berdasarkan ID
        $izin = Izin::findOrFail($id);

        // Hitung lamanya izin dalam hari
        $mulaiIzin = new \DateTime($izin->mulai_izin);
        $selesaiIzin = new \DateTime($izin->selesai_izin);
        $durasiIzin = $mulaiIzin->diff($selesaiIzin)->days + 1; // Tambah 1 hari karena inklusif

        // Hitung tanggal kembali bekerja
        $tanggalKembali = $selesaiIzin->modify('+1 day')->format('d F Y');

        // Path ke template dokumen yang sudah ada
        $templatePath = public_path('surat/pengajuan-izin/Pengajuan_Izin.docx');

        // Membuat objek PHPWord dari template
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

        // Mendefinisikan format tanggal dengan nama bulan dalam bahasa Indonesia
        setlocale(LC_TIME, 'id_ID.utf8');

        // Mengganti placeholder dengan nilai yang sesuai
        $templateProcessor->setValue('DisetujuiOleh', $izin->disetujuiOleh->nama ?? 'User Tidak Ditemukan');
        $templateProcessor->setValue('nama', $izin->dibuatOleh->nama);
        $templateProcessor->setValue('position', $izin->dibuatOleh->position);
        $templateProcessor->setValue('tanggal_mulai', strftime('%d %B %Y', strtotime($izin->mulai_izin)));
        $templateProcessor->setValue('tanggal_selesai', strftime('%d %B %Y', strtotime($izin->selesai_izin)));
        $templateProcessor->setValue('alasan', $izin->alasan);
        $templateProcessor->setImageValue('ttd', [
            'path' => $izin->dibuatOleh->ttd, 
            'width' => 100,
            'height' => 100 
        ]);

        $templateProcessor->setValue('durasi_izin', $durasiIzin); // Menampilkan durasi izin dalam hari
        $templateProcessor->setValue('tanggal_kembali', $tanggalKembali); // Menampilkan tanggal kembali bekerja
        $templateProcessor->setValue('created_at', strftime('%d %B %Y', strtotime($izin->created_at))); 

        // Path untuk menyimpan file
        $fileName = 'Surat_Pengajuan_Izin_' . $izin->id . '.docx';
        $filePath = public_path('surat_pengajuan_izin/' . $fileName);

        // Simpan dokumen yang telah diisi ke file baru
        try {
            $templateProcessor->saveAs($filePath);
        } catch (\Exception $e) {
            // Jika terjadi kesalahan, kembalikan respons dengan pesan kesalahan
            return back()->with('error', 'Gagal menyimpan file: ' . $e->getMessage());
        }

        // Pastikan file telah tersimpan dengan benar sebelum mendownloadnya
        if (file_exists($filePath)) {
            // Tampilkan surat pengajuan izin
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
