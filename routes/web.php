<?php


use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\PrintStationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\PrinterController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::group(['middleware' => ['auth', 'role:super-admin']], function () {
    Route::get('/admin/outlets', [DashboardAdminController::class, 'indexOutlet'])->name('admin.outlets');
    Route::post('/admin/outlets', [DashboardAdminController::class, 'storeOutlet'])->name('admin.outlets.store');
});
Route::get('/station', [PrintStationController::class, 'index']);
Route::get('/station/{printfile}', [PrintStationController::class, 'show'])->name('station.show');
Route::delete('/station/{printfile}', [PrintStationController::class, 'destroy'])->name('station.destroy');
Route::delete('/station-multiple', [PrintStationController::class, 'destroyMultiple'])->name('station.destroy-multiple');
Route::get('/station/file-info/{printfile}', [PrintStationController::class, 'getFileInfo'])->name('station.info');
Route::get('/admin/dashboard', [DashboardAdminController::class, 'index'])->name('admin.dashboard');
Route::get('/upload', [PrintController::class, 'uploadPage']);
Route::post('/upload', [PrintController::class, 'store']);
Route::post('/process-print', [PrinterController::class, 'print'])->name('process.print');
Route::post('/transaction/store', [TransactionController::class, 'store'])->name('transaction.store');
Route::get('/station/check-latest', function () {
    return \App\Models\Printfile::latest()->first()?->id ?? 0;
});
