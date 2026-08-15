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

        if (
            $host === 'youtube.com' ||
            str_ends_with($host, '.youtube.com') ||
            $host === 'youtu.be'
        ) {
            return 'youtube';
        }

        if (
            $host === 'instagram.com' ||
            str_ends_with($host, '.instagram.com')
        ) {
            return 'instagram';
        }

        if (
            $host === 'facebook.com' ||
            str_ends_with($host, '.facebook.com') ||
            $host === 'fb.watch'
        ) {
            return 'facebook';
        }

        throw new RuntimeException(
            'Unsupported URL. Please enter a supported video URL.'
        );
    }
}