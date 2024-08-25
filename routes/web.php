<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
//------------------------------------------------------------------------------------------------------------------------------------
use App\Http\Controllers\Admin\DataKaryawanController;
use App\Http\Controllers\Admin\DashboardControllerAdmin;
use App\Http\Controllers\Admin\LaporanCutiController;
use App\Http\Controllers\Admin\LaporanBarangController;
use App\Http\Controllers\Admin\LaporanIzinController;
use App\Http\Controllers\Admin\LaporanSakitController;
use App\Http\Controllers\Admin\LaporanKasbonController;
use App\Http\Controllers\Admin\CutiApprovalControllerHR;
use App\Http\Controllers\Admin\IzinApprovalControllerHR;
use App\Http\Controllers\Admin\SakitApprovalControllerHR;
use App\Http\Controllers\Admin\BarangApprovalControllerHR;
use App\Http\Controllers\Admin\KasbonApprovalControllerHR;
//------------------------------------------------------------------------------------------------------------------------------------
use App\Http\Controllers\Direktur\DashboardControllerDR;
use App\Http\Controllers\Direktur\PengajuanCutiControllerDR;
use App\Http\Controllers\Direktur\BarangControllerDR;
use App\Http\Controllers\Direktur\PengajuanControllerDR;
use App\Http\Controllers\Direktur\CutiApprovalControllerDR;
use App\Http\Controllers\Direktur\BarangApprovalControllerDR;
use App\Http\Controllers\Direktur\PengajuanKasbonControllerDR;
use App\Http\Controllers\Direktur\KasbonApprovalControllerDR;
//------------------------------------------------------------------------------------------------------------------------------------
use App\Http\Controllers\StaffOffice\DashboardController;
use App\Http\Controllers\StaffOffice\PengajuanCutiController;
use App\Http\Controllers\StaffOffice\BarangController;
use App\Http\Controllers\StaffOffice\PengajuanController;
use App\Http\Controllers\StaffOffice\PengajuanKasbonController;
use App\Http\Controllers\StaffOffice\PengajuanIzinController;
use App\Http\Controllers\StaffOffice\PengajuanSakitController;
//------------------------------------------------------------------------------------------------------------------------------------
use App\Http\Controllers\Gudang\DashboardControllerGD;
use App\Http\Controllers\Gudang\PengajuanCutiControllerGD;
use App\Http\Controllers\Gudang\BarangControllerGD;
use App\Http\Controllers\Gudang\PengajuanControllerGD;
use App\Http\Controllers\Gudang\PengajuanKasbonControllerGD;
//------------------------------------------------------------------------------------------------------------------------------------
use App\Http\Controllers\AreaManager\BarangControllerAM;
use App\Http\Controllers\AreaManager\PengajuanControllerAM;
use App\Http\Controllers\AreaManager\DashboardControllerAM;
use App\Http\Controllers\AreaManager\PengajuanCutiControllerAM;
use App\Http\Controllers\AreaManager\PengajuanKasbonControllerAM;
use App\Http\Controllers\AreaManager\CutiApprovalController;
use App\Http\Controllers\AreaManager\BarangApprovalController;
use App\Http\Controllers\AreaManager\KasbonApprovalController;
use App\Http\Controllers\AreaManager\IzinApprovalControllerAM;
use App\Http\Controllers\AreaManager\SakitApprovalControllerAM;
//------------------------------------------------------------------------------------------------------------------------------------
use App\Http\Controllers\ManagerKeuangan\BarangControllerMK;
use App\Http\Controllers\ManagerKeuangan\PengajuanControllerMK;
use App\Http\Controllers\ManagerKeuangan\DashboardControllerMK;
use App\Http\Controllers\ManagerKeuangan\PengajuanCutiControllerMK;
use App\Http\Controllers\ManagerKeuangan\CutiApprovalControllerMK;
use App\Http\Controllers\ManagerKeuangan\BarangApprovalControllerMK;
use App\Http\Controllers\ManagerKeuangan\GajiController;
use App\Http\Controllers\ManagerKeuangan\DataKasbonController;
//------------------------------------------------------------------------------------------------------------------------------------
use App\Http\Controllers\ManagerOperasional\DashboardControllerMO;
use App\Http\Controllers\ManagerOperasional\PengajuanCutiControllerMO;
use App\Http\Controllers\ManagerOperasional\BarangControllerMO;
use App\Http\Controllers\ManagerOperasional\PengajuanControllerMO;
use App\Http\Controllers\ManagerOperasional\CutiApprovalControllerMo;
use App\Http\Controllers\ManagerOperasional\BarangApprovalControllerMo;
use App\Http\Controllers\ManagerOperasional\PengajuanKasbonControllerMO;
use App\Http\Controllers\ManagerOperasional\KasbonApprovalControllerMO;
use App\Http\Controllers\ManagerOperasional\IzinApprovalController;
use App\Http\Controllers\ManagerOperasional\SakitApprovalController;
//------------------------------------------------------------------------------------------------------------------------------------
use App\Http\Controllers\KepalaGudang\DashboardControllerKG;
use App\Http\Controllers\KepalaGudang\PengajuanCutiControllerKG;
use App\Http\Controllers\KepalaGudang\BarangControllerKG;
use App\Http\Controllers\KepalaGudang\PengajuanControllerKG;
use App\Http\Controllers\KepalaGudang\CutiApprovalControllerKG;
use App\Http\Controllers\KepalaGudang\BarangApprovalControllerKG;
use App\Http\Controllers\KepalaGudang\PengajuanKasbonControllerKG;
use App\Http\Controllers\KepalaGudang\KasbonApprovalControllerKG;
//------------------------------------------------------------------------------------------------------------------------------------
Route::get('/', function () {
    return Redirect::route('login');
});

Route::get('/connect-database', [DatabaseController::class, 'connectDatabase']);

// Route untuk tampilan login dan registrasi
// routes/web.php
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

//Group route login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout'); // Menangani logout melalui metode POST

//------------------------------------------------------------------------------------------------------------------------------------
// Group route dashboard
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

Route::get('/direktur/dashboard', function () {
    return view('direktur.dashboard');
})->name('direktur.dashboard');

Route::get('/manager-operasional/dashboard', function () {
    return view('manager-operasional.dashboard');
})->name('manager-operasional.dashboard'); // Perbaikan disini, sesuaikan dengan nama yang benar

Route::get('/area-manager/dashboard', function () {
    return view('area-manager.dashboard');
})->name('area-manager.dashboard');

Route::get('/manager-keuangan/dashboard', function () {
    return view('manager-keuangan.dashboard');
})->name('manager-keuangan.dashboard');

Route::get('/kepala-gudang/dashboard', function () {
    return view('kepala-gudang.dashboard');
})->name('kepala-gudang.dashboard');

Route::get('/staff-office/dashboard', function () {
    return view('staff-office.dashboard');
})->name('staff-office.dashboard');

Route::get('/gudang/dashboard', function () {
    return view('gudang.dashboard');
})->name('gudang.dashboard');
//------------------------------------------------------------------------------------------------------------------------------------
//Group route profile user
Route::get('/admin/profile', [ProfileController::class, 'showProfile'])->name('admin.profile');
Route::get('/direktur/profile', [ProfileController::class, 'showProfile'])->name('direktur.profile');
Route::get('/area-manager/profile', [ProfileController::class, 'showProfile'])->name('area-manager.profile');
Route::get('/manager-keuangan/profile', [ProfileController::class, 'showProfile'])->name('manager-keuangan.profile');
Route::get('/manager-operasional/profile', [ProfileController::class, 'showProfile'])->name('manager-operasional.profile');
Route::get('/kepala-gudang/profile', [ProfileController::class, 'showProfile'])->name('kepala-gudang.profile');
Route::get('/staff-office/profile', [ProfileController::class, 'showProfile'])->name('staff-office.profile');
Route::get('/gudang/profile', [ProfileController::class, 'showProfile'])->name('gudang.profile');

Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
Route::put('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
Route::post('/profile/upload-photo', [ProfileController::class, 'uploadPhoto'])->name('profile.upload_photo');
//------------------------------------------------------------------------------------------------------------------------------------
//Group route Admin
Route::group(['middleware' => ['auth', 'App\Http\Middleware\RoleMiddleware:admin']], function () {
    Route::get('/admin/dashboard', [DashboardControllerAdmin::class, 'index'])->name('admin.dashboard');
    // Menambahkan pengumuman baru
    Route::post('/admin/pengumuman', [DashboardControllerAdmin::class, 'postAnnouncement'])->name('admin.post_announcement');
    Route::post('/admin/pengumuman/{id}/comment', [DashboardControllerAdmin::class, 'storeComment'])->name('admin.pengumuman.comment.store');
    // Menampilkan halaman form edit pengumuman
    Route::get('/admin/pengumuman/edit/{id}', [DashboardControllerAdmin::class, 'editAnnouncement'])->name('admin.edit_announcement');
    // Menyimpan perubahan pada pengumuman yang diedit
    Route::put('/admin/pengumuman/update/{id}', [DashboardControllerAdmin::class, 'updateAnnouncement'])->name('admin.update_announcement');
    // Menghapus pengumuman
    Route::delete('/admin/pengumuman/delete/{id}', [DashboardControllerAdmin::class, 'deleteAnnouncement'])->name('admin.delete_announcement');

    Route::get('/admin/data-karyawan', [DataKaryawanController::class, 'index'])->name('admin.data-karyawan.index');
    Route::get('/admin/data-karyawan', [DataKaryawanController::class, 'index'])->name('admin.data-karyawan.create');
    Route::get('/admin/data-karyawan', [DataKaryawanController::class, 'index'])->name('admin.data-karyawan.store');
    
    Route::get('/admin/data-karyawan/create', [DataKaryawanController::class, 'create'])->name('admin.data-karyawan.create');
    Route::post('/admin/data-karyawan', [DataKaryawanController::class, 'store'])->name('admin.data-karyawan.store');
    Route::get('/admin/data-karyawan', [DataKaryawanController::class, 'index'])->name('admin.data-karyawan.index');
    Route::get('/admin/data-karyawan/{id}/edit', [DataKaryawanController::class, 'edit'])->name('admin.data-karyawan.edit');
    Route::put('/admin/data-karyawan/{id}', [DataKaryawanController::class, 'update'])->name('admin.data-karyawan.update');
    Route::delete('/admin/data-karyawan/{id}', [DataKaryawanController::class, 'destroy'])->name('admin.data-karyawan.destroy');
    Route::get('/admin/data-karyawan/reset-password/{id}', [DataKaryawanController::class, 'resetPassword'])->name('admin.data-karyawan.reset-password');

    // Route untuk menampilkan laporan cuti
    Route::get('/admin/laporan-cuti', [LaporanCutiController::class, 'index'])->name('admin.laporan-cuti.index');
    Route::get('/admin/laporan-cuti/search', [LaporanCutiController::class, 'search'])->name('admin.laporan-cuti.search');
    Route::post('/admin/laporan-cuti/search-by-month', [LaporanCutiController::class, 'searchByMonth'])->name('admin.laporan-cuti.searchByMonth');
    // Definisi rute untuk Laporan Cuti
    Route::get('/laporan-cuti', [LaporanCutiController::class, 'index'])->name('admin.laporan-cuti.index');
    Route::get('/laporan-cuti/search', [LaporanCutiController::class, 'search'])->name('admin.laporan-cuti.search');
    
    // Rute untuk reset jumlah cuti
    Route::post('/admin/laporan-cuti/reset', [LaporanCutiController::class, 'reset'])->name('admin.laporan-cuti.reset');

    // Rute untuk data laporan barang
    Route::get('/admin/data-laporan-barang', [LaporanBarangController::class, 'index'])->name('admin.laporan-barang.index');
    Route::get('/admin/laporan-barang/{id}/detail', [LaporanBarangController::class, 'detail'])->name('admin.laporan-barang.detail');
    Route::get('/admin/laporan-barang/search', [LaporanBarangController::class, 'search'])->name('admin.laporan-barang.search');
    Route::post('/admin/laporan-barang/search-by-month', [LaporanBarangController::class, 'searchByMonth'])->name('laporan-barang.searchByMonth');

    Route::get('laporan-kasbon', [LaporanKasbonController::class, 'index'])->name('admin.laporan-kasbon.index');
    Route::get('laporan-kasbon/search', [LaporanKasbonController::class, 'search'])->name('admin.laporan-kasbon.search');
    Route::get('laporan-kasbon/{id}', [LaporanKasbonController::class, 'detail'])->name('admin.laporan-kasbon.detail');
    Route::put('admin/laporan-kasbon/{kasbon}', [LaporanKasbonController::class, 'update'])->name('admin.laporan-kasbon.update');
    Route::get('admin/laporan-kasbon/print/{id}', [LaporanKasbonController::class, 'print'])->name('admin.laporan-kasbon.print');


    Route::get('/admin/laporan-cuti/riwayat-cuti', [LaporanCutiController::class, 'riwayatCuti'])->name('admin.laporan-cuti.riwayat-cuti');

    Route::get('laporan-izin', [LaporanIzinController::class, 'index'])->name('admin.laporan-izin.index');
    Route::post('laporan-izin/search', [LaporanIzinController::class, 'search'])->name('admin.laporan-izin.search');
    Route::post('laporan-izin/search-by-month', [LaporanIzinController::class, 'searchByMonth'])->name('admin.laporan-izin.searchByMonth');
    Route::post('laporan-izin/reset', [LaporanIzinController::class, 'reset'])->name('admin.laporan-izin.reset');
    Route::get('laporan-izin/riwayat', [LaporanIzinController::class, 'riwayatIzin'])->name('admin.laporan-izin.riwayat');

    Route::get('laporan-sakit', [LaporanSakitController::class, 'index'])->name('admin.laporan-sakit.index');
    Route::post('laporan-sakit/search', [LaporanSakitController::class, 'search'])->name('admin.laporan-sakit.search');
    Route::post('laporan-sakit/search-by-month', [LaporanSakitController::class, 'searchByMonth'])->name('admin.laporan-sakit.searchByMonth');
    Route::post('laporan-sakit/reset', [LaporanSakitController::class, 'reset'])->name('admin.laporan-sakit.reset');
    Route::get('laporan-sakit/riwayat', [LaporanSakitController::class, 'riwayatSakit'])->name('admin.laporan-sakit.riwayat');

    // Group Route Approve Pengajuan Cuti Admin
    Route::get('/admin/approve/cuti', [CutiApprovalControllerHR::class, 'index'])->name('admin.approve.cuti');
    Route::post('/admin/approve/cuti/{id}/approve', [CutiApprovalControllerHR::class, 'approve'])->name('admin.approve.cuti.approve');
    Route::post('/approved/{id}/{action}', [CutiApprovalControllerHR::class, 'approve'])->name('approved');
    Route::post('/rejected/{id}', [CutiApprovalControllerHR::class, 'reject'])->name('rejected');
    // Group Route Approve Pengajuan Izin Admin
    Route::get('/admin/izin', [IzinApprovalControllerHR::class, 'index'])->name('admin.approve.izin');
    Route::post('/admin/izin/{id}/disetujui-terima', [IzinApprovalControllerHR::class, 'disetujuiTerima'])->name('admin.approve.izin.disetujui.terima');
    Route::post('/admin/izin/{id}/disetujui-tolak', [IzinApprovalControllerHR::class, 'disetujuiTolak'])->name('admin.approve.izin.disetujui.tolak');
    Route::post('/admin/izin/{id}/diketahui-terima', [IzinApprovalControllerHR::class, 'diketahuiTerima'])->name('admin.approve.izin.diketahui.terima');
    Route::post('/admin/izin/{id}/diketahui-tolak', [IzinApprovalControllerHR::class, 'diketahuiTolak'])->name('admin.approve.izin.diketahui.tolak');
    // Group Route Approve Pengajuan Sakit Admin
    Route::get('/admin/approve-sakit', [SakitApprovalControllerHR::class, 'index'])->name('admin.approve.sakit');
    Route::post('/admin/approve-sakit/{id}/disetujui-terima', [SakitApprovalControllerHR::class, 'disetujuiTerima'])->name('admin.approve.sakit.disetujui-terima');
    Route::post('/admin/approve-sakit/{id}/disetujui-tolak', [SakitApprovalControllerHR::class, 'disetujuiTolak'])->name('admin.approve.sakit.disetujui-tolak');
    Route::post('/admin/approve-sakit/{id}/diketahui-terima', [SakitApprovalControllerHR::class, 'diketahuiTerima'])->name('admin.approve.sakit.diketahui-terima');
    Route::post('/admin/approve-sakit/{id}/diketahui-tolak', [SakitApprovalControllerHR::class, 'diketahuiTolak'])->name('admin.approve.sakit.diketahui-tolak');
    // Group Route Approve Pengajuan Barang Admin
    Route::get('/admin/approve/barang', [BarangApprovalControllerHR::class,'index'])->name('approve.approve.barang');
    Route::get('/admin/approve/barang/{id}', [BarangApprovalControllerHR::class, 'detail'])->name('approve.barang.detail');
    Route::get('/admin/approve/barang/{id}/detail', [BarangApprovalControllerHR::class, 'detail'])->name('admin.approve.barang.detailBarang');
    Route::post('/admin/approve/barang/{id}/disetujui-terima', [BarangApprovalControllerHR::class, 'disetujuiTerima'])->name('admin.approve.barang.disetujui-terima');
    Route::post('/admin/approve/barang/{id}/disetujui-tolak', [BarangApprovalControllerHR::class, 'disetujuiTolak'])->name('admin.approve.barang.disetujui-tolak');
    Route::post('/admin/approve/barang/{id}/diketahui-terima', [BarangApprovalControllerHR::class, 'diketahuiTerima'])->name('admin.approve.barang.diketahui-terima');
    Route::post('/admin/approve/barang/{id}/diketahui-tolak', [BarangApprovalControllerHR::class, 'diketahuiTolak'])->name('admin.approve.barang.diketahui-tolak'); 

    Route::get('/admin/approve/kasbon', [KasbonApprovalControllerHR::class, 'index'])->name('admin.approve.kasbon');
    Route::put('/admin/kasbon/disetujui-terima/{id}', [KasbonApprovalControllerHR::class, 'disetujuiTerima'])->name('admin.kasbon.disetujuiTerima');
    Route::put('/admin/kasbon/disetujui-tolak/{id}', [KasbonApprovalControllerHR::class, 'disetujuiTolak'])->name('admin.kasbon.disetujuiTolak');
    Route::put('/admin/kasbon/diketahui-terima/{id}', [KasbonApprovalControllerHR::class, 'diketahuiTerima'])->name('admin.kasbon.diketahuiTerima');
    Route::put('/admin/kasbon/diketahui-tolak/{id}', [KasbonApprovalControllerHR::class, 'diketahuiTolak'])->name('admin.kasbon.diketahuiTolak'); 

});
//------------------------------------------------------------------------------------------------------------------------------------
// Group route untuk staff office
Route::group(['middleware' => ['auth', 'App\Http\Middleware\RoleMiddleware:staff-office']], function () {
    
    // Route::get('/staff-office/dashboard', [PengajuanCutiController::class, 'riwayatDashboard'])->name('staff-office.dashboard');
    Route::get('/staff-office/dashboard', [DashboardController::class, 'index'])->name('staff-office.dashboard');
    Route::get('/staff-office/download-surat/{id}', [DashboardController::class, 'downloadSurat'])->name('staff-office.download-surat'); // Perbarui nama rute
    Route::get('/staff-office/notifikasi-pengajuan', [NotifikasiPengajuanController::class, 'index'])->name('staff-office.notifikasi-pengajuan.index');
    Route::get('/staff-office/pengajuan-cuti', [PengajuanCutiController::class, 'index'])->name('staff-office.pengajuan-cuti.index');
    Route::get('/staff-office/pengajuan-cuti/create', [PengajuanCutiController::class, 'create'])->name('staff-office.pengajuan-cuti.create');
    Route::post('/staff-office/pengajuan-cuti', [PengajuanCutiController::class, 'store'])->name('staff-office.pengajuan-cuti.store');
    Route::post('/staff-office/pengajuan-cuti/store', [PengajuanCutiController::class, 'store'])->name('staff-office.pengajuan-cuti.store');
    Route::get('/staff-office/pengajuan-cuti/{id}/edit', [PengajuanCutiController::class, 'edit'])->name('staff-office.pengajuan-cuti.edit');
    Route::put('/staff-office/pengajuan-cuti/{id}', [PengajuanCutiController::class, 'update'])->name('staff-office.pengajuan-cuti.update');
    Route::delete('/staff-office/pengajuan-cuti/{id}', [PengajuanCutiController::class, 'destroy'])->name('staff-office.pengajuan-cuti.destroy');
    Route::get('/staff-office/pengajuan-cuti/{id}/view', [PengajuanCutiController::class, 'view'])->name('staff-office.pengajuan-cuti.view');
    Route::get('/staff-office/pengajuan-cuti/{id}/view', [PengajuanCutiController::class, 'view'])->name('staff-office.pengajuan-cuti.view');
    Route::post('/staff-office/pengajuan-cuti/reset', [PengajuanCutiController::class, 'reset'])->name('staff-office.pengajuan-cuti.reset');
    Route::put('/staff-office/pengajuan-cuti/resubmit/{id}', [PengajuanCutiController::class, 'resubmit'])->name('staff-office.pengajuan-cuti.resubmit');
    // Group route staff office pengajuan izin
    Route::get('/staff-office/pengajuan-izin', [PengajuanIzinController::class, 'index'])->name('staff-office.pengajuan-izin.index');
    Route::get('/staff-office/pengajuan-izin/create', [PengajuanIzinController::class, 'create'])->name('staff-office.pengajuan-izin.create');
    Route::post('/staff-office/pengajuan-izin', [PengajuanIzinController::class, 'store'])->name('staff-office.pengajuan-izin.store');
    Route::get('/staff-office/pengajuan-izin/{id}/edit', [PengajuanIzinController::class, 'edit'])->name('staff-office.pengajuan-izin.edit');
    Route::put('/staff-office/pengajuan-izin/{id}', [PengajuanIzinController::class, 'update'])->name('staff-office.pengajuan-izin.update');
    Route::delete('/staff-office/pengajuan-izin/{id}', [PengajuanIzinController::class, 'destroy'])->name('staff-office.pengajuan-izin.destroy');
    Route::get('/staff-office/pengajuan-izin/{id}/view', [PengajuanIzinController::class, 'view'])->name('staff-office.pengajuan-izin.view');
    Route::put('/staff-office/pengajuan-izin/{id}/resubmit', [PengajuanIzinController::class, 'resubmit'])->name('staff-office.pengajuan-izin.resubmit');
    // Group route staff office pengajuan sakit
    Route::get('/staff-office/pengajuan-sakit', [PengajuanSakitController::class, 'index'])->name('staff-office.pengajuan-sakit.index');
    Route::get('/staff-office/pengajuan-sakit/create', [PengajuanSakitController::class, 'create'])->name('staff-office.pengajuan-sakit.create');
    Route::post('/staff-office/pengajuan-sakit', [PengajuanSakitController::class, 'store'])->name('staff-office.pengajuan-sakit.store');
    Route::get('/staff-office/pengajuan-sakit/{id}/edit', [PengajuanSakitController::class, 'edit'])->name('staff-office.pengajuan-sakit.edit');
    Route::put('/staff-office/pengajuan-sakit/{id}', [PengajuanSakitController::class, 'update'])->name('staff-office.pengajuan-sakit.update');
    Route::delete('/staff-office/pengajuan-sakit/{id}', [PengajuanSakitController::class, 'destroy'])->name('staff-office.pengajuan-sakit.destroy');
    Route::get('/staff-office/pengajuan-sakit/{id}/view', [PengajuanSakitController::class, 'view'])->name('staff-office.pengajuan-sakit.view');
    Route::put('/staff-office/pengajuan-sakit/{id}/resubmit', [PengajuanSakitController::class, 'resubmit'])->name('staff-office.pengajuan-sakit.resubmit');

    // Group route staff office pengajuan kasbon
    Route::get('/staff-office/pengajuan-kasbon', [PengajuanKasbonController::class, 'index'])->name('staff-office.pengajuan-kasbon.index');
    Route::get('/staff-office/kasbon/create', [PengajuanKasbonController::class, 'create'])->name('staff-office.pengajuan-kasbon.create');
    Route::post('/staff-office/kasbon', [PengajuanKasbonController::class, 'store'])->name('staff-office.pengajuan-kasbon.store');
    Route::get('/staff-office/pengajuan-kasbon/{id}/edit', [PengajuanKasbonController::class, 'edit'])->name('staff-office.pengajuan-kasbon.edit');
    Route::put('/staff-office/pengajuan-kasbon/{id}', [PengajuanKasbonController::class, 'update'])->name('staff-office.pengajuan-kasbon.update');
    Route::delete('/staff-office/pengajuan-kasbon/{id}', [PengajuanKasbonController::class, 'destroy'])->name('staff-office.pengajuan-kasbon.destroy');
    Route::get('/staff-office/pengajuan-kasbon/view/{id}', [PengajuanKasbonController::class, 'download'])->name('staff-office.pengajuan-kasbon.download');
    });
    // Group route staff office pengajuan barang
    Route::get('/staff-office/pengajuan', [PengajuanController::class, 'index'])->name('staff-office.pengajuan-barang.index');
    Route::get('/staff-office/pengajuan/create', [PengajuanController::class, 'create'])->name('staff-office.pengajuan-barang.create');
    Route::post('/staff-office/pengajuan/store', [PengajuanController::class, 'store'])->name('staff-office.pengajuan-barang.store');
    Route::get('/staff-officepengajuan-barang/{id}/edit', [PengajuanController::class, 'edit'])->name('staff-office.pengajuan-barang.edit');
    Route::get('/staff-officepengajuan-barang/{id}/update', [PengajuanController::class, 'update'])->name('staff-office.pengajuan-barang.update');
    Route::delete('/staff-officepengajuan-barang/{id}', [PengajuanController::class, 'destroy'])->name('staff-office.pengajuan-barang.destroy');
    Route::delete('/staff-office/pengajuan-barang/{id}/delete-item/{item_id}', [PengajuanController::class, 'deleteItem'])->name('staff-office.pengajuan-barang.delete-item');
    Route::put('/staff-office/pengajuan-barang/{id}/update', [PengajuanController::class, 'update'])->name('staff-office.pengajuan-barang.update');
    Route::put('/staff-office/pengajuan-barang/{id}',[PengajuanController::class, 'update'])->name('staff-office.pengajuan-barang.update');
    Route::post('/staff-office/pengajuan-barang/{id}/upload-nota', [PengajuanController::class, 'uploadNota'])->name('staff-office.pengajuan-barang.upload-nota');
//------------------------------------------------------------------------------------------------------------------------------------
// Group route untuk gudang
Route::group(['middleware' => ['auth', 'App\Http\Middleware\RoleMiddleware:gudang']], function () {
    // Route::get('/gudang/dashboard', [PengajuanCutiController::class, 'riwayatDashboard'])->name('gudang.dashboard');
    Route::get('/gudang/dashboard', [DashboardControllerGD::class, 'index'])->name('gudang.dashboard');
    Route::get('/gudang/download-surat/{id}', [DashboardControllerGD::class, 'downloadSurat'])->name('gudang.download-surat'); // Perbarui nama rute
    Route::get('/gudang/notifikasi-pengajuan', [NotifikasiPengajuanControllerGD::class, 'index'])->name('gudang.notifikasi-pengajuan.index');
    Route::get('/gudang/pengajuan-cuti', [PengajuanCutiControllerGD::class, 'index'])->name('gudang.pengajuan-cuti.index');
    Route::get('/gudang/pengajuan-cuti/create', [PengajuanCutiControllerGD::class, 'create'])->name('gudang.pengajuan-cuti.create');
    Route::post('/gudang/pengajuan-cuti', [PengajuanCutiControllerGD::class, 'store'])->name('gudang.pengajuan-cuti.store');
    Route::post('/gudang/pengajuan-cuti/store', [PengajuanCutiControllerGD::class, 'store'])->name('gudang.pengajuan-cuti.store');
    Route::get('/gudang/pengajuan-cuti/{id}/edit', [PengajuanCutiControllerGD::class, 'edit'])->name('gudang.pengajuan-cuti.edit');
    Route::put('/gudang/pengajuan-cuti/{id}', [PengajuanCutiControllerGD::class, 'update'])->name('gudang.pengajuan-cuti.update');
    Route::delete('/gudang/pengajuan-cuti/{id}', [PengajuanCutiControllerGD::class, 'destroy'])->name('gudang.pengajuan-cuti.destroy');
    Route::get('/gudang/pengajuan-cuti/{id}/view', [PengajuanCutiControllerGD::class, 'view'])->name('gudang.pengajuan-cuti.view');
    Route::get('/gudang/pengajuan-cuti/{id}/view', [PengajuanCutiControllerGD::class, 'view'])->name('gudang.pengajuan-cuti.view');
    Route::post('/gudang/pengajuan-cuti/reset', [PengajuanCutiControllerGD::class, 'reset'])->name('gudang.pengajuan-cuti.reset');
    Route::put('/gudang/pengajuan-cuti/resubmit/{id}', [PengajuanCutiControllerGD::class, 'resubmit'])->name('gudang.pengajuan-cuti.resubmit');

    Route::get('/gudang/pengajuan-kasbon', [PengajuanKasbonControllerGD::class, 'index'])->name('gudang.pengajuan-kasbon.index');
    Route::get('/gudang/kasbon/create', [PengajuanKasbonControllerGD::class, 'create'])->name('gudang.pengajuan-kasbon.create');
    Route::post('/gudang/kasbon', [PengajuanKasbonControllerGD::class, 'store'])->name('gudang.pengajuan-kasbon.store');
    Route::get('/gudang/pengajuan-kasbon/{id}/edit', [PengajuanKasbonControllerGD::class, 'edit'])->name('gudang.pengajuan-kasbon.edit');
    Route::put('/gudang/pengajuan-kasbon/{id}', [PengajuanKasbonControllerGD::class, 'update'])->name('gudang.pengajuan-kasbon.update');
    Route::delete('/gudang/pengajuan-kasbon/{id}', [PengajuanKasbonControllerGD::class, 'destroy'])->name('gudang.pengajuan-kasbon.destroy');
    Route::get('/gudang/pengajuan-kasbon/view/{id}', [PengajuanKasbonControllerGD::class, 'download'])->name('gudang.pengajuan-kasbon.download');
    });
// Group route gudang pengajuan barang
Route::get('/gudang/pengajuan', [PengajuanControllerGD::class, 'index'])->name('gudang.pengajuan-barang.index');
Route::get('/gudang/pengajuan/create', [PengajuanControllerGD::class, 'create'])->name('gudang.pengajuan-barang.create');
Route::post('/gudang/pengajuan/store', [PengajuanControllerGD::class, 'store'])->name('gudang.pengajuan-barang.store');
Route::get('/gudang/pengajuan-barang/{id}/edit', [PengajuanControllerGD::class, 'edit'])->name('gudang.pengajuan-barang.edit');
Route::get('/gudang/pengajuan-barang/{id}/update', [PengajuanControllerGD::class, 'update'])->name('gudang.pengajuan-barang.update');
Route::delete('/gudang/pengajuan-barang/{id}', [PengajuanControllerGD::class, 'destroy'])->name('gudang.pengajuan-barang.destroy');
Route::delete('/gudang/pengajuan-barang/{id}/delete-item/{item_id}', [PengajuanControllerGD::class, 'deleteItem'])->name('gudang.pengajuan-barang.delete-item');
Route::put('/gudang/pengajuan-barang/{id}/update', [PengajuanControllerGD::class, 'update'])->name('gudang.pengajuan-barang.update');
Route::put('/gudang/pengajuan-barang/{id}',[PengajuanControllerGD::class, 'update'])->name('gudang.pengajuan-barang.update');
//------------------------------------------------------------------------------------------------------------------------------------
// Group Route Manager Keuangan Approve
Route::middleware(['auth'])->group(function () {
});
Route::prefix('manager-keuangan')->name('manager-keuangan.')->group(function () {
    // Rute untuk menampilkan halaman approval barang
    Route::get('approve/barang', [BarangApprovalControllerMK::class,'index'])->name('approve.barang.index');
    Route::get('approve/barang/{id}', [BarangApprovalControllerMK::class, 'detail'])->name('approve.barang.detail');
    
    // Rute untuk GajiController
});
//------------------------------------------------------------------------------------------------------------------------------------
// Group Route Area-Manager Approve
Route::middleware(['auth'])->group(function () {
    Route::get('/area-manager/dashboard', [DashboardControllerAM::class, 'index'])->name('area-manager.dashboard');
    // Group Route Pengajuan Cuti
    Route::get('/area-manager/pengajuan-cuti', [PengajuanCutiControllerAM::class, 'index'])->name('area-manager.pengajuan-cuti.index');
    Route::get('/area-manager/pengajuan-cuti/create', [PengajuanCutiControllerAM::class, 'create'])->name('area-manager.pengajuan-cuti.create');
    Route::post('/area-manager/pengajuan-cuti', [PengajuanCutiControllerAM::class, 'store'])->name('area-manager.pengajuan-cuti.store');
    Route::post('/area-manager/pengajuan-cuti/store', [PengajuanCutiControllerAM::class, 'store'])->name('area-manager.pengajuan-cuti.store');
    Route::get('/area-manager/pengajuan-cuti/{id}/edit', [PengajuanCutiControllerAM::class, 'edit'])->name('area-manager.pengajuan-cuti.edit');
    Route::put('/area-manager/pengajuan-cuti/{id}', [PengajuanCutiControllerAM::class, 'update'])->name('area-manager.pengajuan-cuti.update');
    Route::delete('/area-manager/pengajuan-cuti/{id}', [PengajuanCutiControllerAM::class, 'destroy'])->name('area-manager.pengajuan-cuti.destroy');
    Route::get('/area-manager/pengajuan-cuti/{id}/view',  [PengajuanCutiControllerAM::class, 'view'])->name('area-manager.pengajuan-cuti.view');
    Route::get('/area-manager/pengajuan-cuti/{id}/view', [PengajuanCutiControllerAM::class, 'view'])->name('area-manager.pengajuan-cuti.view');
    Route::post('/area-manager/pengajuan-cuti/reset', [PengajuanCutiControllerAM::class, 'reset'])->name('area-manager.pengajuan-cuti.reset');
    // Group Route Pengajuan Barang
    Route::get('/area-manager/pengajuan', [PengajuanControllerAM::class, 'index'])->name('area-manager.pengajuan-barang.index');
    Route::get('/area-manager/pengajuan/create', [PengajuanControllerAM::class, 'create'])->name('area-manager.pengajuan-barang.create');
    Route::post('/area-manager/pengajuan/store', [PengajuanControllerAM::class, 'store'])->name('area-manager.pengajuan-barang.store');
    Route::get('pengajuan-barang/{id}/edit', [PengajuanControllerAM::class, 'edit'])->name('area-manager.pengajuan-barang.edit');
    Route::get('pengajuan-barang/{id}/update', [PengajuanControllerAM::class, 'update'])->name('area-manager.pengajuan-barang.update');
    Route::delete('pengajuan-barang/{id}', [PengajuanControllerAM::class, 'destroy'])->name('area-manager.pengajuan-barang.destroy');
    Route::delete('/pengajuan-barang/{id}/delete-item/{item_id}', [PengajuanControllerAM::class, 'deleteItem'])->name('area-manager.pengajuan-barang.delete-item');
    Route::put('/pengajuan-barang/{id}/update', [PengajuanControllerAM::class, 'update'])->name('area-manager.pengajuan-barang.update');
    Route::put('/pengajuan-barang/{id}',[PengajuanControllerAM::class, 'update'])->name('area-manager.pengajuan-barang.update');
    // Group Route Pengajuan Kasbon
    Route::get('/area-manager/pengajuan-kasbon', [PengajuanKasbonControllerAM::class, 'index'])->name('area-manager.pengajuan-kasbon.index');
    Route::get('/area-manager/kasbon/create', [PengajuanKasbonControllerAM::class, 'create'])->name('area-manager.pengajuan-kasbon.create');
    Route::post('/area-manager/kasbon', [PengajuanKasbonControllerAM::class, 'store'])->name('area-manager.pengajuan-kasbon.store');
    Route::get('/area-manager/pengajuan-kasbon/{id}/edit', [PengajuanKasbonControllerAM::class, 'edit'])->name('area-manager.pengajuan-kasbon.edit');
    Route::put('/area-manager/pengajuan-kasbon/{id}', [PengajuanKasbonControllerAM::class, 'update'])->name('area-manager.pengajuan-kasbon.update');
    Route::delete('/area-manager/pengajuan-kasbon/{id}', [PengajuanKasbonControllerAM::class, 'destroy'])->name('area-manager.pengajuan-kasbon.destroy');
    Route::get('/area-manager/pengajuan-kasbon/view/{id}', [PengajuanKasbonControllerAM::class, 'download'])->name('area-manager.pengajuan-kasbon.download');
    // Group Route Approve Cuti
    Route::get('/area-manager/approve/cuti', [CutiApprovalController::class, 'index'])->name('area-manager.approve.cuti');
    Route::post('/area-manager/approve/cuti/{id}/approve', [CutiApprovalController::class, 'approve'])->name('area-manager.approve.cuti.approve');
    Route::post('/approved/{id}/{action}', [CutiApprovalController::class, 'approve'])->name('approved');
    Route::post('/rejected/{id}', [CutiApprovalController::class, 'reject'])->name('rejected');

    Route::get('/area-manager/izin', [IzinApprovalControllerAM::class, 'index'])->name('area-manager.izin.index');
    Route::post('/area-manager/izin/{id}/disetujui-terima', [IzinApprovalControllerAM::class, 'disetujuiTerima'])->name('area-manager.izin.disetujui.terima');
    Route::post('/area-manager/izin/{id}/disetujui-tolak', [IzinApprovalControllerAM::class, 'disetujuiTolak'])->name('area-manager.izin.disetujui.tolak');
    Route::post('/area-manager/izin/{id}/diketahui-terima', [IzinApprovalControllerAM::class, 'diketahuiTerima'])->name('area-manager.izin.diketahui.terima');
    Route::post('/area-manager/izin/{id}/diketahui-tolak', [IzinApprovalControllerAM::class, 'diketahuiTolak'])->name('area-manager.izin.diketahui.tolak');

    Route::get('/area-manager/approve-sakit', [SakitApprovalControllerAM::class, 'index'])->name('area-manager.sakit.index');
    Route::post('/area-manager/approve-sakit/{id}/disetujui-terima', [SakitApprovalControllerAM::class, 'disetujuiTerima'])->name('area-manager.approve.sakit.disetujui-terima');
    Route::post('/area-manager/approve-sakit/{id}/disetujui-tolak', [SakitApprovalControllerAM::class, 'disetujuiTolak'])->name('area-manager.approve.sakit.disetujui-tolak');
    Route::post('/area-manager/approve-sakit/{id}/diketahui-terima', [SakitApprovalControllerAM::class, 'diketahuiTerima'])->name('area-manager.approve.sakit.diketahui-terima');
    Route::post('/area-manager/approve-sakit/{id}/diketahui-tolak', [SakitApprovalControllerAM::class, 'diketahuiTolak'])->name('area-manager.approve.sakit.diketahui-tolak');

    Route::get('/area-manager/approve/kasbon', [KasbonApprovalController::class, 'index'])->name('area-manager.approve.kasbon');
    // Route untuk menyetujui kasbon
    Route::put('/area-manager/kasbon/disetujui-terima/{id}', [KasbonApprovalController::class, 'disetujuiTerima'])->name('area-manager.kasbon.disetujuiTerima');
    Route::put('/area-manager/kasbon/disetujui-tolak/{id}', [KasbonApprovalController::class, 'disetujuiTolak'])->name('area-manager.kasbon.disetujuiTolak');
    // Route untuk mengetahui kasbon
    Route::put('/area-manager/kasbon/diketahui-terima/{id}', [KasbonApprovalController::class, 'diketahuiTerima'])->name('area-manager.kasbon.diketahuiTerima');
    Route::put('/area-manager/kasbon/diketahui-tolak/{id}', [KasbonApprovalController::class, 'diketahuiTolak'])->name('area-manager.kasbon.diketahuiTolak');

    Route::get('/manager-keuangan/dashboard', [DashboardControllerMK::class, 'index'])->name('manager-keuangan.dashboard');
    // Group Route Pengajuan Cuti
    Route::get('/manager-keuangan/pengajuan-cuti', [PengajuanCutiControllerMK::class, 'index'])->name('manager-keuangan.pengajuan-cuti.index');
    Route::get('/manager-keuangan/pengajuan-cuti/create', [PengajuanCutiControllerMK::class, 'create'])->name('manager-keuangan.pengajuan-cuti.create');
    Route::post('/manager-keuangan/pengajuan-cuti', [PengajuanCutiControllerMK::class, 'store'])->name('manager-keuangan.pengajuan-cuti.store');
    Route::post('/manager-keuangan/pengajuan-cuti/store', [PengajuanCutiControllerMK::class, 'store'])->name('manager-keuangan.pengajuan-cuti.store');
    Route::get('/manager-keuangan/pengajuan-cuti/{id}/edit', [PengajuanCutiControllerMK::class, 'edit'])->name('manager-keuangan.pengajuan-cuti.edit');
    Route::put('/manager-keuangan/pengajuan-cuti/{id}', [PengajuanCutiControllerMK::class, 'update'])->name('manager-keuangan.pengajuan-cuti.update');
    Route::delete('/manager-keuangan/pengajuan-cuti/{id}', [PengajuanCutiControllerMK::class, 'destroy'])->name('manager-keuangan.pengajuan-cuti.destroy');
    Route::get('/manager-keuangan/pengajuan-cuti/{id}/view', 'App\Http\Controllers\StaffOffice\PengajuanCutiControllerMK@view')->name('manager-keuangan.pengajuan-cuti.view');
    Route::get('/manager-keuangan/pengajuan-cuti/{id}/view', [PengajuanCutiControllerMK::class, 'view'])->name('manager-keuangan.pengajuan-cuti.view');
    Route::post('/manager-keuangan/pengajuan-cuti/reset', [PengajuanCutiControllerMK::class, 'reset'])->name('manager-keuangan.pengajuan-cuti.reset');
    // Group Route Pengajuan Barang
    Route::get('/manager-keuangan/pengajuan', [PengajuanControllerMK::class, 'index'])->name('manager-keuangan.pengajuan-barang.index');
    Route::get('/manager-keuangan/pengajuan/create', [PengajuanControllerMK::class, 'create'])->name('manager-keuangan.pengajuan-barang.create');
    Route::post('/manager-keuangan/pengajuan/store', [PengajuanControllerMK::class, 'store'])->name('manager-keuangan.pengajuan-barang.store');
    Route::get('pengajuan-barang/{id}/edit', [PengajuanControllerMK::class, 'edit'])->name('manager-keuangan.pengajuan-barang.edit');
    Route::get('pengajuan-barang/{id}/update', [PengajuanControllerMK::class, 'update'])->name('manager-keuangan.pengajuan-barang.update');
    Route::delete('pengajuan-barang/{id}', [PengajuanControllerMK::class, 'destroy'])->name('manager-keuangan.pengajuan-barang.destroy');
    Route::delete('/pengajuan-barang/{id}/delete-item/{item_id}', [PengajuanControllerMK::class, 'deleteItem'])->name('manager-keuangan.pengajuan-barang.delete-item');
    Route::put('/pengajuan-barang/{id}/update', [PengajuanControllerMK::class, 'update'])->name('manager-keuangan.pengajuan-barang.update');
    Route::put('/pengajuan-barang/{id}',[PengajuanControllerMK::class, 'update'])->name('manager-keuangan.pengajuan-barang.update');
    // Group Route Approve Cuti
    Route::get('/manager-keuangan/approve/cuti', [CutiApprovalControllerMK::class, 'index'])->name('manager-keuangan.approve.cuti');
    Route::post('/manager-keuangan/approve/cuti/{id}/approve', [CutiApprovalControllerMK::class, 'approve'])->name('manager-keuangan.approve.cuti.approve');
    Route::post('/approved/{id}/{action}', [CutiApprovalControllerMK::class, 'approve'])->name('approved');
    Route::post('/rejected/{id}', [CutiApprovalControllerMK::class, 'reject'])->name('rejected');

    Route::get('/manager-keuangan/data-gaji', [GajiController::class, 'index'])->name('manager-keuangan.data-gaji.index');
    Route::put('/manager-keuangan/gaji/{user}', [GajiController::class, 'store'])->name('manager-keuangan.gaji.store');
    Route::get('data-kasbon', [DataKasbonController::class, 'index'])->name('manager-keuangan.data-kasbon.index');
    Route::get('data-kasbon/update-status-cicilan/{id}', [DataKasbonController::class, 'updateStatusCicilan'])->name('manager-keuangan.data-kasbon.updateStatusCicilan');
});
Route::prefix('area-manager')->name('area-manager')->group(function () {
    Route::get('approve/barang', [BarangApprovalController::class,'index'])->name('approve.barang.index');
    Route::get('approve/barang/{id}', [BarangApprovalController::class, 'detail'])->name('approve.barang.detail');
});
//------------------------------------------------------------------------------------------------------------------------------------
// Group Route Manager Operasional Approve
Route::middleware(['auth'])->group(function () {
    Route::get('/manager-operasional/dashboard', [DashboardControllerMO::class, 'index'])->name('manager-operasional.dashboard');

    // Group Route Pengajuan Cuti
    Route::get('/manager-operasional/pengajuan-cuti', [PengajuanCutiControllerMO::class, 'index'])->name('manager-operasional.pengajuan-cuti.index');
    Route::get('/manager-operasional/pengajuan-cuti/create', [PengajuanCutiControllerMO::class, 'create'])->name('manager-operasional.pengajuan-cuti.create');
    Route::post('/manager-operasional/pengajuan-cuti', [PengajuanCutiControllerMO::class, 'store'])->name('manager-operasional.pengajuan-cuti.store');
    Route::post('/manager-operasional/pengajuan-cuti/store', [PengajuanCutiControllerMO::class, 'store'])->name('manager-operasional.pengajuan-cuti.store');
    Route::get('/manager-operasional/pengajuan-cuti/{id}/edit', [PengajuanCutiControllerMO::class, 'edit'])->name('manager-operasional.pengajuan-cuti.edit');
    Route::put('/manager-operasional/pengajuan-cuti/{id}', [PengajuanCutiControllerMO::class, 'update'])->name('manager-operasional.pengajuan-cuti.update');
    Route::delete('/manager-operasional/pengajuan-cuti/{id}', [PengajuanCutiControllerMO::class, 'destroy'])->name('manager-operasional.pengajuan-cuti.destroy');
    Route::get('/manager-operasional/pengajuan-cuti/{id}/view', 'App\Http\Controllers\StaffOffice\PengajuanCutiControllerMO@view')->name('manager-operasional.pengajuan-cuti.view');
    Route::get('/manager-operasional/pengajuan-cuti/{id}/view', [PengajuanCutiControllerMO::class, 'view'])->name('manager-operasional.pengajuan-cuti.view');
    Route::post('/manager-operasional/pengajuan-cuti/reset', [PengajuanCutiControllerMO::class, 'reset'])->name('manager-operasional.pengajuan-cuti.reset');
    // Group Route Pengajuan Barang
    Route::get('/manager-operasional/pengajuan', [PengajuanControllerMO::class, 'index'])->name('manager-operasional.pengajuan-barang.index');
    Route::get('/manager-operasional/pengajuan/create', [PengajuanControllerMO::class, 'create'])->name('manager-operasional.pengajuan-barang.create');
    Route::post('/manager-operasional/pengajuan/store', [PengajuanControllerMO::class, 'store'])->name('manager-operasional.pengajuan-barang.store');
    Route::get('manager-operasional/pengajuan-barang/{id}/edit', [PengajuanControllerMO::class, 'edit'])->name('manager-operasional.pengajuan-barang.edit');
    Route::get('manager-operasional/pengajuan-barang/{id}/update', [PengajuanControllerMO::class, 'update'])->name('manager-operasional.pengajuan-barang.update');
    Route::delete('manager-operasional/pengajuan-barang/{id}', [PengajuanControllerMO::class, 'destroy'])->name('manager-operasional.pengajuan-barang.destroy');
    Route::delete('manager-operasional/pengajuan-barang/{id}/delete-item/{item_id}', [PengajuanControllerMO::class, 'deleteItem'])->name('manager-operasional.pengajuan-barang.delete-item');
    Route::put('manager-operasional/pengajuan-barang/{id}/update', [PengajuanControllerMO::class, 'update'])->name('manager-operasional.pengajuan-barang.update');
    Route::put('manager-operasional/pengajuan-barang/{id}',[PengajuanControllerMO::class, 'update'])->name('manager-operasional.pengajuan-barang.update');
    // Group Route Pengajuan Kasbon
    Route::get('/manager-operasional/pengajuan-kasbon', [PengajuanKasbonControllerMO::class, 'index'])->name('manager-operasional.pengajuan-kasbon.index');
    Route::get('/manager-operasional/kasbon/create', [PengajuanKasbonControllerMO::class, 'create'])->name('manager-operasional.pengajuan-kasbon.create');
    Route::post('/manager-operasional/kasbon', [PengajuanKasbonControllerMO::class, 'store'])->name('manager-operasional.pengajuan-kasbon.store');
    Route::get('/manager-operasional/pengajuan-kasbon/{id}/edit', [PengajuanKasbonControllerMO::class, 'edit'])->name('manager-operasional.pengajuan-kasbon.edit');
    Route::put('/manager-operasional/pengajuan-kasbon/{id}', [PengajuanKasbonControllerMO::class, 'update'])->name('manager-operasional.pengajuan-kasbon.update');
    Route::delete('/manager-operasional/pengajuan-kasbon/{id}', [PengajuanKasbonControllerMO::class, 'destroy'])->name('manager-operasional.pengajuan-kasbon.destroy');
    Route::get('/manager-operasional/pengajuan-kasbon/view/{id}', [PengajuanKasbonControllerMO::class, 'download'])->name('manager-operasional.pengajuan-kasbon.download');
    // Group Route Approve Pengajuan Kasbon
    Route::get('/manager-operasional/approve/kasbon', [KasbonApprovalControllerMO::class, 'index'])->name('manager-operasional.approve.kasbon');
            // Route untuk menyetujui kasbon
    Route::put('/manager-operasional/kasbon/disetujui-terima/{id}', [KasbonApprovalControllerMO::class, 'disetujuiTerima'])->name('manager-operasional.kasbon.disetujuiTerima');
    Route::put('/manager-operasional/kasbon/disetujui-tolak/{id}', [KasbonApprovalControllerMO::class, 'disetujuiTolak'])->name('manager-operasional.kasbon.disetujuiTolak');
    Route::put('/manager-operasional/kasbon/diketahui-terima/{id}', [KasbonApprovalControllerMO::class, 'diketahuiTerima'])->name('manager-operasional.kasbon.diketahuiTerima');
    Route::put('/manager-operasional/kasbon/diketahui-tolak/{id}', [KasbonApprovalControllerMO::class, 'diketahuiTolak'])->name('manager-operasional.kasbon.diketahuiTolak');
    // Group Route Approve Pengajuan Cuti
    Route::get('/manager-operasional/approve/cuti', [CutiApprovalControllerMo::class, 'index'])->name('manager-operasional.approve.cuti');
    Route::post('/manager-operasional/approve/cuti/{id}/approve', [CutiApprovalControllerMo::class, 'approve'])->name('manager-operasional.approve.cuti.approve');
    Route::post('/approved/{id}/{action}', [CutiApprovalControllerMo::class, 'approve'])->name('approved');
    Route::post('/rejected/{id}', [CutiApprovalControllerMo::class, 'reject'])->name('rejected');

    Route::get('izin', [IzinApprovalController::class, 'index'])->name('manager-operasional.izin.index');
    Route::post('izin/{id}/disetujui-terima', [IzinApprovalController::class, 'disetujuiTerima'])->name('manager-operasional.approve.izin.disetujui.terima');
    Route::post('izin/{id}/disetujui-tolak', [IzinApprovalController::class, 'disetujuiTolak'])->name('manager-operasional.approve.izin.disetujui.tolak');
    Route::post('izin/{id}/diketahui-terima', [IzinApprovalController::class, 'diketahuiTerima'])->name('manager-operasional.approve.izin.diketahui.terima');
    Route::post('izin/{id}/diketahui-tolak', [IzinApprovalController::class, 'diketahuiTolak'])->name('manager-operasional.approve.izin.diketahui.tolak');

    Route::get('/approve-sakit', [SakitApprovalController::class, 'index'])->name('manager-operasional.sakit.index');
    Route::post('/approve-sakit/{id}/disetujui-terima', [SakitApprovalController::class, 'disetujuiTerima'])->name('manager-operasional.approve.sakit.disetujui-terima');
    Route::post('/approve-sakit/{id}/disetujui-tolak', [SakitApprovalController::class, 'disetujuiTolak'])->name('manager-operasional.approve.sakit.disetujui-tolak');
    Route::post('/approve-sakit/{id}/diketahui-terima', [SakitApprovalController::class, 'diketahuiTerima'])->name('manager-operasional.approve.sakit.diketahui-terima');
    Route::post('/approve-sakit/{id}/diketahui-tolak', [SakitApprovalController::class, 'diketahuiTolak'])->name('manager-operasional.approve.sakit.diketahui-tolak');

    // Group Route Direktur Approve
    Route::get('/direktur/dashboard', [DashboardControllerDR::class, 'index'])->name('direktur.dashboard');

    // Group Route Pengajuan Cuti
    Route::get('/direktur/pengajuan-cuti', [PengajuanCutiControllerDR::class, 'index'])->name('direktur.pengajuan-cuti.index');
    Route::get('/direktur/pengajuan-cuti/create', [PengajuanCutiControllerDR::class, 'create'])->name('direktur.pengajuan-cuti.create');
    Route::post('/direktur/pengajuan-cuti', [PengajuanCutiControllerDR::class, 'store'])->name('direktur.pengajuan-cuti.store');
    Route::post('/direktur/pengajuan-cuti/store', [PengajuanCutiControllerDR::class, 'store'])->name('direktur.pengajuan-cuti.store');
    Route::get('/direktur/pengajuan-cuti/{id}/edit', [PengajuanCutiControllerDR::class, 'edit'])->name('direktur.pengajuan-cuti.edit');
    Route::put('/direktur/pengajuan-cuti/{id}', [PengajuanCutiControllerDR::class, 'update'])->name('direktur.pengajuan-cuti.update');
    Route::delete('/direktur/pengajuan-cuti/{id}', [PengajuanCutiControllerDR::class, 'destroy'])->name('direktur.pengajuan-cuti.destroy');
    Route::get('/pengajuan-cuti/{id}/view', 'App\Http\Controllers\StaffOffice\PengajuanCutiControllerDR@view')->name('direktur.pengajuan-cuti.view');
    Route::get('/pengajuan-cuti/{id}/view', [PengajuanCutiControllerDR::class, 'view'])->name('direktur.pengajuan-cuti.view');
    Route::post('/direktur/pengajuan-cuti/reset', [PengajuanCutiControllerDR::class, 'reset'])->name('direktur.pengajuan-cuti.reset');
    // Group Route Pengajuan Barang
    Route::get('/direktur/pengajuan', [PengajuanControllerDR::class, 'index'])->name('direktur.pengajuan-barang.index');
    Route::get('/direktur/pengajuan/create', [PengajuanControllerDR::class, 'create'])->name('direktur.pengajuan-barang.create');
    Route::post('/direktur/pengajuan/store', [PengajuanControllerDR::class, 'store'])->name('direktur.pengajuan-barang.store');
    Route::get('/direktur/pengajuan-barang/{id}/edit', [PengajuanControllerDR::class, 'edit'])->name('direktur.pengajuan-barang.edit');
    Route::get('/direktur/pengajuan-barang/{id}/update', [PengajuanControllerDR::class, 'update'])->name('direktur.pengajuan-barang.update');
    Route::delete('/direktur/pengajuan-barang/{id}', [PengajuanControllerDR::class, 'destroy'])->name('direktur.pengajuan-barang.destroy');
    Route::delete('/direktur/pengajuan-barang/{id}/delete-item/{item_id}', [PengajuanControllerDR::class, 'deleteItem'])->name('direktur.pengajuan-barang.delete-item');
    Route::put('/direktur/pengajuan-barang/{id}/update', [PengajuanControllerDR::class, 'update'])->name('direktur.pengajuan-barang.update');
    Route::put('/direktur/pengajuan-barang/{id}',[PengajuanControllerDR::class, 'update'])->name('direktur.pengajuan-barang.update');
    // Group Route Pengajuan Kasbon
    Route::get('/direktur/pengajuan-kasbon', [PengajuanKasbonControllerDR::class, 'index'])->name('direktur.pengajuan-kasbon.index');
    Route::get('/direktur/kasbon/create', [PengajuanKasbonControllerDR::class, 'create'])->name('direktur.pengajuan-kasbon.create');
    Route::post('/direktur/kasbon', [PengajuanKasbonControllerDR::class, 'store'])->name('direktur.pengajuan-kasbon.store');
    Route::get('/direktur/pengajuan-kasbon/{id}/edit', [PengajuanKasbonControllerDR::class, 'edit'])->name('direktur.pengajuan-kasbon.edit');
    Route::put('/direktur/pengajuan-kasbon/{id}', [PengajuanKasbonControllerDR::class, 'update'])->name('direktur.pengajuan-kasbon.update');
    Route::delete('/direktur/pengajuan-kasbon/{id}', [PengajuanKasbonControllerDR::class, 'destroy'])->name('direktur.pengajuan-kasbon.destroy');
    Route::get('/direktur/pengajuan-kasbon/view/{id}', [PengajuanKasbonControllerDR::class, 'download'])->name('direktur.pengajuan-kasbon.download');
    // Group Route Approve Pengajuan Kasbon
    Route::get('/direktur/approve/kasbon', [KasbonApprovalControllerDR::class, 'index'])->name('direktur.approve.kasbon');
            // Route untuk menyetujui kasbon
    Route::put('/kasbon/disetujui-terima/{id}', [KasbonApprovalControllerDR::class, 'disetujuiTerima'])->name('direktur.kasbon.disetujuiTerima');
    Route::put('/kasbon/disetujui-tolak/{id}', [KasbonApprovalControllerDR::class, 'disetujuiTolak'])->name('direktur.kasbon.disetujuiTolak');
            // Route untuk mengetahui kasbon
    Route::put('/kasbon/diketahui-terima/{id}', [KasbonApprovalControllerDR::class, 'diketahuiTerima'])->name('direktur.kasbon.diketahuiTerima');
    Route::put('/kasbon/diketahui-tolak/{id}', [KasbonApprovalControllerDR::class, 'diketahuiTolak'])->name('direktur.kasbon.diketahuiTolak');
    // Group Route Approve Pengajuan Cuti
    Route::get('/direktur/approve/cuti', [CutiApprovalControllerDR::class, 'index'])->name('direktur.approve.cuti');
    Route::post('/direktur/approve/cuti/{id}/approve', [CutiApprovalControllerDR::class, 'approve'])->name('direktur.approve.cuti.approve');
    Route::post('/approved/{id}/{action}', [CutiApprovalControllerDR::class, 'approve'])->name('approved');
    Route::post('/rejected/{id}', [CutiApprovalControllerDR::class, 'reject'])->name('rejected');
    });
Route::prefix('manager-operasional')->name('manager-operasional.')->group(function () {
    Route::get('approve/barang', [BarangApprovalControllerMo::class,'index'])->name('approve.barang.index');
    Route::get('approve/barang/{id}', [BarangApprovalControllerMo::class, 'detail'])->name('approve.barang.detail');
    });
//------------------------------------------------------------------------------------------------------------------------------------

//------------------------------------------------------------------------------------------------------------------------------------
// Group Route Kepala Gudang Approve
Route::middleware(['auth'])->group(function () {
    Route::get('/kepala-gudang/dashboard', [DashboardControllerKG::class, 'index'])->name('kepala-gudang.dashboard');

    // Group Route Pengajuan Cuti
    Route::get('/kepala-gudang/pengajuan-cuti', [PengajuanCutiControllerKG::class, 'index'])->name('kepala-gudang.pengajuan-cuti.index');
    Route::get('/kepala-gudang/pengajuan-cuti/create', [PengajuanCutiControllerKG::class, 'create'])->name('kepala-gudang.pengajuan-cuti.create');
    Route::post('/kepala-gudang/pengajuan-cuti', [PengajuanCutiControllerKG::class, 'store'])->name('kepala-gudang.pengajuan-cuti.store');
    Route::post('/kepala-gudang/pengajuan-cuti/store', [PengajuanCutiControllerKG::class, 'store'])->name('kepala-gudang.pengajuan-cuti.store');
    Route::get('/kepala-gudang/pengajuan-cuti/{id}/edit', [PengajuanCutiControllerKG::class, 'edit'])->name('kepala-gudang.pengajuan-cuti.edit');
    Route::put('/kepala-gudang/pengajuan-cuti/{id}', [PengajuanCutiControllerKG::class, 'update'])->name('kepala-gudang.pengajuan-cuti.update');
    Route::delete('/kepala-gudang/pengajuan-cuti/{id}', [PengajuanCutiControllerKG::class, 'destroy'])->name('kepala-gudang.pengajuan-cuti.destroy');
    Route::get('/kepala-gudang/pengajuan-cuti/{id}/view', 'App\Http\Controllers\StaffOffice\PengajuanCutiControllerKG@view')->name('kepala-gudang.pengajuan-cuti.view');
    Route::get('/kepala-gudang/pengajuan-cuti/{id}/view', [PengajuanCutiControllerKG::class, 'view'])->name('kepala-gudang.pengajuan-cuti.view');
    Route::post('/kepala-gudang/pengajuan-cuti/reset', [PengajuanCutiControllerKG::class, 'reset'])->name('kepala-gudang.pengajuan-cuti.reset');
    // Group Route Pengajuan Barang
    Route::get('/kepala-gudang/pengajuan', [PengajuanControllerKG::class, 'index'])->name('kepala-gudang.pengajuan-barang.index');
    Route::get('/kepala-gudang/pengajuan/create', [PengajuanControllerKG::class, 'create'])->name('kepala-gudang.pengajuan-barang.create');
    Route::post('/kepala-gudang/pengajuan/store', [PengajuanControllerKG::class, 'store'])->name('kepala-gudang.pengajuan-barang.store');
    Route::get('kepala-gudang/pengajuan-barang/{id}/edit', [PengajuanControllerKG::class, 'edit'])->name('kepala-gudang.pengajuan-barang.edit');
    Route::get('kepala-gudang/pengajuan-barang/{id}/update', [PengajuanControllerKG::class, 'update'])->name('kepala-gudang.pengajuan-barang.update');
    Route::delete('kepala-gudang/pengajuan-barang/{id}', [PengajuanControllerKG::class, 'destroy'])->name('kepala-gudang.pengajuan-barang.destroy');
    Route::delete('kepala-gudang/pengajuan-barang/{id}/delete-item/{item_id}', [PengajuanControllerKG::class, 'deleteItem'])->name('kepala-gudang.pengajuan-barang.delete-item');
    Route::put('kepala-gudang/pengajuan-barang/{id}/update', [PengajuanControllerKG::class, 'update'])->name('kepala-gudang.pengajuan-barang.update');
    Route::put('kepala-gudang/pengajuan-barang/{id}',[PengajuanControllerKG::class, 'update'])->name('kepala-gudang.pengajuan-barang.update');
    // Group Route Pengajuan Kasbon
    Route::get('/kepala-gudang/pengajuan-kasbon', [PengajuanKasbonControllerKG::class, 'index'])->name('kepala-gudang.pengajuan-kasbon.index');
    Route::get('/kepala-gudang/kasbon/create', [PengajuanKasbonControllerKG::class, 'create'])->name('kepala-gudang.pengajuan-kasbon.create');
    Route::post('/kepala-gudang/kasbon', [PengajuanKasbonControllerKG::class, 'store'])->name('kepala-gudang.pengajuan-kasbon.store');
    Route::get('/kepala-gudang/pengajuan-kasbon/{id}/edit', [PengajuanKasbonControllerKG::class, 'edit'])->name('kepala-gudang.pengajuan-kasbon.edit');
    Route::put('/kepala-gudang/pengajuan-kasbon/{id}', [PengajuanKasbonControllerKG::class, 'update'])->name('kepala-gudang.pengajuan-kasbon.update');
    Route::delete('/kepala-gudang/pengajuan-kasbon/{id}', [PengajuanKasbonControllerKG::class, 'destroy'])->name('kepala-gudang.pengajuan-kasbon.destroy');
    Route::get('/kepala-gudang/pengajuan-kasbon/view/{id}', [PengajuanKasbonControllerKG::class, 'download'])->name('kepala-gudang.pengajuan-kasbon.download');
    // Group Route Approve Pengajuan Kasbon
    Route::get('/kepala-gudang/approve/kasbon', [KasbonApprovalControllerKG::class, 'index'])->name('kepala-gudang.approve.kasbon');
            // Route untuk menyetujui kasbon
    Route::put('/kepala-gudang/kasbon/disetujui-terima/{id}', [KasbonApprovalControllerKG::class, 'disetujuiTerima'])->name('kepala-gudang.kasbon.disetujuiTerima');
    Route::put('/kepala-gudang/kasbon/disetujui-tolak/{id}', [KasbonApprovalControllerKG::class, 'disetujuiTolak'])->name('kepala-gudang.kasbon.disetujuiTolak');
            // Route untuk mengetahui kasbon
    Route::put('/kepala-gudang/kasbon/diketahui-terima/{id}', [KasbonApprovalControllerKG::class, 'diketahuiTerima'])->name('kepala-gudang.kasbon.diketahuiTerima');
    Route::put('/kepala-gudang/kasbon/diketahui-tolak/{id}', [KasbonApprovalControllerKG::class, 'diketahuiTolak'])->name('kepala-gudang.kasbon.diketahuiTolak');
    // Group Route Approve Pengajuan Cuti
    Route::get('/kepala-gudang/approve/cuti', [CutiApprovalControllerKG::class, 'index'])->name('kepala-gudang.approve.cuti');
    Route::post('/kepala-gudang/approve/cuti/{id}/approve', [CutiApprovalControllerKG::class, 'approve'])->name('kepala-gudang.approve.cuti.approve');
    Route::post('/approved/{id}/{action}', [CutiApprovalControllerKG::class, 'approve'])->name('approved');
    Route::post('/rejected/{id}', [CutiApprovalControllerKG::class, 'reject'])->name('rejected');

    // Group Route Direktur Approve
    Route::get('/direktur/dashboard', [DashboardControllerDR::class, 'index'])->name('direktur.dashboard');

    // Group Route Pengajuan Cuti
    Route::get('/direktur/pengajuan-cuti', [PengajuanCutiControllerDR::class, 'index'])->name('direktur.pengajuan-cuti.index');
    Route::get('/direktur/pengajuan-cuti/create', [PengajuanCutiControllerDR::class, 'create'])->name('direktur.pengajuan-cuti.create');
    Route::post('/direktur/pengajuan-cuti', [PengajuanCutiControllerDR::class, 'store'])->name('direktur.pengajuan-cuti.store');
    Route::post('/direktur/pengajuan-cuti/store', [PengajuanCutiControllerDR::class, 'store'])->name('direktur.pengajuan-cuti.store');
    Route::get('/direktur/pengajuan-cuti/{id}/edit', [PengajuanCutiControllerDR::class, 'edit'])->name('direktur.pengajuan-cuti.edit');
    Route::put('/direktur/pengajuan-cuti/{id}', [PengajuanCutiControllerDR::class, 'update'])->name('direktur.pengajuan-cuti.update');
    Route::delete('/direktur/pengajuan-cuti/{id}', [PengajuanCutiControllerDR::class, 'destroy'])->name('direktur.pengajuan-cuti.destroy');
    Route::get('/pengajuan-cuti/{id}/view', 'App\Http\Controllers\StaffOffice\PengajuanCutiControllerDR@view')->name('direktur.pengajuan-cuti.view');
    Route::get('/pengajuan-cuti/{id}/view', [PengajuanCutiControllerDR::class, 'view'])->name('direktur.pengajuan-cuti.view');
    Route::post('/direktur/pengajuan-cuti/reset', [PengajuanCutiControllerDR::class, 'reset'])->name('direktur.pengajuan-cuti.reset');
    // Group Route Pengajuan Barang
    Route::get('/direktur/pengajuan', [PengajuanControllerDR::class, 'index'])->name('direktur.pengajuan-barang.index');
    Route::get('/direktur/pengajuan/create', [PengajuanControllerDR::class, 'create'])->name('direktur.pengajuan-barang.create');
    Route::post('/direktur/pengajuan/store', [PengajuanControllerDR::class, 'store'])->name('direktur.pengajuan-barang.store');
    Route::get('/direktur/pengajuan-barang/{id}/edit', [PengajuanControllerDR::class, 'edit'])->name('direktur.pengajuan-barang.edit');
    Route::get('/direktur/pengajuan-barang/{id}/update', [PengajuanControllerDR::class, 'update'])->name('direktur.pengajuan-barang.update');
    Route::delete('/direktur/pengajuan-barang/{id}', [PengajuanControllerDR::class, 'destroy'])->name('direktur.pengajuan-barang.destroy');
    Route::delete('/direktur/pengajuan-barang/{id}/delete-item/{item_id}', [PengajuanControllerDR::class, 'deleteItem'])->name('direktur.pengajuan-barang.delete-item');
    Route::put('/direktur/pengajuan-barang/{id}/update', [PengajuanControllerDR::class, 'update'])->name('direktur.pengajuan-barang.update');
    Route::put('/direktur/pengajuan-barang/{id}',[PengajuanControllerDR::class, 'update'])->name('direktur.pengajuan-barang.update');
    // Group Route Pengajuan Kasbon
    Route::get('/direktur/pengajuan-kasbon', [PengajuanKasbonControllerDR::class, 'index'])->name('direktur.pengajuan-kasbon.index');
    Route::get('/direktur/kasbon/create', [PengajuanKasbonControllerDR::class, 'create'])->name('direktur.pengajuan-kasbon.create');
    Route::post('/direktur/kasbon', [PengajuanKasbonControllerDR::class, 'store'])->name('direktur.pengajuan-kasbon.store');
    Route::get('/direktur/pengajuan-kasbon/{id}/edit', [PengajuanKasbonControllerDR::class, 'edit'])->name('direktur.pengajuan-kasbon.edit');
    Route::put('/direktur/pengajuan-kasbon/{id}', [PengajuanKasbonControllerDR::class, 'update'])->name('direktur.pengajuan-kasbon.update');
    Route::delete('/direktur/pengajuan-kasbon/{id}', [PengajuanKasbonControllerDR::class, 'destroy'])->name('direktur.pengajuan-kasbon.destroy');
    Route::get('/direktur/pengajuan-kasbon/view/{id}', [PengajuanKasbonControllerDR::class, 'download'])->name('direktur.pengajuan-kasbon.download');
    // Group Route Approve Pengajuan Kasbon
    Route::get('/direktur/approve/kasbon', [KasbonApprovalControllerDR::class, 'index'])->name('direktur.approve.kasbon');
            // Route untuk menyetujui kasbon
    Route::put('/kasbon/disetujui-terima/{id}', [KasbonApprovalControllerDR::class, 'disetujuiTerima'])->name('direktur.kasbon.disetujuiTerima');
    Route::put('/kasbon/disetujui-tolak/{id}', [KasbonApprovalControllerDR::class, 'disetujuiTolak'])->name('direktur.kasbon.disetujuiTolak');
            // Route untuk mengetahui kasbon
    Route::put('/kasbon/diketahui-terima/{id}', [KasbonApprovalControllerDR::class, 'diketahuiTerima'])->name('direktur.kasbon.diketahuiTerima');
    Route::put('/kasbon/diketahui-tolak/{id}', [KasbonApprovalControllerDR::class, 'diketahuiTolak'])->name('direktur.kasbon.diketahuiTolak');
    // Group Route Approve Pengajuan Cuti
    Route::get('/direktur/approve/cuti', [CutiApprovalControllerDR::class, 'index'])->name('direktur.approve.cuti');
    Route::post('/direktur/approve/cuti/{id}/approve', [CutiApprovalControllerDR::class, 'approve'])->name('direktur.approve.cuti.approve');
    Route::post('/approved/{id}/{action}', [CutiApprovalControllerDR::class, 'approve'])->name('approved');
    Route::post('/rejected/{id}', [CutiApprovalControllerDR::class, 'reject'])->name('rejected');
    });
Route::prefix('kepala-gudang')->name('kepala-gudang.')->group(function () {
    Route::get('approve/barang', [BarangApprovalControllerKG::class,'index'])->name('approve.barang.index');
    Route::get('approve/barang/{id}', [BarangApprovalControllerKG::class, 'detail'])->name('approve.barang.detail');
    });
//------
Route::prefix('direktur')->name('direktur.')->group(function () {
    Route::get('approve/barang', [BarangApprovalControllerDR::class,'index'])->name('approve.barang.index');
    Route::get('approve/barang/{id}', [BarangApprovalControllerDR::class, 'detail'])->name('approve.barang.detail');
    });
//------------------------------------------------------------------------------------------------------------------------------------
// Route Approve Barang Manager Keuangan
Route::middleware(['auth'])->group(function () {
    //Route Area Manager
    Route::get('/approve-barang', [BarangApprovalControllerMK::class, 'index'])->name('manager-keuangan.approve.barang');
    Route::get('/manager-keuangan/approve/barang/{id}/detail', [BarangApprovalControllerMK::class, 'detail'])->name('manager-keuangan.approve.barang.detailBarang');
    
    // Menambahkan rute untuk fungsi approve/disapprove
    Route::post('/manager-keuangan/approve/barang/{id}/disetujui-terima', [BarangApprovalControllerMK::class, 'disetujuiTerima'])->name('manager-keuangan.approve.barang.disetujui-terima');
    Route::post('/manager-keuangan/approve/barang/{id}/disetujui-tolak', [BarangApprovalControllerMK::class, 'disetujuiTolak'])->name('manager-keuangan.approve.barang.disetujui-tolak');
    Route::post('/manager-keuangan/approve/barang/{id}/diketahui-terima', [BarangApprovalControllerMK::class, 'diketahuiTerima'])->name('manager-keuangan.approve.barang.diketahui-terima');
    Route::post('/manager-keuangan/approve/barang/{id}/diketahui-tolak', [BarangApprovalControllerMK::class, 'diketahuiTolak'])->name('manager-keuangan.approve.barang.diketahui-tolak');
});
// Route Approve Barang Area Manager
Route::middleware(['auth'])->group(function () {
    //Route Area Manager
    Route::get('/approve-barang', [BarangApprovalController::class, 'index'])->name('area-manager.approve.barang');
    Route::get('/area-manager/approve/barang/{id}/detail', [BarangApprovalController::class, 'detail'])->name('area-manager.approve.barang.detailBarang');
    
    // Menambahkan rute untuk fungsi approve/disapprove
    Route::post('/area-manager/approve/barang/{id}/disetujui-terima', [BarangApprovalController::class, 'disetujuiTerima'])->name('area-manager.approve.barang.disetujui-terima');
    Route::post('/area-manager/approve/barang/{id}/disetujui-tolak', [BarangApprovalController::class, 'disetujuiTolak'])->name('area-manager.approve.barang.disetujui-tolak');
    Route::post('/area-manager/approve/barang/{id}/diketahui-terima', [BarangApprovalController::class, 'diketahuiTerima'])->name('area-manager.approve.barang.diketahui-terima');
    Route::post('/area-manager/approve/barang/{id}/diketahui-tolak', [BarangApprovalController::class, 'diketahuiTolak'])->name('area-manager.approve.barang.diketahui-tolak');
});
// Route Approve Barang Manager Operasional
Route::middleware(['auth'])->group(function () {
        //Route Manager Operasioanal
        Route::get('/approve-barang', [BarangApprovalControllerMo::class, 'index'])->name('manager-operasional.approve.barang');
        Route::get('/manager-operasional/approve/barang/{id}/detail', [BarangApprovalControllerMo::class, 'detail'])->name('manager-operasional.approve.barang.detailBarang');
        // Menambahkan rute untuk fungsi approve/disapprove
        Route::post('/manager-operasional/approve/barang/{id}/disetujui-terima', [BarangApprovalControllerMo::class, 'disetujuiTerima'])->name('manager-operasional.approve.barang.disetujui-terima');
        Route::post('/manager-operasional/approve/barang/{id}/disetujui-tolak', [BarangApprovalControllerMo::class, 'disetujuiTolak'])->name('manager-operasional.approve.barang.disetujui-tolak');
        Route::post('/manager-operasional/approve/barang/{id}/diketahui-terima', [BarangApprovalControllerMo::class, 'diketahuiTerima'])->name('manager-operasional.approve.barang.diketahui-terima');
        Route::post('/manager-operasional/approve/barang/{id}/diketahui-tolak', [BarangApprovalControllerMo::class, 'diketahuiTolak'])->name('manager-operasional.approve.barang.diketahui-tolak');
 //------------------------------------------------------------------------------------------------------------------------------------       
        Route::get('/approve-barang', [BarangApprovalControllerDR::class, 'index'])->name('direktur.approve.barang');
        Route::get('/direktur/approve/barang/{id}/detail', [BarangApprovalControllerDR::class, 'detail'])->name('direktur.approve.barang.detailBarang');
        // Menambahkan rute untuk fungsi approve/disapprove
        Route::post('/direktur/approve/barang/{id}/disetujui-terima', [BarangApprovalControllerDR::class, 'disetujuiTerima'])->name('direktur.approve.barang.disetujui-terima');
        Route::post('/direktur/approve/barang/{id}/disetujui-tolak', [BarangApprovalControllerDR::class, 'disetujuiTolak'])->name('direktur.approve.barang.disetujui-tolak');
        Route::post('/direktur/approve/barang/{id}/diketahui-terima', [BarangApprovalControllerDR::class, 'diketahuiTerima'])->name('direktur.approve.barang.diketahui-terima');
        Route::post('/direktur/approve/barang/{id}/diketahui-tolak', [BarangApprovalControllerDR::class, 'diketahuiTolak'])->name('direktur.approve.barang.diketahui-tolak');
         //------------------------------------------------------------------------------------------------------------------------------------       
         Route::get('/approve-barang', [BarangApprovalControllerKG::class, 'index'])->name('kepala-gudang.approve.barang');
         Route::get('/kepala-gudang/approve/barang/{id}/detail', [BarangApprovalControllerKG::class, 'detail'])->name('kepala-gudang.approve.barang.detailBarang');
         // Menambahkan rute untuk fungsi approve/disapprove
         Route::post('/kepala-gudang/approve/barang/{id}/disetujui-terima', [BarangApprovalControllerKG::class, 'disetujuiTerima'])->name('kepala-gudang.approve.barang.disetujui-terima');
         Route::post('/kepala-gudang/approve/barang/{id}/disetujui-tolak', [BarangApprovalControllerKG::class, 'disetujuiTolak'])->name('kepala-gudang.approve.barang.disetujui-tolak');
         Route::post('/kepala-gudang/approve/barang/{id}/diketahui-terima', [BarangApprovalControllerKG::class, 'diketahuiTerima'])->name('kepala-gudang.approve.barang.diketahui-terima');
         Route::post('/kepala-gudang/approve/barang/{id}/diketahui-tolak', [BarangApprovalControllerKG::class, 'diketahuiTolak'])->name('kepala-gudang.approve.barang.diketahui-tolak');
});
Route::get('/pengajuan-barang/{id}/download-surat', [PengajuanController::class, 'view'])->name('staff-office.pengajuan-barang.download-surat');
