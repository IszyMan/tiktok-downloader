<?php

namespace App\Services\Downloader\Providers\TikTok;

use App\Services\Downloader\DTO\TikTokVideoData;

interface TikTokProviderContract
{
    public function fetch(string $url): TikTokVideoData;
}