<?php

namespace App\Services\Downloader\Providers\YouTube;

use Throwable;
use RuntimeException;
use App\Services\Downloader\DTO\YouTubeVideoData;

class YouTubeProviderManager
{
    public function __construct(
        private readonly YtDlpYouTubeProvider $provider,
    ) {
    }

    public function fetch(string $url): YouTubeVideoData
    {
        $errors = [];

        try {

            return $this->provider->fetch($url);

        } catch (Throwable $e) {

            $errors[] = 'yt-dlp: ' . $e->getMessage();
        }

        throw new RuntimeException(
            implode(PHP_EOL, $errors)
        );
    }
}