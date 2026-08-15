<?php

namespace App\Services\Downloader\Providers\Facebook;

use Throwable;
use RuntimeException;
use App\Services\Downloader\DTO\FacebookVideoData;

class FacebookProviderManager
{
    public function __construct(
        private readonly YtDlpFacebookProvider $ytDlp,
    ) {
    }

    public function fetch(string $url): FacebookVideoData
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