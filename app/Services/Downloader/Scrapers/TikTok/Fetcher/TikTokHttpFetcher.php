<?php

namespace App\Services\Downloader\Scrapers\TikTok\Fetcher;

use Illuminate\Support\Facades\Http;
use RuntimeException;
use App\Services\Downloader\Scrapers\TikTok\Contracts\FetcherContract;

class TikTokHttpFetcher implements FetcherContract
{
    /**
     * Download the HTML for a TikTok page.
     *
     * @throws RuntimeException
     */
    public function fetch(string $url): string
    {
        $response = $this->sendRequest($url);

        $this->validateResponse($response);

        return $response->body();
    }

    /**
     * Send the HTTP request.
     */
    private function sendRequest(string $url)
    {
        return Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36',
                'Accept-Language' => 'en-US,en;q=0.9',
            ])
            ->timeout(20)
            ->retry(2, 500)
            ->get($url);
    }

    /**
     * Ensure we received a valid response.
     */
    private function validateResponse($response): void
    {
        if (! $response->successful()) {
            throw new RuntimeException(
                "Unable to fetch TikTok page. HTTP {$response->status()}."
            );
        }

        if (blank($response->body())) {
            throw new RuntimeException(
                'TikTok returned an empty response.'
            );
        }
    }
}