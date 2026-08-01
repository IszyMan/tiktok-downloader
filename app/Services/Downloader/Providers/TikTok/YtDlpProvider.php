<?php

namespace App\Services\Downloader\Providers\TikTok;


use RuntimeException;
use App\Services\Downloader\DTO\AuthorData;
use App\Services\Downloader\DTO\MediaData;
use App\Services\Downloader\DTO\DownloadData;
use App\Services\Downloader\DTO\StatisticsData;
use App\Services\Downloader\DTO\TikTokVideoData;
use App\Services\Downloader\Scrapers\TikTok\YtDlp\YtDlpService;

class YtDlpProvider implements TikTokProviderContract
{
    public function __construct(
        private readonly YtDlpService $ytDlp,
    ) {
    }

    public function fetch(string $url): TikTokVideoData
    {
        $data = $this->ytDlp->getVideoData($url);

        $download = $data['requested_downloads'][0] ?? null;

        if (! $download) {
            throw new RuntimeException(
                'yt-dlp returned no downloadable format.'
            );
        }

        $selectedFormat = $this->selectBestH264Format(
            $data['formats'] ?? []
        );

        return new TikTokVideoData(

            id: (string) ($data['id'] ?? ''),

            title: $data['title'] ?? 'video',

            description: $data['description'] ?? '',

            duration: $data['duration'] ?? null,

            width: $selectedFormat['width']
                ?? $download['width']
                ?? null,

            height: $selectedFormat['height']
                ?? $download['height']
                ?? null,

            author: new AuthorData(

                id: $data['uploader_id'] ?? null,

                username: $data['uploader'] ?? null,

                nickname: $data['channel'] ?? null,

                avatar: null,

            ),

            media: new MediaData(

                thumbnail: $data['thumbnail'] ?? null,

                cover: null,

                musicUrl: null,

            ),

            downloads: new DownloadData(

                playUrl: null,

                hdPlayUrl: null,

                watermarkUrl: null,

                format: $selectedFormat['format']
                    ?? $download['format']
                    ?? null,

                formatId: $selectedFormat['format_id']
                    ?? null,

            ),

            statistics: new StatisticsData(

                views: $data['view_count'] ?? null,

                likes: $data['like_count'] ?? null,

                comments: $data['comment_count'] ?? null,

                shares: $data['repost_count'] ?? null,

            ),

            provider: 'yt-dlp',

            sourceUrl: $url,

            filename: sprintf(
                '%s-%s.mp4',
                config('app.name'),
                $data['id'] ?? time()
            ),

            extra: $data,
        );
    }

    /**
     * Download the selected format.
     */
    public function download(
        string $url,
        string $outputPath,
        ?string $formatId = null
    ): void {
        $this->ytDlp->downloadVideo(
            $url,
            $outputPath,
            $formatId
        );
    }

    /**
     * Pick the best H.264 format.
     * Falls back to null if none is found.
     */
    private function selectBestH264Format(array $formats): ?array
    {
        $best = null;

        foreach ($formats as $format) {

            $codec = strtolower(
                $format['vcodec'] ?? ''
            );

            // Ignore audio-only formats
            if (
                empty($codec) ||
                $codec === 'none'
            ) {
                continue;
            }

            // Accept AVC/H264 only
            if (
                ! str_contains($codec, 'h264') &&
                ! str_contains($codec, 'avc')
            ) {
                continue;
            }

            if (
                $best === null ||
                (($format['height'] ?? 0) > ($best['height'] ?? 0)) ||
                (
                    ($format['height'] ?? 0) === ($best['height'] ?? 0) &&
                    (($format['tbr'] ?? 0) > ($best['tbr'] ?? 0))
                )
            ) {
                $best = $format;
            }
        }

        return $best;
    }
}