<?php

namespace App\Services\Downloader\Providers\Instagram;

use App\Services\Downloader\DTO\InstagramVideoData;

interface InstagramProviderContract
{
    public function fetch(string $url): InstagramVideoData;
}