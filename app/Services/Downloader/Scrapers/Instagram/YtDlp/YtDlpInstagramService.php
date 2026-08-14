<?php

namespace App\Services\Downloader\Scrapers\Instagram\YtDlp;

use RuntimeException;

class YtDlpInstagramService
{
    private const MAX_ATTEMPTS = 3;

    /**
     * Get Instagram video metadata from yt-dlp.
     */
    public function getVideoData(string $url): array
    {
        $json = $this->execute($url);

        try {
            return json_decode(
                $json,
                true,
                512,
                JSON_THROW_ON_ERROR
            );
        } catch (\JsonException $e) {
            throw new RuntimeException(
                "yt-dlp returned invalid JSON.\n\n"
                . "Raw output:\n"
                . $json,
                previous: $e
            );
        }
    }

    /**
     * Execute yt-dlp metadata extraction with retries.
     */
    public function execute(string $url): string
    {
        $lastError = 'Unknown yt-dlp error.';

        for (
            $attempt = 1;
            $attempt <= self::MAX_ATTEMPTS;
            $attempt++
        ) {
            [$exitCode, $output] = $this->executeCommand($url);

            if ($exitCode === 0) {
                return trim($output);
            }

            $lastError = $output;

            if ($attempt < self::MAX_ATTEMPTS) {
                sleep(1);
            }
        }

        throw new RuntimeException($lastError);
    }

    /**
     * Execute yt-dlp metadata extraction once.
     *
     * @return array{0:int,1:string}
     */
    private function executeCommand(string $url): array
    {
        $binary = config('services.ytdlp.binary');

        if (! $binary) {
            throw new RuntimeException(
                'YTDLP_BINARY is not configured.'
            );
        }

        if (! file_exists($binary)) {
            throw new RuntimeException(
                "yt-dlp binary not found at: {$binary}"
            );
        }

        $exe = escapeshellarg($binary);

        $command = sprintf(
            '%s --no-playlist --skip-download -J --no-warnings %s 2>&1',
            $exe,
            escapeshellarg($url)
        );

        $output = [];

        $exitCode = 0;

        exec(
            $command,
            $output,
            $exitCode
        );

        return [
            $exitCode,
            implode(PHP_EOL, $output),
        ];
    }

    /**
     * Download the best available Instagram video
     * together with the best available audio.
     *
     * yt-dlp selects the streams and FFmpeg merges them
     * into the final output file.
     */
    public function downloadVideo(
        string $url,
        string $outputPath,
        ?string $formatId = null
    ): void {

        $binary = config('services.ytdlp.binary');

        if (! $binary) {
            throw new RuntimeException(
                'YTDLP_BINARY is not configured.'
            );
        }

        if (! file_exists($binary)) {
            throw new RuntimeException(
                "yt-dlp binary not found at: {$binary}"
            );
        }

        $ffmpeg = config('services.ytdlp.ffmpeg');

        if (! $ffmpeg || ! file_exists($ffmpeg)) {
            throw new RuntimeException(
                'FFmpeg binary not found.'
            );
        }

        $exe = escapeshellarg($binary);

        $ffmpegPath = escapeshellarg(
            dirname($ffmpeg)
        );

        /*
         * Always let yt-dlp select the best video and
         * best audio independently.
         *
         * bv = best video-only stream
         * ba = best audio-only stream
         * /b = fallback to best combined format
         */
        $formatOption = '-f ' . escapeshellarg(
            'bv*+ba/b'
        );

        $command = sprintf(
            '%s --no-playlist --no-progress --ffmpeg-location %s %s -o %s %s 2>&1',
            $exe,
            $ffmpegPath,
            $formatOption,
            escapeshellarg($outputPath),
            escapeshellarg($url)
        );

        $output = [];

        $exitCode = 0;

        exec(
            $command,
            $output,
            $exitCode
        );

        if ($exitCode !== 0) {
            throw new RuntimeException(
                implode(PHP_EOL, $output)
            );
        }

        if (
            ! file_exists($outputPath) ||
            filesize($outputPath) === 0
        ) {
            throw new RuntimeException(
                'yt-dlp finished successfully but no output file was created.'
            );
        }
    }
}