<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;


Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $user = auth()->user();
    
    if ($user->hasRole('super-admin')) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->hasRole('outlet-owner')) {
        return redirect()->route('outlet.dashboard');
    } elseif ($user->hasRole('station')) {
        return redirect()->route('station.index');
    }

    return redirect()->to('/login'); // Fallback jika role tidak dikenali
});

Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| SUPER ADMIN (HARUS LOGIN + ROLE super-admin)
|--------------------------------------------------------------------------
*/
// Route::group(['middleware' => ['auth', 'role:super-admin']], function () {
//     Route::get('/admin/dashboard', [DashboardAdminController::class, 'indexDashboard'])->name('admin.dashboard');
//     Route::get('/admin/upa/outlets', [OutletAdminController::class, 'index'])->name('admin.upa.outlets');
//     Route::post('/admin/upa/outlets', [OutletAdminController::class, 'store'])->name('admin.upa.outlets.store');
//     Route::put('/admin/upa/outlets/{id}', [OutletAdminController::class, 'update'])->name('admin.upa.outlets.update');
// });


/*
|--------------------------------------------------------------------------
| STATION (kalau mau dilock login station, tambahkan middleware auth+role:station)
|--------------------------------------------------------------------------
| Kalau kamu belum siap lock station, biarkan tanpa middleware dulu.
| Kalau sudah siap, ubah jadi:
*/
// Route::group(['middleware' => ['auth', 'role:station']], function () {
//     Route::get('/station', [PrintStationController::class, 'index'])->name('station.index');
//     Route::delete('/station/destroy-multiple', [PrintStationController::class, 'destroyMultiple'])->name('station.destroy-multiple');
//     Route::get('/station/{printfile}', [PrintStationController::class, 'show'])->name('station.show');
//     Route::delete('/station/{printfile}', [PrintStationController::class, 'destroy'])->name('station.destroy');
//     Route::get('/station/file-info/{printfile}', [PrintStationController::class, 'getFileInfo'])->name('station.info');
//     Route::get('/station/file/{printfile}/info', [PrintStationController::class, 'getFileInfo'])
//         ->name('station.fileInfo');
//     Route::post('/station/print/{orderId}', [PrintStationController::class, 'printJob'])
//         ->name('station.print');
// });

/*
|--------------------------------------------------------------------------
| USER UPLOAD (HP)
|--------------------------------------------------------------------------
*/
// Route::get('/upload/{id}', [UserUploadController::class, 'index'])->name('upload.page');
// Route::post('/upload{id}', [UserUploadController::class, 'store'])->name('upload.store');

/*
|--------------------------------------------------------------------------
| PRINT + TRANSACTION
|--------------------------------------------------------------------------
*/
// Route::post('/process-print', [PrinterController::class, 'print'])->name('process.print');
// Route::post('/transaction/store', [TransactionController::class, 'store'])->name('transaction.store');


/*
|--------------------------------------------------------------------------
| OUTLET OWNER
|--------------------------------------------------------------------------
*/
// Route::group(['middleware' => ['auth', 'role:outlet-owner']], function () {
//     Route::get('/outlet/dashboard', [OutletController::class, 'index'])->name('outlet.dashboard');
//     Route::get('/outlet/payments', [OutletController::class, 'payments'])->name('outlet.payments');
//     Route::get('/outlet/stations', [OutletController::class, 'indexStation'])->name('outlet.stations.index');
//     Route::get('/outlet/files', [OutletController::class, 'indexFiles'])->name('outlet.files.index');
//     Route::delete('/outlet/files/clear-all', [OutletController::class, 'clearAllFiles'])->name('outlet.files.clear-all');
//     Route::delete('/outlet/files/bulk/{stationId}', [OutletController::class, 'bulkDeleteByStation'])->name('outlet.files.bulk-station');
//     Route::delete('/outlet/files/{id}', [OutletController::class, 'destroyFile'])->name('outlet.files.destroy');
//     Route::get('/outlet/qris', [OutletController::class, 'editQRIS'])->name('outlet.editQRIS');
//     Route::post('/outlet/verify/{id}', [OutletController::class, 'verify'])->name('outlet.verify');
//     Route::post('/outlet/reject/{id}', [OutletController::class, 'reject'])->name('outlet.reject');
//     Route::post('/outlet/station', [OutletController::class, 'storeStation'])->name('outlet.storeStation');
//     Route::delete('/outlet/station/{id}', [OutletController::class, 'destroyStation'])->name('outlet.destroyStation');
//     Route::post('/outlet/update-qris', [OutletController::class, 'updateQRIS'])->name('outlet.updateQRIS');
// });






// INERTIA AUTH (SUPER ADMIN)
// Route::group(['middleware' => ['auth', 'role:super-admin']], function () {
//     Route::get('/admin/upa/dashboard', [InertiaAuthController::class, 'dashboard'])->name('admin.upa.dashboard');
//     Route::get('/admin/upa/verify-print', [InertiaVerifyPrintController::class, 'index'])->name('admin.upa.verify-print.index');
//     Route::post('/admin/upa/verify-print/{id}/verify', [InertiaVerifyPrintController::class, 'verify'])->name('admin.upa.verify-print.verify');
//     Route::post('/admin/upa/verify-print/{id}/reject', [InertiaVerifyPrintController::class, 'reject'])->name('admin.upa.verify-print.reject');
// });

// Route::group(['middleware' => ['auth', 'role:station-upa-pkk']], function () {
//     Route::get('/upa/station', [InertiaPrintStationController::class, 'index'])->name('upa.station.index');
//     Route::get('/station/proxy-pdf/{id}', [InertiaPrintStationController::class, 'proxyPdf'])->name('upa.station.proxy-pdf');
//     Route::post('/upa/station/request-print', [InertiaPrintStationController::class, 'submitRequest'])->name('upa.station.request-print');
//     Route::delete('/upa/station/file/{printfile}', [InertiaPrintStationController::class, 'destroy'])->name('upa.station.file.destroy');
//     Route::delete('/upa/station/destroy-multiple', [InertiaPrintStationController::class, 'destroyMultiple'])->name('upa.station.destroy-multiple');
//     Route::delete('/upa/station/destroy/{filetoprint}', [InertiaPrintStationController::class, 'destroy'])->name('upa.station.destroy');
//     Route::post('/upa/station/print', [InertiaPrintStationController::class, 'print'])->name('upa.station.print');
// });
