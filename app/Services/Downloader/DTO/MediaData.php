<?php

namespace App\Services\Downloader\DTO;

class MediaData
{
    public function __construct(

        public readonly ?string $thumbnail,

        public readonly ?string $cover,

    ) {
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}