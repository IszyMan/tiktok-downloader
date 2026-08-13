<?php

namespace App\Services\Downloader\Scrapers\YouTube\YtDlp;

use RuntimeException;
use Symfony\Component\Process\Process;

class YtDlpYouTubeService
{
    /**
     * Return the yt-dlp executable path.
     */
    private function binary(): string
    {
        $binary = config('services.ytdlp.binary');

        if (! $binary || ! file_exists($binary)) {
            throw new RuntimeException(
                'yt-dlp executable was not found.'
            );
        }

        return $binary;
    }

    /**
     * Extract YouTube video metadata and available formats.
     */
    public function getVideoData(string $url): array
    {
        $process = new Process([
            $this->binary(),

            '--dump-single-json',

            '--no-warnings',

            '--no-playlist',

            '--skip-download',

            $url,
        ]);

        /*
        |--------------------------------------------------------------------------
        | YouTube currently benefits from an external JS runtime.
        |--------------------------------------------------------------------------
        |
        | Node 22+ is supported by current yt-dlp.
        |
        */

        $process->setTimeout(120);

        $process->run();

        if (! $process->isSuccessful()) {
            throw new RuntimeException(
                trim($process->getErrorOutput())
                ?: 'yt-dlp could not extract the YouTube video.'
            );
        }

        $output = trim($process->getOutput());

        if ($output === '') {
            throw new RuntimeException(
                'yt-dlp returned empty YouTube metadata.'
            );
        }

        $data = json_decode(
            $output,
            true
        );

        if (! is_array($data)) {
            throw new RuntimeException(
                'yt-dlp returned invalid YouTube metadata.'
            );
        }

        return $data;
    }

    /**
     * Download the best available YouTube video.
     *
     * This selects:
     *
     * best video + best audio
     *
     * and lets FFmpeg merge them into MP4.
     */
    public function downloadVideo(
        string $url,
        string $outputPath,
    ): void {

        $process = new Process([
            $this->binary(),

            '--no-playlist',

            '-f',
            'bv*+ba/b',

            '--merge-output-format',
            'mp4',

            '-o',
            $outputPath,

            $url,
        ]);

        $process->setTimeout(600);

        $process->run();

        if (! $process->isSuccessful()) {
            throw new RuntimeException(
                trim($process->getErrorOutput())
                ?: 'yt-dlp could not download the YouTube video.'
            );
        }
    }

    /**
     * Download YouTube audio and convert it to MP3.
     */
    public function downloadMp3(
        string $url,
        string $outputPath,
    ): void {

        $process = new Process([
            $this->binary(),

            '--no-playlist',

            '-f',
            'ba/b',

            '-x',

            '--audio-format',
            'mp3',

            '--audio-quality',
            '0',

            '-o',
            $outputPath,

            $url,
        ]);

        $process->setTimeout(600);

        $process->run();

        if (! $process->isSuccessful()) {
            throw new RuntimeException(
                trim($process->getErrorOutput())
                ?: 'yt-dlp could not extract YouTube audio.'
            );
        }
    }
}