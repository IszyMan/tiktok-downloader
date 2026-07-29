<?php

namespace App\Services\Downloader\Scrapers\TikTok\Fetcher;

use RuntimeException;

class TikTokBrowserFetcher
{
    public function fetch(string $url): string
    {
        $script = escapeshellarg(
            base_path('playwright/playwright-fetch.js')
        );

        $url = escapeshellarg($url);

        $output = shell_exec(
            "node {$script} {$url} 2>&1"
        );

        if ($output === null) {
            throw new RuntimeException(
                'Browser fetch failed.'
            );
        }

        $html = trim($output);

        if ($html === '') {
            throw new RuntimeException(
                'Playwright returned an empty HTML response.'
            );
        }

        return $html;
    }
}