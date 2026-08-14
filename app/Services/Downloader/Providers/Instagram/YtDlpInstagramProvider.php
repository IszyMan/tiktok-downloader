<?php

namespace App\Services\Downloader\Providers\Instagram;

use RuntimeException;
use App\Services\Downloader\DTO\AuthorData;
use App\Services\Downloader\DTO\MediaData;
use App\Services\Downloader\DTO\DownloadData;
use App\Services\Downloader\DTO\StatisticsData;
use App\Services\Downloader\DTO\InstagramVideoData;
use App\Services\Downloader\Scrapers\Instagram\YtDlp\YtDlpInstagramService;

class YtDlpInstagramProvider implements InstagramProviderContract
{
    public function __construct(
        private readonly YtDlpInstagramService $ytDlp,
    ) {
    }

    public function fetch(string $url): InstagramVideoData
    {
        $data = $this->ytDlp->getVideoData($url);

        $formats = $data['formats'] ?? [];

        $selectedFormat = $this->selectBestVideoFormat(
            $formats
        );

        if (! $selectedFormat) {
            throw new RuntimeException(
                'yt-dlp returned no downloadable Instagram video format.'
            );
        }

        return new InstagramVideoData(

            id: (string) ($data['id'] ?? ''),

            title: $data['title']
                ?? $data['description']
                ?? 'Instagram video',

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

            downloads: new DownloadData(
                playUrl: $selectedFormat['url'] ?? null,

                hdPlayUrl: $selectedFormat['url'] ?? null,

                watermarkUrl: null,

                musicUrl: null,

                playSize: $selectedFormat['filesize']
                    ?? $selectedFormat['filesize_approx']
                    ?? null,

                hdSize: $selectedFormat['filesize']
                    ?? $selectedFormat['filesize_approx']
                    ?? null,

                watermarkSize: null,

                format: $selectedFormat['ext'] ?? 'mp4',

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

            provider: 'ytdlp-instagram',

            sourceUrl: $url,

            filename: sprintf(
                'instagram-%s.mp4',
                $data['id'] ?? time()
            ),

            extra: $data,
        );
    }

    /**
     * Select the highest-quality video-only HTTP format.
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