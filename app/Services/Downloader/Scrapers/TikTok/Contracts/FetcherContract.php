<?php

namespace App\Services\Downloader\Scrapers\TikTok\Contracts;

interface FetcherContract
{
    /**
     * Fetch the HTML for a URL.
     */
    public function fetch(string $url): string;
}