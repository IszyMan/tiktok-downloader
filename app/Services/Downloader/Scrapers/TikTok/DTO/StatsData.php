<?php

namespace App\Services\Downloader\Scrapers\TikTok\DTO;

class StatsData
{
    public function __construct(
        public readonly int $playCount,
        public readonly int $diggCount,
        public readonly int $commentCount,
        public readonly int $shareCount,
        public readonly int $collectCount,
    ) {
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}