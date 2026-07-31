<?php

namespace App\Http\Controllers;

use App\Services\Downloader\Scrapers\TikTok\YtDlp\YtDlpService;

class DebugScraperController extends Controller
{
    public function __invoke(YtDlpService $ytDlp)
    {
        return $ytDlp->execute(
            'https://www.tiktok.com/@scout2015/video/6718335390845095173'
        );
    }
}