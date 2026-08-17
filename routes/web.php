<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\LanguageController;


/*
|--------------------------------------------------------------------------
| Language Switcher
|--------------------------------------------------------------------------
*/

Route::get(
    '/language/{locale}',
    [LanguageController::class, 'switch']
)->name('language.switch');


/*
|--------------------------------------------------------------------------
| English Routes
|--------------------------------------------------------------------------
|
| English is the default language and has NO URL prefix.
|
*/


/*
|--------------------------------------------------------------------------
| English Home
|--------------------------------------------------------------------------
*/

Route::get(
    '/',
    [HomeController::class, 'index']
)->name('home');


/*
|--------------------------------------------------------------------------
| English Universal Download
|--------------------------------------------------------------------------
*/

Route::post(
    '/',
    [DownloadController::class, 'download']
)->name('download');


/*
|--------------------------------------------------------------------------
| English TikTok Video Downloader
|--------------------------------------------------------------------------
*/

Route::get(
    '/tiktok-video-downloader',
    [DownloadController::class, 'tiktok']
)->name('tiktok.downloader');

Route::post(
    '/tiktok-video-downloader',
    [DownloadController::class, 'download']
)->name('tiktok.download');


/*
|--------------------------------------------------------------------------
| English X Video Downloader
|--------------------------------------------------------------------------
*/

Route::get(
    '/x-video-downloader',
    [DownloadController::class, 'x']
)->name('x.downloader');

Route::post(
    '/x-video-downloader',
    [DownloadController::class, 'download']
)->name('x.download');


/*
|--------------------------------------------------------------------------
| English YouTube Video Downloader
|--------------------------------------------------------------------------
*/

Route::get(
    '/youtube-video-downloader',
    [DownloadController::class, 'youtube']
)->name('youtube.downloader');

Route::post(
    '/youtube-video-downloader',
    [DownloadController::class, 'download']
)->name('youtube.download');


/*
|--------------------------------------------------------------------------
| English Instagram Video Downloader
|--------------------------------------------------------------------------
*/

Route::get(
    '/instagram-video-downloader',
    [DownloadController::class, 'instagram']
)->name('instagram.downloader');

Route::post(
    '/instagram-video-downloader',
    [DownloadController::class, 'download']
)->name('instagram.download');


/*
|--------------------------------------------------------------------------
| English Facebook Video Downloader
|--------------------------------------------------------------------------
*/

Route::get(
    '/facebook-video-downloader',
    [DownloadController::class, 'facebook']
)->name('facebook.downloader');

Route::post(
    '/facebook-video-downloader',
    [DownloadController::class, 'download']
)->name('facebook.download');


/*
|--------------------------------------------------------------------------
| Localized Routes
|--------------------------------------------------------------------------
|
| Supported:
|
| /fr/...
| /es/...
| /de/...
| /pt/...
|
*/

foreach (['fr', 'es', 'de', 'pt'] as $locale) {

    Route::prefix($locale)
        ->name($locale . '.')
        ->group(function () {

            /*
            |--------------------------------------------------------------------------
            | Localized Home
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/',
                [HomeController::class, 'index']
            )->name('home');


            /*
            |--------------------------------------------------------------------------
            | Localized Universal Download
            |--------------------------------------------------------------------------
            */

            Route::post(
                '/',
                [DownloadController::class, 'download']
            )->name('download');


            /*
            |--------------------------------------------------------------------------
            | Localized TikTok Video Downloader
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/tiktok-video-downloader',
                [DownloadController::class, 'tiktok']
            )->name('tiktok.downloader');

            Route::post(
                '/tiktok-video-downloader',
                [DownloadController::class, 'download']
            )->name('tiktok.download');


            /*
            |--------------------------------------------------------------------------
            | Localized X Video Downloader
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/x-video-downloader',
                [DownloadController::class, 'x']
            )->name('x.downloader');

            Route::post(
                '/x-video-downloader',
                [DownloadController::class, 'download']
            )->name('x.download');


            /*
            |--------------------------------------------------------------------------
            | Localized YouTube Video Downloader
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/youtube-video-downloader',
                [DownloadController::class, 'youtube']
            )->name('youtube.downloader');

            Route::post(
                '/youtube-video-downloader',
                [DownloadController::class, 'download']
            )->name('youtube.download');


            /*
            |--------------------------------------------------------------------------
            | Localized Instagram Video Downloader
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/instagram-video-downloader',
                [DownloadController::class, 'instagram']
            )->name('instagram.downloader');

            Route::post(
                '/instagram-video-downloader',
                [DownloadController::class, 'download']
            )->name('instagram.download');


            /*
            |--------------------------------------------------------------------------
            | Localized Facebook Video Downloader
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/facebook-video-downloader',
                [DownloadController::class, 'facebook']
            )->name('facebook.downloader');

            Route::post(
                '/facebook-video-downloader',
                [DownloadController::class, 'download']
            )->name('facebook.download');

        });
}