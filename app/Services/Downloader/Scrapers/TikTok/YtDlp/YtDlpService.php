<?php

namespace App\Services\Downloader\Scrapers\TikTok\YtDlp;

use RuntimeException;

class YtDlpService
{
    private const MAX_ATTEMPTS = 3;

    /**
     * Get video metadata.
     */
    public function getVideoData(string $url): array
    {
        $json = $this->execute($url);

        return json_decode(
            $json,
            true,
            512,
            JSON_THROW_ON_ERROR
        );
    }

    /**
     * Execute yt-dlp metadata extraction with retries.
     */
    public function execute(string $url): string
    {
        $lastError = 'Unknown yt-dlp error.';

        for ($attempt = 1; $attempt <= self::MAX_ATTEMPTS; $attempt++) {

            [$exitCode, $output] = $this->executeCommand($url);

            if ($exitCode === 0) {
                return $output;
            }

            $lastError = $output;

            sleep(1);
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
        $exe = escapeshellarg(
            base_path('bin/yt-dlp.exe')
        );

        $command = sprintf(
            '%s --skip-download -J %s 2>&1',
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
     * Download the video.
     *
     * If a format ID is supplied, yt-dlp downloads exactly that format.
     * Otherwise, yt-dlp chooses its default "best" format.
     */
    public function downloadVideo(
        string $url,
        string $outputPath,
        ?string $formatId = null
    ): void {

        $exe = escapeshellarg(
            base_path('bin/yt-dlp.exe')
        );

        $command = sprintf(
            '%s --no-playlist --no-progress %s -o %s %s 2>&1',
            $exe,
            $formatId
                ? '-f '.escapeshellarg($formatId)
                : '',
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

        if (! file_exists($outputPath)) {
            throw new RuntimeException(
                'yt-dlp finished successfully but no output file was created.'
            );
        }
    }
}