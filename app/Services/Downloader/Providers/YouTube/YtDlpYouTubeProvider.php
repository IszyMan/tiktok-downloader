<?php

namespace App\Services\Downloader\Providers\YouTube;

use RuntimeException;

use App\Services\Downloader\DTO\AuthorData;
use App\Services\Downloader\DTO\MediaData;
use App\Services\Downloader\DTO\DownloadData;
use App\Services\Downloader\DTO\StatisticsData;
use App\Services\Downloader\DTO\YouTubeVideoData;

use App\Services\Downloader\Scrapers\YouTube\YtDlp\YtDlpYouTubeService;

class YtDlpYouTubeProvider implements YouTubeProviderContract
{
    public function __construct(
        private readonly YtDlpYouTubeService $ytDlp,
    ) {
    }

    public function fetch(string $url): YouTubeVideoData
    {
        $data = $this->ytDlp->getVideoData($url);

        $formats = $data['formats'] ?? [];

        $selectedFormat = $this->selectBestVideoFormat(
            $formats
        );

        if (! $selectedFormat) {
            throw new RuntimeException(
                'yt-dlp returned no downloadable YouTube video format.'
            );
        }

        return new YouTubeVideoData(

            id: (string) (
                $data['id']
                ?? ''
            ),

            title: $data['title']
                ?? 'YouTube video',

            description: $data['description']
                ?? '',

            duration: isset($data['duration'])
                ? (float) $data['duration']
                : null,

            width: isset($selectedFormat['width'])
                ? (int) $selectedFormat['width']
                : null,

            height: isset($selectedFormat['height'])
                ? (int) $selectedFormat['height']
                : null,

            author: new AuthorData(
                id: $data['channel_id']
                    ?? $data['uploader_id']
                    ?? null,

                username: $data['channel']
                    ?? $data['uploader']
                    ?? null,

                nickname: $data['channel']
                    ?? $data['uploader']
                    ?? null,

                avatar: $data['thumbnail']
                    ?? null,
            ),

            media: new MediaData(
                thumbnail: $data['thumbnail']
                    ?? null,

                cover: $data['thumbnail']
                    ?? null,
            ),

            downloads: new DownloadData(
                playUrl: $selectedFormat['url']
                    ?? null,

                hdPlayUrl: $selectedFormat['url']
                    ?? null,

                watermarkUrl: null,

                musicUrl: null,

                playSize: $selectedFormat['filesize']
                    ?? $selectedFormat['filesize_approx']
                    ?? null,

                hdSize: $selectedFormat['filesize']
                    ?? $selectedFormat['filesize_approx']
                    ?? null,

                watermarkSize: null,

                format: 'mp4',

                formatId: $selectedFormat['format_id']
                    ?? null,
            ),

            statistics: new StatisticsData(
                views: $data['view_count']
                    ?? null,

                likes: $data['like_count']
                    ?? null,

                comments: $data['comment_count']
                    ?? null,

                shares: null,

                downloads: null,

                favorites: null,
            ),

            provider: 'ytdlp-youtube',

            sourceUrl: $url,

            filename: sprintf(
                'youtube-%s.mp4',
                $data['id']
                    ?? time()
            ),

            extra: $data,
        );
    }

    /**
     * Select the best available video format.
     */
    private function selectBestVideoFormat(
        array $formats
    ): ?array {

        $best = null;

        foreach ($formats as $format) {

            $videoExt = strtolower(
                $format['video_ext']
                ?? ''
            );

            $ext = strtolower(
                $format['ext']
                ?? ''
            );

            $vcodec = strtolower(
                $format['vcodec']
                ?? ''
            );

            $width = (int) (
                $format['width']
                ?? 0
            );

            $height = (int) (
                $format['height']
                ?? 0
            );

            $tbr = (float) (
                $format['tbr']
                ?? 0
            );

            /*
            |--------------------------------------------------------------------------
            | Must contain video.
            |--------------------------------------------------------------------------
            */

            if (
                $videoExt === '' ||
                $videoExt === 'none'
            ) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Must have dimensions.
            |--------------------------------------------------------------------------
            */

            if (
                $width <= 0 ||
                $height <= 0
            ) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Must have a video codec.
            |--------------------------------------------------------------------------
            */

            if (
                $vcodec === '' ||
                $vcodec === 'none'
            ) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Prefer MP4 formats.
            |--------------------------------------------------------------------------
            */

            if (
                $ext !== 'mp4' &&
                $videoExt !== 'mp4'
            ) {
                continue;
            }

            if ($best === null) {
                $best = $format;
                continue;
            }

            $bestHeight = (int) (
                $best['height']
                ?? 0
            );

            $bestTbr = (float) (
                $best['tbr']
                ?? 0
            );

            if (
                $height > $bestHeight
                ||
                (
                    $height === $bestHeight
                    &&
                    $tbr > $bestTbr
                )
            ) {
                $best = $format;
            }
        }

        return $best;
    }
}