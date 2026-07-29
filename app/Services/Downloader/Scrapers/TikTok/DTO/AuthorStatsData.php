<?php

namespace App\Services\Downloader\Scrapers\TikTok\DTO;

class AuthorStatsData
{
    public function __construct(
        public readonly int $followerCount,
        public readonly int $followingCount,
        public readonly int $heartCount,
        public readonly int $videoCount,
        public readonly int $diggCount,
        public readonly int $friendCount,
    ) {}

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}