<?php

namespace App\Services\Downloader\DTO;

class StatisticsData
{
    public function __construct(

        public readonly ?int $views,

        public readonly ?int $likes,

        public readonly ?int $comments,

        public readonly ?int $shares,

        public readonly ?int $downloads,

        public readonly ?int $favorites,
    ) {
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}