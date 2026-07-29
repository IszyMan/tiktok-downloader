<?php

namespace App\Services\Downloader\Scrapers\TikTok\DTO;

class MusicData
{
    public function __construct(
        public readonly string $id,
        public readonly string $title,
        public readonly string $authorName,
        public readonly string $playUrl,
        public readonly int $duration,
        public readonly bool $original,
    ) {
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}