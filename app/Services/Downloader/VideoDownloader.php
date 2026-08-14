<?php

namespace App\Services\Downloader;

use RuntimeException;
use Illuminate\Support\Facades\Http;
use App\Services\Downloader\DTO\VideoData;
use App\Services\Downloader\Scrapers\TikTok\YtDlp\YtDlpService;
use App\Services\Downloader\Scrapers\X\YtDlp\YtDlpXService;
use App\Services\Downloader\Scrapers\YouTube\YtDlp\YtDlpYouTubeService;
use App\Services\Downloader\Scrapers\Instagram\YtDlp\YtDlpInstagramService;

class VideoDownloader
{
    public function __construct(
        private readonly YtDlpService $tikTokYtDlp,
        private readonly YtDlpXService $xYtDlp,
        private readonly YtDlpYouTubeService $youtubeYtDlp,
        private readonly YtDlpInstagramService $instagramYtDlp,
    ) {
    }

    /**
     * Download a video using the provider that produced its metadata.
     */
    public function download(
        VideoData $video,
        string $outputPath,
        string $type = 'hd',
    ): void {

        match ($video->provider) {

            'tikwm' => $this->downloadFromTikWM(
                $video,
                $outputPath,
                $type,
            ),

            'ytdlp' => $this->downloadWithTikTokYtDlp(
                $video,
                $outputPath,
            ),

            'ytdlp-x' => $this->downloadWithXYtDlp(
                $video,
                $outputPath,
            ),

            'ytdlp-youtube' => $this->downloadWithYouTubeYtDlp(
                $video,
                $outputPath,
                $type,
            ),

            'ytdlp-instagram' => $this->downloadWithInstagramYtDlp(
                $video,
                $outputPath,
                $type,
            ),

            default => throw new RuntimeException(
                "Unsupported provider [{$video->provider}]"
            ),
        };

        $this->validateDownloadedFile($outputPath);
    }

    /**
     * Download directly from TikWM.
     */
    private function downloadFromTikWM(
        VideoData $video,
        string $outputPath,
        string $type,
    ): void {

        $url = $type === 'hd'
            ? (
                $video->downloads->hdPlayUrl
                ?? $video->downloads->playUrl
            )
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
     * Download TikTok using yt-dlp.
     */
    private function downloadWithTikTokYtDlp(
        VideoData $video,
        string $outputPath,
    ): void {

        $this->tikTokYtDlp->downloadVideo(
            url: $video->sourceUrl,
            outputPath: $outputPath,
            formatId: $video->downloads->formatId,
        );
    }

    /**
     * Download X using yt-dlp.
     */
    private function downloadWithXYtDlp(
        VideoData $video,
        string $outputPath,
    ): void {

        $this->xYtDlp->downloadVideo(
            url: $video->sourceUrl,
            outputPath: $outputPath,
            formatId: null,
        );
    }


    /**
     * Download YouTube video or MP3 using yt-dlp.
     */
    private function downloadWithYouTubeYtDlp(
        VideoData $video,
        string $outputPath,
        string $type,
    ): void {

        if ($type === 'mp3') {

            $this->youtubeYtDlp->downloadMp3(
                url: $video->sourceUrl,
                outputPath: $outputPath,
            );

            return;
        }

        $this->youtubeYtDlp->downloadVideo(
            url: $video->sourceUrl,
            outputPath: $outputPath,
        );
    }


    /**
     * Download Instagram using yt-dlp.
     */
    private function downloadWithInstagramYtDlp(
        VideoData $video,
        string $outputPath,
        string $type,
    ): void {

        if ($type === 'mp3') {

            $this->instagramYtDlp->downloadMp3(
                url: $video->sourceUrl,
                outputPath: $outputPath,
            );

            return;
        }

        $this->instagramYtDlp->downloadVideo(
            url: $video->sourceUrl,
            outputPath: $outputPath,
            formatId: $video->downloads->formatId,
        );
    }

    /**
     * Make sure the downloader actually produced a file.
     */
    private function validateDownloadedFile(
        string $outputPath
    ): void {

        if (
            ! file_exists($outputPath) ||
            filesize($outputPath) === 0
        ) {
            throw new RuntimeException(
                'Downloaded file is empty.'
            );
        }
    }
}