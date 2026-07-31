<?php

namespace App\Services\Downloader\Managers;

use App\Services\Downloader\DTO\DownloadResult;
use App\Services\Downloader\Scrapers\TikTok\Download\TikTokDownloadService;
use App\Services\Downloader\Downloaders\MediaDownloader;

class DownloadManager
{
    public function __construct(
        private readonly TikTokDownloadService $tikTokDownloadService,
        private readonly MediaDownloader $mediaDownloader,
    ) {
    }

    public function download(string $url): DownloadResult
    {
        $videoData = $this->tikTokDownloadService->download($url);
        $video = $this->mediaDownloader->download($videoData->video->downloadAddr);

        dd($videoData->video->downloadAddr);
    }
}