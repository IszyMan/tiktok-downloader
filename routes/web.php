<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\DebugScraperController;

Route::get('/', [HomeController::class, 'index']);
Route::post('/download', [DownloadController::class, 'download'])->name('download');
Route::get('/debug/scrape', DebugScraperController::class );