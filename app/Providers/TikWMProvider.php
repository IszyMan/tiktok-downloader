<?php

namespace App\Providers;

use App\Contracts\DownloaderProviderInterface;
use App\DTOs\DownloadResult;

class TikWMProvider implements DownloaderProviderInterface
{
    public function download(string $url): DownloadResult
    {
        return new DownloadResult(
            success: true,
            videoUrl: 'Coming Soon',
            audioUrl: null,
            thumbnail: null,
            title: 'Demo Response',
            author: 'TikWM Provider',
        );
    }
}