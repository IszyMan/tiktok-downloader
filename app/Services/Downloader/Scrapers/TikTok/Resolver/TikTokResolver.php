<?php

namespace App\Services\Downloader\Scrapers\TikTok\Resolver;

use App\Support\Url\TikTokUrl;
use InvalidArgumentException;
use GuzzleHttp\TransferStats;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class TikTokResolver
{
    /**
     * Resolve any TikTok URL into its canonical form.
     *
     * @throws InvalidArgumentException
     */
    public function resolve(string $url): string
    {
        $url = trim($url);

        $this->validateUrl($url);

        if (! $this->needsResolving($url)) {
            return $this->normalize($url);
        }

        $resolvedUrl = $this->followRedirects($url);

        return $this->normalize($resolvedUrl);
    }

    /**
     * Ensure the supplied URL is a valid TikTok URL.
     */
    private function validateUrl(string $url): void
    {
        if (! TikTokUrl::isValid($url)) {
            throw new InvalidArgumentException('Invalid TikTok URL.');
        }
    }

    /**
     * Determine whether the URL needs redirect resolution.
     */
    private function needsResolving(string $url): bool
    {
        return TikTokUrl::isShortUrl($url);
    }

    /**
     * Follow redirects and return the final TikTok URL.
     */
    private function followRedirects(string $url): string
    {
        $effectiveUrl = null;

        $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36',
                'Accept-Language' => 'en-US,en;q=0.9',
            ])
            ->timeout(15)
            ->retry(2, 500)
            ->withOptions([
                'allow_redirects' => true,
                'on_stats' => function (TransferStats $stats) use (&$effectiveUrl) {
                    $effectiveUrl = (string) $stats->getEffectiveUri();
                },
            ])
            ->get($url);

        if (! $response->successful()) {
            throw new RuntimeException(
                "Unable to resolve TikTok URL. HTTP {$response->status()}."
            );
        }

        if (empty($effectiveUrl)) {
            throw new RuntimeException('Unable to determine the resolved TikTok URL.');
        }

        return $effectiveUrl;
    }

    /**
     * Normalize the URL format.
     */
    private function normalize(string $url): string
    {
        return rtrim($url, '/');
    }
}