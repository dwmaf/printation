<?php


use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\PrintStationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\PrinterController;
use App\Http\Controllers\TransactionController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/station', [PrintStationController::class, 'index']);
Route::get('/station/{printfile}', [PrintStationController::class, 'show'])->name('station.show');
Route::delete('/station/{printfile}', [PrintStationController::class, 'destroy'])->name('station.destroy');
Route::get('/station/file-info/{printfile}', [PrintStationController::class, 'getFileInfo'])->name('station.info');
Route::get('/admin/dashboard', [DashboardAdminController::class, 'index'])->name('admin.dashboard');
Route::get('/upload', [PrintController::class, 'uploadPage']);
Route::post('/upload', [PrintController::class, 'store']);
Route::post('/process-print', [PrinterController::class, 'print'])->name('process.print');
Route::post('/transaction/store', [TransactionController::class, 'store'])->name('transaction.store');
Route::get('/station/check-latest', function () {
    return \App\Models\Printfile::latest()->first()?->id ?? 0;
});
