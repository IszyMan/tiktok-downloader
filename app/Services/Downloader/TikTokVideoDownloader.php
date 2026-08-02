<?php

namespace App\Services\Downloader;

use RuntimeException;
use Illuminate\Support\Facades\Http;
use App\Services\Downloader\DTO\TikTokVideoData;
use App\Services\Downloader\Scrapers\TikTok\YtDlp\YtDlpService;


class TikTokVideoDownloader
{
    public function __construct(
        private readonly YtDlpService $ytDlp,
    ) {
    }

    /**
     * Download a TikTok video using the provider that produced the metadata.
     */
    public function download(
        TikTokVideoData $video,
        string $outputPath,
        string $type = 'hd',
    ): void {

        match ($video->provider) {

            'tikwm' => $this->downloadFromTikWM(
                $video,
                $outputPath,
                $type,
            ),

            'ytdlp' => $this->downloadWithYtDlp(
                $video,
                $outputPath,
            ),

            default => throw new RuntimeException(
                "Unsupported provider [{$video->provider}]"
            ),
        };

        if (
            ! file_exists($outputPath) ||
            filesize($outputPath) === 0
        ) {
            throw new RuntimeException(
                'Downloaded file is empty.'
            );
        }
    }

    /**
     * Download directly from TikWM.
     */
    private function downloadFromTikWM(
        TikTokVideoData $video,
        string $outputPath,
        string $type,
    ): void {

        $url = $type === 'hd'
            ? ($video->downloads->hdPlayUrl
                ?? $video->downloads->playUrl)
            : $video->downloads->watermarkUrl;

        if (! $url) {
            throw new RuntimeException(
                'TikWM returned no downloadable URL.'
            );
        }

        

        $response = Http::timeout(120)
            ->sink($outputPath)
            ->get($url);


        if (! $response->successful()) {
            throw new RuntimeException(
                'TikWM download request failed.'
            );
        }
    }

    /**
     * Download using yt-dlp.
     */
    private function downloadWithYtDlp(
        TikTokVideoData $video,
        string $outputPath,
    ): void {

        $this->ytDlp->downloadVideo(
            url: $video->sourceUrl,
            outputPath: $outputPath,
            formatId: $video->downloads->formatId,
        );
    }
}