<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuperAdmin\DashboardAdminController;


Route::get('/', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    /** @var \App\Models\User $user */
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
