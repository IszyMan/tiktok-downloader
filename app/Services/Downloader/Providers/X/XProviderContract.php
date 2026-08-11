<?php

namespace App\Services\Downloader\Providers\X;

use App\Services\Downloader\DTO\XVideoData;

interface XProviderContract
{
    public function fetch(string $url): XVideoData;
}