<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\LanguageController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/', [DownloadController::class, 'download'])->name('download');

Route::get('/tiktok-downloader', [DownloadController::class, 'tiktok'])->name('tiktok.downloader');
Route::get('/x-downloader', [DownloadController::class, 'x'])->name('x.downloader');
Route::get('/youtube-downloader', [DownloadController::class, 'youtube'])->name('youtube.downloader');
Route::get('/instagram-downloader', [DownloadController::class, 'instagram'])->name('instagram.downloader');
Route::get('/facebook-downloader', [DownloadController::class, 'facebook'])->name('facebook.downloader');


Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');



