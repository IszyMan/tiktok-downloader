<?php

namespace App\Services\Downloader\Providers\Instagram;

use Throwable;
use RuntimeException;
use App\Services\Downloader\DTO\InstagramVideoData;

class InstagramProviderManager
{
    public function __construct(
        private readonly YtDlpInstagramProvider $ytDlp,
    ) {
    }

    public function fetch(string $url): InstagramVideoData
    {
        $errors = [];

        try {
            return $this->ytDlp->fetch($url);

        } catch (Throwable $e) {
            $errors[] = 'yt-dlp: ' . $e->getMessage();
        }

        throw new RuntimeException(
            implode(PHP_EOL, $errors)
        );
    }
}