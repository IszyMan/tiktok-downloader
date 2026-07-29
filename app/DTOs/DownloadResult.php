<?php

namespace App\DTOs;

class DownloadResult
{
    public function __construct(
        public bool $success,
        public ?string $videoUrl = null,
        public ?string $audioUrl = null,
        public ?string $thumbnail = null,
        public ?string $title = null,
        public ?string $author = null,
        public ?string $error = null,
    ) {
    }
}