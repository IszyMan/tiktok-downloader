<?php

namespace App\Services\Downloader\Scrapers\TikTok\Download;

use App\Services\Downloader\Scrapers\TikTok\DTO\TikTokVideoData;
use App\Services\Downloader\Scrapers\TikTok\Fetcher\FetchManager;
use App\Services\Downloader\Scrapers\TikTok\Parser\TikTokParser;
use App\Services\Downloader\Scrapers\TikTok\Resolver\TikTokResolver;

class TikTokDownloadService
{
    public function __construct(
        private readonly TikTokResolver $resolver,
        private readonly FetchManager $fetchManager,
        private readonly TikTokParser $parser,
    ) {
    }

    public function download(string $url): TikTokVideoData
    {
        $resolved = $this->resolver->resolve($url);

        $html = $this->fetchManager->fetch($resolved);

        return $this->parser->parse($html);
    }
}