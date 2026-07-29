<?php

namespace App\Services\Downloader\Scrapers\TikTok\Fetcher;

use RuntimeException;
use Throwable;
use App\Services\Downloader\Scrapers\TikTok\Enums\ChallengeType;
use App\Services\Downloader\Scrapers\TikTok\Detector\ChallengeDetector;

class FetchManager
{
    public function __construct(
        private readonly TikTokHttpFetcher $httpFetcher,
        private readonly TikTokBrowserFetcher $browserFetcher,
        private readonly ChallengeDetector $detector,
    ) {
    }

    /**
     * Fetch HTML for a TikTok page.
     *
     * Strategy:
     * 1. Try the HTTP fetcher first.
     * 2. If HTTP fails or returns a challenge page,
     *    fall back to the browser fetcher.
     * 3. Only return HTML if it is a valid TikTok page.
     *
     * @throws RuntimeException
     */
    public function fetch(string $url): string
    {
        try {
            // First attempt: HTTP fetch.
            $html = $this->fetchWithHttp($url);

            $challenge = $this->detectChallenge($html);

            if ($this->isSuccessfulFetch($challenge)) {
                return $html;
            }

        } catch (Throwable $e) {
            // HTTP fetch failed (timeout, connection error, etc.).
            // Fall back to the browser fetcher.
        }

        // Second attempt: Browser fetch.
        $html = $this->fetchWithBrowser($url);

        file_put_contents(
            storage_path('app/browser-response.html'),
            $html
        );

        $challenge = $this->detectChallenge($html);

        if ($this->isSuccessfulFetch($challenge)) {
            return $html;
        }

        throw new RuntimeException(
            "Fetch failed. Challenge detected: {$challenge->value}."
        );
    }

    /**
     * Fetch HTML using the HTTP fetcher.
     */
    private function fetchWithHttp(string $url): string
    {
        return $this->httpFetcher->fetch($url);
    }

    /**
     * Fetch HTML using the browser fetcher.
     */
    private function fetchWithBrowser(string $url): string
    {
        return $this->browserFetcher->fetch($url);
    }

    /**
     * Determine what kind of page was fetched.
     */
    private function detectChallenge(string $html): ChallengeType
    {
        return $this->detector->detect($html);
    }

    /**
     * Determine whether the fetch was successful.
     */
    private function isSuccessfulFetch(ChallengeType $challenge): bool
    {
        return $challenge === ChallengeType::VALID;
    }
}