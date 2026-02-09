<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\PrintStationController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\PrinterController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AdminTransactionController;
use App\Http\Controllers\TransactionStatusController;

Route::delete('/station/destroy-multiple', [PrintStationController::class, 'destroyMultiple'])
    ->name('station.destroy-multiple');

// FILE INFO
Route::get('/station/file/{printfile}/info', [PrintStationController::class, 'getFileInfo'])
    ->name('station.fileInfo');

// CREATE TX (sudah ada di kamu)
Route::post('/transaction', [TransactionController::class, 'store'])
    ->name('transaction.store');

// STATUS TX (BARU)
Route::get('/transaction/status/{orderId}', [TransactionController::class, 'status'])
    ->name('transaction.status');

// PRINT JOB VIA LARAVEL PROXY (BARU) -> NO CTRL+P
Route::post('/station/print/{orderId}', [PrintStationController::class, 'printJob'])
    ->name('station.print');

Route::get('/transactions/status/{orderId}', [TransactionStatusController::class, 'show']);


Route::get('/transactions/{orderId}/status', [TransactionController::class, 'status'])
    ->name('transaction.status');
Route::post('/station/print/{orderId}', [PrintStationController::class, 'printByOrder'])
    ->name('station.printByOrder');

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| SUPER ADMIN (HARUS LOGIN + ROLE super-admin)
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => ['auth', 'role:super-admin']], function () {
    Route::get('/admin/outlets', [DashboardAdminController::class, 'indexOutlet'])->name('admin.outlets');
    Route::post('/admin/outlets', [DashboardAdminController::class, 'storeOutlet'])->name('admin.outlets.store');

    // ✅ TAMBAH INI
    Route::get('/admin/transactions', [\App\Http\Controllers\AdminTransactionController::class, 'index'])->name('admin.transactions');
    Route::post('/admin/transactions/{transaction}/approve', [\App\Http\Controllers\AdminTransactionController::class, 'approve'])->name('admin.transactions.approve');
    Route::post('/admin/transactions/{transaction}/reject', [\App\Http\Controllers\AdminTransactionController::class, 'reject'])->name('admin.transactions.reject');
});


/*
|--------------------------------------------------------------------------
| STATION (kalau mau dilock login station, tambahkan middleware auth+role:station)
|--------------------------------------------------------------------------
| Kalau kamu belum siap lock station, biarkan tanpa middleware dulu.
| Kalau sudah siap, ubah jadi:
| Route::group(['middleware' => ['auth','role:station']], function () { ... });
*/
Route::get('/station', [PrintStationController::class, 'index'])->name('station.index');
Route::get('/station/{printfile}', [PrintStationController::class, 'show'])->name('station.show');
Route::delete('/station/{printfile}', [PrintStationController::class, 'destroy'])->name('station.destroy');
Route::get('/station/file-info/{printfile}', [PrintStationController::class, 'getFileInfo'])->name('station.info');

/*
|--------------------------------------------------------------------------
| USER UPLOAD (HP)
|--------------------------------------------------------------------------
*/
Route::get('/upload', [PrintController::class, 'uploadPage'])->name('upload.page');
Route::post('/upload', [PrintController::class, 'store'])->name('upload.store');

/*
|--------------------------------------------------------------------------
| PRINT + TRANSACTION
|--------------------------------------------------------------------------
*/
Route::post('/process-print', [PrinterController::class, 'print'])->name('process.print');
Route::post('/transaction/store', [TransactionController::class, 'store'])->name('transaction.store');
