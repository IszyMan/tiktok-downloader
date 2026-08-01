<?php

namespace App\Services\Downloader\Providers\TikTok;

use Throwable;
use RuntimeException;
use App\Services\Downloader\DTO\TikTokVideoData;

class TikTokProviderManager
{
    public function __construct(
        private readonly TikWMProvider $tikWM,
        private readonly YtDlpProvider $ytDlp,
    ) {
    }

    public function fetch(string $url): TikTokVideoData
    {
        $errors = [];

        try {
            return $this->tikWM->fetch($url);

        } catch (Throwable $e) {
            $errors[] = 'TikWM: '.$e->getMessage();
        }

        /** Here YtDlp pipeline was commented out
        try {
            return $this->ytDlp->fetch($url);

        } catch (Throwable $e) {
            $errors[] = 'yt-dlp: '.$e->getMessage();
        }

        */

        throw new RuntimeException(
            implode(PHP_EOL, $errors)
        );
    }
}