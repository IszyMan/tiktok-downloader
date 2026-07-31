<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Downloader\Scrapers\TikTok\YtDlp\YtDlpProvider;

class DownloadController extends Controller
{
    public function __construct(
        private readonly YtDlpProvider $provider,
    ) {
    }

    public function download(Request $request)
    {
        $validated = $request->validate([
            'url' => ['required', 'url'],
        ]);

        $url = $validated['url'];

        // Retrieve video metadata and the preferred download format.
        $video = $this->provider->fetch($url);

        $directory = storage_path('app/temp');

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $tempPath = $directory . DIRECTORY_SEPARATOR . $video['filename'];

        // Download the selected format (preferably H.264).
        $this->provider->download(
            url: $url,
            outputPath: $tempPath,
            formatId: $video['format_id'],
        );

        if (! file_exists($tempPath) || filesize($tempPath) === 0) {
            abort(500, 'Video download failed.');
        }

        return response()->download(
            file: $tempPath,
            name: $video['filename'],
            headers: [
                'Content-Type' => 'video/mp4',
            ]
        )->deleteFileAfterSend(true);
    }
}