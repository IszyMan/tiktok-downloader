<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DownloadController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/', [DownloadController::class, 'download'])->name('download');

Route::get('/test-route', function () {
    dd(route('download'));
});