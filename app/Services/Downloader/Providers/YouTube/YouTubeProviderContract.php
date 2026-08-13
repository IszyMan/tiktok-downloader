<?php

namespace App\Services\Downloader\Providers\YouTube;

use App\Services\Downloader\DTO\YouTubeVideoData;

interface YouTubeProviderContract
{
    /**
     * Fetch YouTube video metadata.
     */
    public function fetch(string $url): YouTubeVideoData;
}