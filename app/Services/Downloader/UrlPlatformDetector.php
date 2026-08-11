<?php

namespace App\Services\Downloader;

use RuntimeException;

class UrlPlatformDetector
{
    public function detect(string $url): string
    {
        $host = strtolower(
            parse_url($url, PHP_URL_HOST) ?? ''
        );

        $host = preg_replace('/^www\./', '', $host);

        if (
            $host === 'tiktok.com' ||
            str_ends_with($host, '.tiktok.com')
        ) {
            return 'tiktok';
        }

        if (
            $host === 'x.com' ||
            str_ends_with($host, '.x.com') ||
            $host === 'twitter.com' ||
            str_ends_with($host, '.twitter.com')
        ) {
            return 'x';
        }

        throw new RuntimeException(
            'Unsupported URL. Please enter a TikTok or X video URL.'
        );
    }
}