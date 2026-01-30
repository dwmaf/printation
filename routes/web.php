<?php

use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\PrintStationController;
use App\Livewire\UserUpload;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});
Route::get('/station', [PrintStationController::class, 'index']);
Route::get('/station/{printfile}', [PrintStationController::class, 'show'])->name('station.show');
Route::get('/admin/dashboard', [DashboardAdminController::class, 'index'])->name('admin.dashboard');
