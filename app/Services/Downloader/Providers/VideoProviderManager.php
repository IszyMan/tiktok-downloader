<?php

namespace App\Services\Downloader\Providers;

use RuntimeException;
use App\Services\Downloader\DTO\VideoData;
use App\Services\Downloader\Providers\TikTok\TikTokProviderManager;
use App\Services\Downloader\Providers\X\XProviderManager;
use App\Services\Downloader\Providers\YouTube\YouTubeProviderManager;
use App\Services\Downloader\UrlPlatformDetector;



class VideoProviderManager
{
    public function __construct(
        private readonly UrlPlatformDetector $detector,
        private readonly TikTokProviderManager $tikTok,
        private readonly XProviderManager $x,
        private readonly YouTubeProviderManager $youtube,
    ) {
    }

    public function fetch(string $url): VideoData
    {
        $platform = $this->detector->detect($url);

        return match ($platform) {
            'tiktok' => $this->tikTok->fetch($url),
            'x' => $this->x->fetch($url),
            'youtube' => $this->youtube->fetch($url),

            default => throw new RuntimeException(
                "Unsupported platform [{$platform}]"
            ),
        };
    }
}