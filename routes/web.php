<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\DebugScraperController;
use App\Services\Downloader\Scrapers\TikTok\TikWM\TikWMService;

Route::get('/', [HomeController::class, 'index']);
Route::post('/download', [DownloadController::class, 'download'])->name('download');
Route::get('/debug/scrape', DebugScraperController::class );
Route::get('/debug/ytdlp', DebugScraperController::class);

Route::get('/test-tikwm', function (TikWMService $service) {

    return response()->json(

        $service
            ->getVideoData(
                'https://www.tiktok.com/@mrbeast/video/7654638524729216287'
            )
            ->toArray()

    );

});