<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DownloadController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/', [DownloadController::class, 'download'])->name('download');

Route::get('/tiktok-downloader', [DownloadController::class, 'tiktok'])->name('tiktok.downloader');
Route::get('/x-downloader', [DownloadController::class, 'x'])->name('x.downloader');
Route::get('/youtube-downloader', [DownloadController::class, 'youtube'])->name('youtube.downloader');
Route::get('/instagram-downloader', [DownloadController::class, 'instagram'])->name('instagram.downloader');
