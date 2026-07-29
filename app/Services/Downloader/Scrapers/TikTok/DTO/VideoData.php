<?php

namespace App\Services\Downloader\Scrapers\TikTok\DTO;

class VideoData
{
    public function __construct(
        public readonly string $id,
        public readonly int $duration,
        public readonly int $width,
        public readonly int $height,
        public readonly string $ratio,

        public readonly string $cover,
        public readonly string $originCover,
        public readonly string $dynamicCover,

        public readonly string $playAddr,
        public readonly string $downloadAddr,

        public readonly array $playUrls,
    ) {
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}