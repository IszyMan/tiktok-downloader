<?php

namespace App\Services\Downloader\Scrapers\TikTok\YtDlp;

use RuntimeException;

class YtDlpProvider
{
    public function __construct(
        private readonly YtDlpService $ytDlp,
    ) {
    }

    public function fetch(string $url): array
    {
        $data = $this->ytDlp->getVideoData($url);

        $download = $data['requested_downloads'][0] ?? null;

        $domain = 'domain'; // your brand name

        if (! $download) {
            throw new RuntimeException(
                'yt-dlp returned no downloadable format.'
            );
        }

        $selectedFormat = $this->selectBestH264Format(
            $data['formats'] ?? []
        );

        return [

            'id' => $data['id'] ?? null,

            'title' => $data['title'] ?? 'video',

            'description' => $data['description'] ?? '',            

            'filename' => sprintf(
                '%s-%s.mp4',
                $domain,
                $data['id'] ?? time()
            ),

            'width' => $selectedFormat['width']
                ?? $download['width']
                ?? null,

            'height' => $selectedFormat['height']
                ?? $download['height']
                ?? null,

            'duration' => $data['duration'] ?? null,

            'thumbnail' => $data['thumbnail'] ?? null,

            'format' => $selectedFormat['format']
                ?? $download['format']
                ?? null,

            'format_id' => $selectedFormat['format_id']
                ?? null,
        ];
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
                    ($format['tbr'] ?? 0) > ($best['tbr'] ?? 0)
                )
            ) {
                $best = $format;
            }
        }

        return $best;
    }
}