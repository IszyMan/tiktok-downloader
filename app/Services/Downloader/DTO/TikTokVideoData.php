<?php

namespace App\Services\Downloader\DTO;

class TikTokVideoData
{
    public function __construct(

        // Identity
        public readonly string $id,

        // Basic Info
        public readonly string $title,
        public readonly string $description,

        // Video
        public readonly ?int $duration,
        public readonly ?int $width,
        public readonly ?int $height,

        // Nested DTOs
        public readonly AuthorData $author,
        public readonly MediaData $media,
        public readonly DownloadData $downloads,
        public readonly StatisticsData $statistics,

        // Provider Information
        public readonly string $provider,

        // Original TikTok URL
        public readonly string $sourceUrl,

        // File
        public readonly ?string $filename,

        // Raw provider response
        public readonly array $extra = [],
    ) {
    }

    public function toArray(): array
    {
        return [

            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,

            'duration' => $this->duration,
            'width' => $this->width,
            'height' => $this->height,

            'author' => $this->author->toArray(),

            'media' => $this->media->toArray(),

            'downloads' => $this->downloads->toArray(),

            'statistics' => $this->statistics->toArray(),

            'provider' => $this->provider,

            'filename' => $this->filename,

            'extra' => $this->extra,
        ];
    }
}