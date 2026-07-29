<?php

namespace App\Services\Downloader\Scrapers\TikTok\DTO;

class AuthorData
{
    public function __construct(
        public readonly string $id,
        public readonly string $uniqueId,
        public readonly string $nickname,
        public readonly string $avatar,
        public readonly string $signature,
        public readonly bool $verified,
        public readonly string $secUid,
    ) {
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}