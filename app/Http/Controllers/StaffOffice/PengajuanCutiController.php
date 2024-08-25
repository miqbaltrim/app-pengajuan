<?php

namespace App\Http\Controllers\StaffOffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ajucuti;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PengajuanCutiController extends Controller
{
    public function index()
    {
        // Get current user's leave applications
        $ajucutis = Ajucuti::where('id_user', Auth::id())->get();

        // Get the default leave days from the database
        $user = User::find(Auth::id());
        $totalCuti = $user->jml_cuti;

        // Begin database transaction
        DB::beginTransaction();

        try {
            // Get today's date
            $today = Carbon::today();

            // Calculate remaining leave days for the current user
            $usedCuti = 0;
            foreach ($ajucutis as $ajucuti) {
                if ($ajucuti->id_user == Auth::id()) {
                    $mulaiCuti = strtotime($ajucuti->mulai_cuti);
                    $selesaiCuti = strtotime($ajucuti->selesai_cuti);
                    $hariCuti = ($selesaiCuti - $mulaiCuti) / (60 * 60 * 24) + 1;

                    if ($ajucuti->status == 'disetujui') {
                        $usedCuti += $hariCuti;
                    }

                    if ($ajucuti->status == 'disetujui' && $today->greaterThan(Carbon::parse($ajucuti->selesai_cuti))) {
                        $ajucuti->status = 'selesai';
                        $ajucuti->save();
                    }
                }
            }

            $remainingCuti = $totalCuti - $usedCuti;

            // Commit database transaction
            DB::commit();
        } catch (\Exception $e) {
            // Rollback transaction if an error occurs
            DB::rollback();
            // Handle error
            // ...
        }

        // Display view index.blade.php in the staff-office/pengajuan-cuti directory
        return view('staff-office.pengajuan-cuti.index', compact('ajucutis', 'remainingCuti'));
    }

    public function reset()
    {
        // Get current user data
        $user = User::find(Auth::id());

        // Begin database transaction
        DB::beginTransaction();

        try {
            // Reset leave days to default value (e.g., 12)
            $user->jml_cuti = 12;
            $user->save();

            // Delete all leave applications for the current user
            Ajucuti::where('id_user', Auth::id())->delete();

            // Commit database transaction
            DB::commit();

            // Redirect with success message
            return redirect()->route('staff-office.pengajuan-cuti.index')->with('success', 'Jumlah cuti berhasil direset.');
        } catch (\Exception $e) {
            // Rollback transaction if an error occurs
            DB::rollback();
            // Handle error
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mereset jumlah cuti.');
        }
    }

    public function riwayatDashboard()
    {
        // Get all leave history from the Ajucuti model
        $riwayatCuti = Ajucuti::all();

        // Display view index.blade.php in the staff-office/pengajuan-cuti directory
        return view('staff-office.dashboard', compact('riwayatCuti'));
    }

    public function create()
    {
        return view('staff-office.pengajuan-cuti.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'mulai_cuti' => 'required|date',
        'selesai_cuti' => 'required|date|after_or_equal:mulai_cuti',
        'alasan' => 'required|string',
        'approved' => 'required|in:admin,direktur,manager-operasional,manager-territory,manager-keuangan,area-manager,kepala-cabang,kepala-gudang',
    ]);

    // Get current user data
    $user = User::find(Auth::id());
    $totalCuti = $user->jml_cuti;

    // Get current month
    $currentMonth = Carbon::now()->format('m');

    // Get total days of leave taken in the current month
    $currentMonthDaysCount = Ajucuti::where('id_user', Auth::id())
        ->whereMonth('mulai_cuti', $currentMonth)
        ->where('status', 'disetujui')
        ->sum(DB::raw('DATEDIFF(selesai_cuti, mulai_cuti) + 1'));

    // Calculate days requested in the new leave application
    $mulaiCuti = new Carbon($request->mulai_cuti);
    $selesaiCuti = new Carbon($request->selesai_cuti);
    $requestedDays = $selesaiCuti->diffInDays($mulaiCuti) + 1;

    // Check if total days in the month exceed the limit (e.g., 3 days)
    $maxDaysPerMonth = 3;
    if (($currentMonthDaysCount + $requestedDays) > $maxDaysPerMonth) {
        return redirect()->back()->with('error', 'Maaf, jumlah hari cuti dalam bulan ini melebihi batas maksimal 3 hari.');
    }

    // Check if there are already 3 people on leave on any requested day with the same role
    $maxLeavePerDay = 3;
    $datesRequested = [];
    for ($date = $mulaiCuti; $date->lte($selesaiCuti); $date->addDay()) {
        $datesRequested[] = $date->format('Y-m-d');
    }

    $userRole = $user->role;

    foreach ($datesRequested as $date) {
        $leaveCount = Ajucuti::whereDate('mulai_cuti', '<=', $date)
            ->whereDate('selesai_cuti', '>=', $date)
            ->where('status', 'disetujui')
            ->whereHas('user', function ($query) use ($userRole) {
                $query->where('role', $userRole);
            })
            ->count();

        if ($leaveCount >= $maxLeavePerDay) {
            return redirect()->back()->with('error', 'Maaf, pada tanggal ' . $date . ' sudah ada 3 orang ' . $userRole . ' yang mengambil cuti.');
        }
    }

    DB::beginTransaction();

    try {
        // Calculate total leave days taken by the current user
        $ajucutis = Ajucuti::where('id_user', Auth::id())->get();
        $usedCuti = 0;
        foreach ($ajucutis as $ajucuti) {
            if ($ajucuti->status == 'disetujui') {
                $mulaiCuti = strtotime($ajucuti->mulai_cuti);
                $selesaiCuti = strtotime($ajucuti->selesai_cuti);
                $hariCuti = ($selesaiCuti - $mulaiCuti) / (60 * 60 * 24) + 1;
                $usedCuti += $hariCuti;
            }
        }

        // Check if the user tries to take more leave than available
        if (($totalCuti - $usedCuti - $requestedDays) < 0) {
            DB::rollback();
            return redirect()->back()->with('error', 'Maaf, Anda mencoba mengambil cuti melebihi jatah cuti yang tersisa.');
        }

        // Create a new leave application
        $ajucuti = new Ajucuti();
        $ajucuti->id_user = Auth::id();
        $ajucuti->mulai_cuti = $request->mulai_cuti;
        $ajucuti->selesai_cuti = $request->selesai_cuti;
        $ajucuti->alasan = $request->alasan;
        $ajucuti->approved = $request->approved;
        $ajucuti->status = 'tunggu'; // Set initial status to 'tunggu'
        $ajucuti->save();

        DB::commit();

        return redirect()->route('staff-office.pengajuan-cuti.index')->with('success', 'Pengajuan cuti berhasil diajukan.');
    } catch (\Exception $e) {
        DB::rollback();
        return redirect()->back()->with('error', 'Terjadi kesalahan saat mengajukan cuti.');
    }
}




    public function edit($id)
    {
        // Get leave application data by ID
        $ajucuti = Ajucuti::findOrFail($id);

        // Display view edit.blade.php in the staff-office/pengajuan-cuti directory
        return view('staff-office.pengajuan-cuti.edit', compact('ajucuti'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'mulai_cuti' => 'required|date',
            'selesai_cuti' => 'required|date|after_or_equal:mulai_cuti',
            'alasan' => 'required|string',
            'approved' => 'required|in:admin,direktur,manager-operasional,manager-territory,manager-keuangan,area-manager,kepala-cabang,kepala-gudang',
        ]);

        // Get Ajucuti data by ID
        $ajucuti = Ajucuti::findOrFail($id);

        // Get default leave days from the database for the current user
        $user = User::find(Auth::id());
        $totalCuti = $user->jml_cuti;

        // Begin database transaction
        DB::beginTransaction();

        try {
            // Calculate total leave days taken by the current user
            $ajucutis = Ajucuti::where('id_user', Auth::id())->get();
            $usedCuti = 0;
            foreach ($ajucutis as $cuti) {
                if ($cuti->status == 'disetujui') {
                    $mulaiCuti = strtotime($cuti->mulai_cuti);
                    $selesaiCuti = strtotime($cuti->selesai_cuti);
                    $hariCuti = ($selesaiCuti - $mulaiCuti) / (60 * 60 * 24) + 1;
                    $usedCuti += $hariCuti;
                }
            }

            // Calculate leave days requested in this application
            $mulaiCuti = strtotime($request->mulai_cuti);
            $selesaiCuti = strtotime($request->selesai_cuti);
            $hariCuti = ($selesaiCuti - $mulaiCuti) / (60 * 60 * 24) + 1;

            // Check if the user tries to take more leave than available
            if (($totalCuti - $usedCuti - $hariCuti) < 0) {
                DB::rollback();
                return redirect()->back()->with('error', 'Maaf, Anda mencoba mengambil cuti melebihi jatah cuti yang tersisa.');
            }

            // Update leave application data
            $ajucuti->mulai_cuti = $request->mulai_cuti;
            $ajucuti->selesai_cuti = $request->selesai_cuti;
            $ajucuti->alasan = $request->alasan;
            $ajucuti->approved = $request->approved;
            $ajucuti->save();

            // Commit database transaction
            DB::commit();

            // Redirect with success message
            return redirect()->route('staff-office.pengajuan-cuti.index')->with('success', 'Pengajuan cuti berhasil diperbarui.');
        } catch (\Exception $e) {
            // Rollback transaction if an error occurs
            DB::rollback();
            // Handle error
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui pengajuan cuti.');
        }
    }

    public function destroy($id)
    {
        // Get leave application data by ID
        $ajucuti = Ajucuti::findOrFail($id);

        // Begin database transaction
        DB::beginTransaction();

        try {
            // Delete leave application
            $ajucuti->delete();

            // Commit database transaction
            DB::commit();

            // Redirect with success message
            return redirect()->route('staff-office.pengajuan-cuti.index')->with('success', 'Pengajuan cuti berhasil dihapus.');
        } catch (\Exception $e) {
            // Rollback transaction if an error occurs
            DB::rollback();
            // Handle error
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus pengajuan cuti.');
        }
    }

    public function resubmit(Request $request, $id)
{
    $ajucuti = Ajucuti::findOrFail($id);
    $ajucuti->status = 'tunggu';
    $ajucuti->save();

    return redirect()->route('staff-office.pengajuan-cuti.edit', $ajucuti->id)->with('success', 'Pengajuan cuti berhasil diajukan kembali. Silakan perbarui data cuti jika diperlukan.');
}

    public function view($id)
{
    // Temukan pengajuan cuti berdasarkan ID
    $ajucuti = Ajucuti::findOrFail($id);

    // Hitung lamanya cuti dalam hari
    $mulaiCuti = new \DateTime($ajucuti->mulai_cuti);
    $selesaiCuti = new \DateTime($ajucuti->selesai_cuti);
    $durasiCuti = $mulaiCuti->diff($selesaiCuti)->days + 1; // Tambah 1 hari karena inklusif

    // Hitung tanggal kembali bekerja
    $tanggalKembali = $selesaiCuti->modify('+1 day')->format('d F Y');

    // Path ke template dokumen yang sudah ada
    $templatePath = public_path('surat/pengajuan-cuti/Surat-Pengajuan.docx');

    // Membuat objek PHPWord dari template
    $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

    // Mendefinisikan format tanggal dengan nama bulan dalam bahasa Indonesia
    setlocale(LC_TIME, 'id_ID.utf8');

    // Mengganti placeholder dengan nilai yang sesuai
    $templateProcessor->setValue('DisetujuiOleh', $approved ?? 'User Tidak Ditemukan');
    $templateProcessor->setValue('nama', $ajucuti->user->nama);
    $templateProcessor->setValue('position', $ajucuti->user->position);
    $templateProcessor->setValue('tanggal_mulai', strftime('%d %B %Y', strtotime($ajucuti->mulai_cuti)));
    $templateProcessor->setValue('tanggal_selesai', strftime('%d %B %Y', strtotime($ajucuti->selesai_cuti)));
    $templateProcessor->setValue('alasan', $ajucuti->alasan);
    $templateProcessor->setImageValue('ttd', [
        'path' => $ajucuti->user->ttd, 
        'width' => 100,
        'height' => 100 
    ]);

    $templateProcessor->setValue('durasi_cuti', $durasiCuti); // Menampilkan durasi cuti dalam hari
    $templateProcessor->setValue('tanggal_kembali', $tanggalKembali); // Menampilkan tanggal kembali bekerja
    $templateProcessor->setValue('created_at', strftime('%d %B %Y', strtotime($ajucuti->created_at))); 

    // Path untuk menyimpan file
    $fileName = 'Surat_Pengajuan_Cuti_' . $ajucuti->id . '.docx';
    $filePath = public_path('surat_pengajuan_cuti/' . $fileName);

    // Simpan dokumen yang telah diisi ke file baru
    try {
        $templateProcessor->saveAs($filePath);
    } catch (\Exception $e) {
        // Jika terjadi kesalahan, kembalikan respons dengan pesan kesalahan
        return back()->with('error', 'Gagal menyimpan file: ' . $e->getMessage());
    }

    // Pastikan file telah tersimpan dengan benar sebelum mendownloadnya
    if (file_exists($filePath)) {
        // Tampilkan surat pengajuan cuti
        return response()->download($filePath)->deleteFileAfterSend(true);
    } else {
        // Jika file tidak dapat disimpan, berikan respons yang sesuai
        return back()->with('error', 'Gagal menyimpan file.');
    }
}
}

