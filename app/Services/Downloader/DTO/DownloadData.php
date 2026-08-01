<?php

namespace App\Services\Downloader\DTO;

class DownloadData
{
    public function __construct(

        public readonly ?string $playUrl,
        public readonly ?string $hdPlayUrl,
        public readonly ?string $watermarkUrl,
        public readonly ?string $musicUrl,

        public readonly ?int $playSize,
        public readonly ?int $hdSize,
        public readonly ?int $watermarkSize,

        public readonly ?string $format,
        public readonly ?string $formatId,
    ) {
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}