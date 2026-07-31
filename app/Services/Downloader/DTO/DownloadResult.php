<?php

namespace App\Services\Downloader\DTO;

use App\Services\Downloader\Scrapers\TikTok\DTO\TikTokVideoData;

class DownloadResult
{
    public function __construct(
        public readonly bool $success,
        public readonly ?TikTokVideoData $videoData = null,
        public readonly ?string $downloadUrl = null,
        public readonly ?string $filename = null,
        public readonly ?string $mimeType = null,
        public readonly ?int $size = null,
        public readonly ?string $provider = null,
        public readonly ?string $error = null,
    ) {
    }
}