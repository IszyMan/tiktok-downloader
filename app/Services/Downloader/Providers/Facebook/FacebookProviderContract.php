<?php

namespace App\Services\Downloader\Providers\Facebook;

use App\Services\Downloader\DTO\FacebookVideoData;

interface FacebookProviderContract
{
    public function fetch(string $url): FacebookVideoData;
}