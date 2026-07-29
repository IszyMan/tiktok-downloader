<?php

namespace App\Contracts;

use App\DTOs\DownloadResult;

interface DownloaderProviderInterface
{
    public function download(string $url): DownloadResult;
}