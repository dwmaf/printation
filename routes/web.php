<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuperAdmin\DashboardAdminController;
use App\Http\Controllers\Outlets\DashboardOutletController;
use App\Http\Controllers\Outlets\StationController;
use App\Http\Controllers\Outlets\VerifyPrintController;


Route::get('/', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    /** @var \App\Models\User $user */
    $user = Auth::user();
    if ($user->hasRole('super-admin')) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->hasRole('outlet-owner')) {
        return redirect()->route('outlet.dashboard');
    } elseif ($user->hasRole('station')) {
        return redirect()->route('station.index');
    }
    return redirect()->to('/login');
});

Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::group(['middleware' => ['auth', 'role:super-admin']], function () {
    Route::get('/admin/dashboard', [DashboardAdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/outlets', [DashboardAdminController::class, 'indexOutlet'])->name('admin.outlets.index');
    Route::post('/admin/outlets', [DashboardAdminController::class, 'storeOutlet'])->name('admin.outlets.store');
    Route::put('/admin/outlets/{id}', [DashboardAdminController::class, 'updateOutlet'])->name('admin.outlets.update');
    Route::delete('/admin/outlets/{id}', [DashboardAdminController::class, 'destroyOutlet'])->name('admin.outlets.destroy');
});

// ROUTE UNTUK OUTLET (OWNER)
Route::group(['middleware' => ['auth', 'role:outlet-owner']], function () {
    Route::get('/outlet/dashboard', [DashboardOutletController::class, 'index'])->name('outlet.dashboard');
    
    // Stations
    Route::get('/outlet/stations', [StationController::class, 'index'])->name('outlet.stations.index');
    Route::post('/outlet/stations', [StationController::class, 'store'])->name('outlet.stations.store');
    Route::put('/outlet/stations/{id}', [StationController::class, 'update'])->name('outlet.stations.update');
    Route::delete('/outlet/stations/{id}', [StationController::class, 'destroy'])->name('outlet.stations.destroy');
    
    // Verifikasi Print (Transaction Request)
    Route::get('/outlet/verify-print', [VerifyPrintController::class, 'index'])->name('outlet.verify-print.index');
    Route::post('/outlet/verify-print/{id}/verify', [VerifyPrintController::class, 'verify'])->name('outlet.verify-print.verify');
    Route::post('/outlet/verify-print/{id}/reject', [VerifyPrintController::class, 'reject'])->name('outlet.verify-print.reject');
});

