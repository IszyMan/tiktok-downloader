<?php

namespace App\Services\Downloader\Scrapers\Facebook\YtDlp;

use RuntimeException;

class YtDlpFacebookService
{
    private const MAX_ATTEMPTS = 3;

    /**
     * Get Facebook video metadata from yt-dlp.
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
     * Execute yt-dlp once.
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
     * Download a Facebook video.
     *
     * Facebook may provide AV1 video + AAC audio.
     *
     * yt-dlp first downloads and merges the streams.
     * FFmpeg then converts the result to:
     *
     * H.264 video + AAC audio + yuv420p.
     */
    public function downloadVideo(
        string $url,
        string $outputPath
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

        /*
        |--------------------------------------------------------------------------
        | Temporary source file
        |--------------------------------------------------------------------------
        */

        $temporaryPath = $outputPath . '.source.mp4';

        if (file_exists($temporaryPath)) {
            @unlink($temporaryPath);
        }

        /*
        |--------------------------------------------------------------------------
        | Download + merge with yt-dlp
        |--------------------------------------------------------------------------
        */

        $exe = escapeshellarg($binary);

        $ffmpegDirectory = escapeshellarg(
            dirname($ffmpeg)
        );

        $command = sprintf(
            '%s '
            . '--no-playlist '
            . '--no-progress '
            . '--ffmpeg-location %s '
            . '-f %s '
            . '--merge-output-format mp4 '
            . '--force-overwrites '
            . '-o %s '
            . '%s 2>&1',

            $exe,

            $ffmpegDirectory,

            escapeshellarg('bv*+ba/b'),

            escapeshellarg($temporaryPath),

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

            @unlink($temporaryPath);

            throw new RuntimeException(
                implode(PHP_EOL, $output)
            );
        }

        if (
            ! file_exists($temporaryPath) ||
            filesize($temporaryPath) === 0
        ) {

            @unlink($temporaryPath);

            throw new RuntimeException(
                'yt-dlp finished successfully but no source video was created.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Convert AV1 → H.264
        |--------------------------------------------------------------------------
        */

        $ffmpegExe = escapeshellarg($ffmpeg);

        $command = sprintf(
            '%s '
            . '-y '
            . '-i %s '
            . '-map 0:v:0 '
            . '-map 0:a:0? '
            . '-c:v libx264 '
            . '-preset medium '
            . '-crf 23 '
            . '-c:a aac '
            . '-b:a 128k '
            . '-pix_fmt yuv420p '
            . '-movflags +faststart '
            . '%s 2>&1',

            $ffmpegExe,

            escapeshellarg($temporaryPath),

            escapeshellarg($outputPath)
        );

        $output = [];

        $exitCode = 0;

        exec(
            $command,
            $output,
            $exitCode
        );

        /*
        |--------------------------------------------------------------------------
        | Delete temporary AV1 file
        |--------------------------------------------------------------------------
        */

        @unlink($temporaryPath);

        if ($exitCode !== 0) {

            @unlink($outputPath);

            throw new RuntimeException(
                "FFmpeg Facebook conversion failed:\n\n"
                . implode(PHP_EOL, $output)
            );
        }

        if (
            ! file_exists($outputPath) ||
            filesize($outputPath) === 0
        ) {

            @unlink($outputPath);

            throw new RuntimeException(
                'FFmpeg finished successfully but no Facebook output file was created.'
            );
        }
    }
}