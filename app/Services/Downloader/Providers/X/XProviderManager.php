<?php

namespace App\Services\Downloader\Providers\X;

use Throwable;
use RuntimeException;
use App\Services\Downloader\DTO\XVideoData;

class XProviderManager
{
    public function __construct(
        private readonly YtDlpXProvider $ytDlp,
    ) {
    }

    public function fetch(string $url): XVideoData
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