<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\DataKaryawanController;
use App\Http\Controllers\StaffOffice\PengajuanCutiController;
use App\Http\Controllers\StaffOffice\BarangController;
use App\Http\Controllers\StaffOffice\PengajuanController;
use App\Http\Controllers\AreaManager\CutiApprovalController;
use App\Http\Controllers\AreaManager\BarangApprovalController;

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

// Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
// Route::post('/login', [AuthController::class, 'login']);
// Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
// Route::post('/register', [AuthController::class, 'register']);

// Group route dashboard
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

Route::get('/manager-operasional/dashboard', function () {
    return view('manager-operasional.dashboard');
})->name('manager-operasional.dashboard'); // Perbaikan disini, sesuaikan dengan nama yang benar

Route::get('/area-manager/dashboard', function () {
    return view('area-manager.dashboard');
})->name('area-manager.dashboard');

Route::get('/staff-office/dashboard', function () {
    return view('staff-office.dashboard');
})->name('staff-office.dashboard');



//Group route profile user

Route::get('/admin/profile', [ProfileController::class, 'showProfile'])->name('admin.profile');
Route::get('/area-manager/profile', [ProfileController::class, 'showProfile'])->name('area-manager.profile');
Route::get('/manager-operasional/profile', [ProfileController::class, 'showProfile'])->name('manager-operasional.profile');
Route::get('/staff-office/profile', [ProfileController::class, 'showProfile'])->name('staff-office.profile');

Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
Route::put('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
Route::post('/profile/upload-photo', [ProfileController::class, 'uploadPhoto'])->name('profile.upload_photo');


//Group route login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout'); // Menangani logout melalui metode POST


//Group route data karyawan
Route::group(['middleware' => ['auth', 'App\Http\Middleware\RoleMiddleware:admin']], function () {
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
});


// Group route untuk staff office
Route::group(['middleware' => ['auth', 'App\Http\Middleware\RoleMiddleware:staff-office']], function () {
    Route::get('/staff-office/pengajuan-cuti', [PengajuanCutiController::class, 'index'])->name('staff-office.pengajuan-cuti.index');
    Route::get('/staff-office/pengajuan-cuti/create', [PengajuanCutiController::class, 'create'])->name('staff-office.pengajuan-cuti.create');
    Route::post('/staff-office/pengajuan-cuti', [PengajuanCutiController::class, 'store'])->name('staff-office.pengajuan-cuti.store');
    Route::post('/staff-office/pengajuan-cuti/store', [PengajuanCutiController::class, 'store'])->name('staff-office.pengajuan-cuti.store');
    Route::get('/staff-office/pengajuan-cuti/{id}/edit', [PengajuanCutiController::class, 'edit'])->name('staff-office.pengajuan-cuti.edit');
    Route::put('/staff-office/pengajuan-cuti/{id}', [PengajuanCutiController::class, 'update'])->name('staff-office.pengajuan-cuti.update');
    Route::delete('/staff-office/pengajuan-cuti/{id}', [PengajuanCutiController::class, 'destroy'])->name('staff-office.pengajuan-cuti.destroy');
    Route::get('/pengajuan-cuti/{id}/view', 'App\Http\Controllers\StaffOffice\PengajuanCutiController@view')->name('staff-office.pengajuan-cuti.view');
});


// Group route Area-Manager Approve
Route::middleware(['auth'])->group(function () {
    Route::get('/area-manager/approve/cuti', [CutiApprovalController::class, 'index'])->name('area-manager.approve.cuti');
    Route::post('/area-manager/approve/cuti/{id}/approve', [CutiApprovalController::class, 'approve'])->name('area-manager.approve.cuti.approve');
    Route::post('/approved/{id}/{action}', [CutiApprovalController::class, 'approve'])->name('approved');
    Route::post('/rejected/{id}', [CutiApprovalController::class, 'reject'])->name('rejected');
});
Route::prefix('area-manager')->name('area-manager.')->group(function () {
    Route::get('approve/barang', 'AreaManager\BarangApprovalController@index')->name('approve.barang.index');
    Route::get('approve/barang/{id}', 'AreaManager\BarangApprovalController@detail')->name('approve.barang.detail');
    Route::post('approve/barang/{id}/approve', 'AreaManager\BarangApprovalController@approve')->name('approve.barang.approve');
    Route::post('approve/barang/{id}/reject', 'AreaManager\BarangApprovalController@reject')->name('approve.barang.reject');
});
// //Group route pengajuan barang 
// Route::prefix('staff-office')->group(function () {
//     Route::get('pengajuan-barang', [PengajuanBarangController::class, 'index'])->name('staff-office.pengajuan-barang.index');
//     Route::get('/staff-office/pengajuan-barang/create', [PengajuanBarangController::class, 'create'])->name('staff-office.pengajuan-barang.create');
//     Route::post('/staff-office/pengajuan-barang/create', [PengajuanBarangController::class, 'store'])->name('staff-office.pengajuan-barang.store');
// });

// Group route staff office pengajuan barang
Route::get('/pengajuan', [PengajuanController::class, 'index'])->name('staff-office.pengajuan-barang.index');
Route::get('/pengajuan/create', [PengajuanController::class, 'create'])->name('staff-office.pengajuan-barang.create');
Route::post('/pengajuan/store', [PengajuanController::class, 'store'])->name('staff-office.pengajuan-barang.store');
Route::get('pengajuan-barang/{id}/edit', [PengajuanController::class, 'edit'])->name('staff-office.pengajuan-barang.edit');
Route::get('pengajuan-barang/{id}/update', [PengajuanController::class, 'update'])->name('staff-office.pengajuan-barang.update');
Route::delete('pengajuan-barang/{id}', [PengajuanController::class, 'destroy'])->name('staff-office.pengajuan-barang.destroy');
Route::delete('/pengajuan-barang/{id}/delete-item/{item_id}', [PengajuanController::class, 'deleteItem'])->name('staff-office.pengajuan-barang.delete-item');
Route::put('/pengajuan-barang/{id}/update', [PengajuanController::class, 'update'])->name('staff-office.pengajuan-barang.update');
Route::put('/pengajuan-barang/{id}',[PengajuanController::class, 'update'])->name('staff-office.pengajuan-barang.update');


// Route::middleware(['auth'])->group(function () {
//     // Rute-rute yang memerlukan autentikasi pengguna

//     // Contoh rute untuk approve barang
//     Route::get('/approve-barang', [BarangApprovalController::class, 'index'])->name('area-manager.approve.barang');
//     Route::post('/area-manager/approve/barang/{id}/diterima', [BarangApprovalController::class, 'diterima'])->name('area-manager.approve.barang.diterima');
//     Route::post('/area-manager/approve/barang/{id}/ditolak', [BarangApprovalController::class, 'ditolak'])->name('area-manager.approve.barang.ditolak');
//     Route::get('/area-manager/approve/barang/{id}/detail-barang', [BarangApprovalController::class, 'detail'])->name('area-manager.approve.barang.detailBarang');
//     Route::post('/area-manager/approve/barang/{id}/setujui', [BarangApprovalController::class, 'setujui'])->name('area-manager.approve.barang.setujui.barang');
//     Route::post('/area-manager/approve/barang/{id}/tolak', [BarangApprovalController::class, 'tolak'])->name('tolak.barang');
    
// });

Route::middleware(['auth'])->group(function () {
    // Rute-rute yang memerlukan autentikasi pengguna

    Route::get('/approve-barang', [BarangApprovalController::class, 'index'])->name('area-manager.approve.barang');
    Route::get('/area-manager/approve/barang/{id}/detail', [BarangApprovalController::class, 'detail'])->name('area-manager.approve.barang.detailBarang');

    // Menambahkan rute untuk fungsi approve/disapprove
    Route::post('/area-manager/approve/barang/{id}/disetujui-terima', [BarangApprovalController::class, 'disetujuiTerima'])->name('area-manager.approve.barang.disetujui-terima');
    Route::post('/area-manager/approve/barang/{id}/disetujui-tolak', [BarangApprovalController::class, 'disetujuiTolak'])->name('area-manager.approve.barang.disetujui-tolak');
    Route::post('/area-manager/approve/barang/{id}/diketahui-terima', [BarangApprovalController::class, 'diketahuiTerima'])->name('area-manager.approve.barang.diketahui-terima');
    Route::post('/area-manager/approve/barang/{id}/diketahui-tolak', [BarangApprovalController::class, 'diketahuiTolak'])->name('area-manager.approve.barang.diketahui-tolak');
});