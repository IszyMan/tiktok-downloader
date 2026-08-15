<?php

namespace App\Services\Downloader\Providers\Facebook;

use RuntimeException;
use App\Services\Downloader\DTO\AuthorData;
use App\Services\Downloader\DTO\MediaData;
use App\Services\Downloader\DTO\DownloadData;
use App\Services\Downloader\DTO\StatisticsData;
use App\Services\Downloader\DTO\FacebookVideoData;
use App\Services\Downloader\Scrapers\Facebook\YtDlp\YtDlpFacebookService;

class YtDlpFacebookProvider implements FacebookProviderContract
{
    public function __construct(
        private readonly YtDlpFacebookService $ytDlp,
    ) {
    }

    public function fetch(string $url): FacebookVideoData
    {
        $data = $this->ytDlp->getVideoData($url);

        $formats = $data['formats'] ?? [];

        /*
        |--------------------------------------------------------------------------
        | Select the best video format only for metadata.
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | Facebook frequently exposes video and audio as separate DASH
        | streams. Therefore this URL must NOT be treated as the final
        | downloadable video.
        |
        | Actual downloads are handled by YtDlpFacebookService::downloadVideo()
        | which downloads video + audio and lets FFmpeg merge them.
        |
        |--------------------------------------------------------------------------
        */

        $selectedFormat = $this->selectBestVideoFormat(
            $formats
        );

        if (! $selectedFormat) {
            throw new RuntimeException(
                'yt-dlp returned no downloadable Facebook video format.'
            );
        }

        return new FacebookVideoData(

            id: (string) ($data['id'] ?? ''),

            title: $data['title']
                ?? $data['description']
                ?? 'Facebook video',

            description: $data['description'] ?? '',

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
                id: $data['uploader_id']
                    ?? $data['channel_id']
                    ?? null,

                username: $data['uploader']
                    ?? $data['channel']
                    ?? null,

                nickname: $data['uploader']
                    ?? $data['channel']
                    ?? null,

                avatar: $data['thumbnail'] ?? null,
            ),

            media: new MediaData(
                thumbnail: $data['thumbnail'] ?? null,
                cover: $data['thumbnail'] ?? null,
            ),

            /*
            |--------------------------------------------------------------------------
            | Facebook downloads
            |--------------------------------------------------------------------------
            |
            | Do NOT put the video-only DASH URL here.
            |
            | VideoDownloader sees the provider:
            |
            |     ytdlp-facebook
            |
            | and calls:
            |
            |     YtDlpFacebookService::downloadVideo()
            |
            | which downloads video + audio and merges them with FFmpeg.
            |
            |--------------------------------------------------------------------------
            */

            downloads: new DownloadData(

                playUrl: null,

                hdPlayUrl: null,

                watermarkUrl: null,

                musicUrl: null,

                playSize: null,

                hdSize: null,

                watermarkSize: null,

                format: 'mp4',

                formatId: null,
            ),

            statistics: new StatisticsData(
                views: $data['view_count'] ?? null,

                likes: $data['like_count'] ?? null,

                comments: $data['comment_count'] ?? null,

                shares: $data['repost_count'] ?? null,

                downloads: null,

                favorites: $data['favorite_count'] ?? null,
            ),

            provider: 'ytdlp-facebook',

            sourceUrl: $url,

            filename: sprintf(
                'facebook-%s.mp4',
                $data['id'] ?? time()
            ),

            extra: $data,
        );
    }

    /**
     * Select the highest-quality video format.
     *
     * This is used ONLY for metadata such as width and height.
     *
     * The returned URL must NOT be used as the final Facebook
     * downloadable video because Facebook may provide video and
     * audio as separate DASH streams.
     */
    private function selectBestVideoFormat(
        array $formats
    ): ?array {

        $best = null;

        foreach ($formats as $format) {

            $videoExt = strtolower(
                $format['video_ext'] ?? ''
            );

            $ext = strtolower(
                $format['ext'] ?? ''
            );

            $vcodec = strtolower(
                $format['vcodec'] ?? ''
            );

            $width = (int) ($format['width'] ?? 0);

            $height = (int) ($format['height'] ?? 0);

            $tbr = (float) ($format['tbr'] ?? 0);

            if (
                $videoExt === '' ||
                $videoExt === 'none'
            ) {
                continue;
            }

            if (
                $width <= 0 ||
                $height <= 0
            ) {
                continue;
            }

            if (
                $vcodec === '' ||
                $vcodec === 'none'
            ) {
                continue;
            }

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
                $best['height'] ?? 0
            );

            $bestTbr = (float) (
                $best['tbr'] ?? 0
            );

            if (
                $height > $bestHeight ||
                (
                    $height === $bestHeight &&
                    $tbr > $bestTbr
                )
            ) {
                $best = $format;
            }
        }

        return $best;
    }
}