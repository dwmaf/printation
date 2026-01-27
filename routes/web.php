<?php

use App\Livewire\PrintStation;
use App\Livewire\UserUpload;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});
Route::get('/station', PrintStation::class); 
Route::get('/upload', UserUpload::class);
