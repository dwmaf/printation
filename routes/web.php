<?php

use App\Http\Controllers\PrintStationController;
use App\Livewire\UserUpload;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});
Route::get('/station', [PrintStationController::class, 'index']);

