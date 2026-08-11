<?php

namespace App\Services\Downloader\DTO;

class VideoData
{
    public function __construct(
        public readonly string $id,
        public readonly string $title,
        public readonly string $description,
        public readonly ?float $duration,
        public readonly ?int $width,
        public readonly ?int $height,
        public readonly AuthorData $author,
        public readonly MediaData $media,
        public readonly DownloadData $downloads,
        public readonly StatisticsData $statistics,
        public readonly string $provider,
        public readonly string $sourceUrl,
        public readonly string $filename,
        public readonly array $extra = [],
    ) {
    }
}