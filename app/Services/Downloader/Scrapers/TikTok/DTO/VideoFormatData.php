<?php

namespace App\Services\Downloader\Scrapers\TikTok\DTO;

class VideoFormatData
{
    public function __construct(
        public readonly string $formatId,
        public readonly string $url,

        public readonly string $extension,

        public readonly ?string $videoCodec,
        public readonly ?string $audioCodec,

        public readonly ?int $width,
        public readonly ?int $height,

        public readonly ?int $filesize,

        public readonly ?float $bitrate,

        public readonly ?string $qualityLabel,
    ) {
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}