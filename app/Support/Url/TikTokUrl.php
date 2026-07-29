<?php

namespace App\Support\Url;

class TikTokUrl
{
    /**
     * All supported TikTok hosts.
     */
    private const HOSTS = [
        'tiktok.com',
        'www.tiktok.com',
        'm.tiktok.com',
        'vm.tiktok.com',
        'vt.tiktok.com',
    ];

    /**
     * Determine if the given URL belongs to TikTok.
     */
    public static function isValid(string $url): bool
    {
        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $host = strtolower(parse_url($url, PHP_URL_HOST) ?? '');

        foreach (self::HOSTS as $allowedHost) {
            if ($host === $allowedHost) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the URL is a TikTok short link.
     */
    public static function isShortUrl(string $url): bool
    {
        if (! self::isValid($url)) {
            return false;
        }

        $host = strtolower(parse_url($url, PHP_URL_HOST) ?? '');

        return in_array($host, [
            'vm.tiktok.com',
            'vt.tiktok.com',
        ], true);
    }

    /**
     * Extract the video ID from supported TikTok URLs.
     *
     * Returns null if the URL doesn't contain a video ID
     * (for example vm.tiktok.com short links).
     */
    public static function extractVideoId(string $url): ?string
    {
        $path = parse_url($url, PHP_URL_PATH) ?? '';

        $patterns = [
            '/\/video\/(\d+)/i',
            '/\/v\/(\d+)/i',
            '/\/embed\/(\d+)/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $path, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    /**
     * Normalize the URL.
     */
    public static function normalize(string $url): string
    {
        return trim($url);
    }
}