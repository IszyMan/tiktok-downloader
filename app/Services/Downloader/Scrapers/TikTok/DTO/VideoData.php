<?php

namespace App\Services\Downloader\Scrapers\TikTok\DTO;

class VideoData
{
    public function __construct(

        public readonly string $id,

        public readonly int $duration,

        public readonly int $width,

        public readonly int $height,

        public readonly string $ratio,

        public readonly string $cover,

        public readonly string $originCover,

        public readonly string $dynamicCover,

        public readonly string $playAddr,

        public readonly string $downloadAddr,

        /**
         * Legacy URLs from TikTok scraper.
         */
        public readonly array $playUrls,

        /**
         * Available video formats from yt-dlp/API.
         *
         * @var VideoFormatData[]
         */
        public readonly array $formats,

        /**
         * HTTP headers required to download media.
         */
        public readonly array $headers = [],

        /**
         * TikTok cookies required by some providers.
         */
        public readonly ?string $cookies = null,
    ) {
    }


    public function toArray(): array
    {
        return [
            'id' => $this->id,

            'duration' => $this->duration,

            'width' => $this->width,

            'height' => $this->height,

            'ratio' => $this->ratio,

            'cover' => $this->cover,

            'originCover' => $this->originCover,

            'dynamicCover' => $this->dynamicCover,

            'playAddr' => $this->playAddr,

            'downloadAddr' => $this->downloadAddr,

            'playUrls' => $this->playUrls,

            'formats' => array_map(
                fn (VideoFormatData $format) => $format->toArray(),
                $this->formats
            ),

            'headers' => $this->headers,

            'cookies' => $this->cookies,
        ];
    }
}