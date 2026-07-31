<?php

namespace App\Services\Downloader\Scrapers\TikTok\YtDlp;

use App\Services\Downloader\Scrapers\TikTok\DTO\TikTokVideoData;
use App\Services\Downloader\Scrapers\TikTok\DTO\VideoData;
use App\Services\Downloader\Scrapers\TikTok\DTO\VideoFormatData;

class YtDlpMapper
{
    public function map(array $data): TikTokVideoData
    {
        return new TikTokVideoData(
            id: (string) $data['id'],

            description: $data['description'] ?? '',

            createTime: (int) ($data['timestamp'] ?? 0),

            video: $this->mapVideo($data),

            author: $this->mapAuthor($data),

            music: $this->mapMusic($data),

            stats: $this->mapStats($data),

            authorStats: $this->mapAuthorStats($data),
        );
    }


    private function mapVideo(array $data): VideoData
    {
        return new VideoData(

            id: (string) $data['id'],

            duration: (int) ($data['duration'] ?? 0),

            width: (int) ($data['width'] ?? 0),

            height: (int) ($data['height'] ?? 0),

            ratio: $this->calculateRatio(
                $data['width'] ?? 0,
                $data['height'] ?? 0
            ),

            cover: $data['thumbnail'] ?? '',

            originCover: $data['thumbnail'] ?? '',

            dynamicCover: $data['thumbnail'] ?? '',


            /*
             * Primary playable URL.
             * yt-dlp already selects the best requested download.
             */
            playAddr: $data['url'] ?? '',

            downloadAddr: $data['url'] ?? '',


            /*
             * Keep compatibility with your old TikTok parser.
             */
            playUrls: [],


            /*
             * Convert yt-dlp formats into DTO objects.
             */
            formats: $this->mapFormats(
                $data['formats'] ?? []
            ),


            headers: $data['http_headers'] ?? [],


            cookies: $data['cookies'] ?? null,
        );
    }


    /**
     * @return VideoFormatData[]
     */
    private function mapFormats(array $formats): array
    {
        return array_map(
            function (array $format) {

                return new VideoFormatData(

                    formatId: (string) ($format['format_id'] ?? ''),

                    url: (string) ($format['url'] ?? ''),

                    extension: (string) ($format['ext'] ?? 'mp4'),

                    videoCodec: $format['vcodec'] ?? null,

                    audioCodec: $format['acodec'] ?? null,

                    width: $format['width'] ?? null,

                    height: $format['height'] ?? null,

                    filesize: $format['filesize'] ?? null,

                    bitrate: $format['tbr'] ?? null,

                    qualityLabel: $format['format'] ?? null,
                );
            },
            $formats
        );
    }


    private function calculateRatio(
        int $width,
        int $height
    ): string {

        if ($width === 0 || $height === 0) {
            return '';
        }

        return round($width / $height, 2) . '';
    }


    private function mapAuthor(array $data)
    {
        // Next step
    }


    private function mapMusic(array $data)
    {
        // Next step
    }


    private function mapStats(array $data)
    {
        // Next step
    }


    private function mapAuthorStats(array $data)
    {
        // Next step
    }
}