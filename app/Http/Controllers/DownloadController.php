<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Downloader\TikTokVideoDownloader;
use App\Services\Downloader\Providers\TikTok\TikTokProviderManager;

class DownloadController extends Controller
{
    public function __construct(
        private readonly TikTokProviderManager $provider,
        private readonly TikTokVideoDownloader $downloader,
    ) {
    }

    public function download(Request $request)
    {
        $validated = $request->validate([
            'url' => ['required', 'url'],
        ]);

        $video = $this->provider->fetch(
            $validated['url']
        );

        $directory = storage_path('app/temp');

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $tempPath = $directory
            . DIRECTORY_SEPARATOR
            . $video->filename;

        $this->downloader->download(
            $video,
            $tempPath
        );

        return response()->download(
            file: $tempPath,
            name: $video->filename,
            headers: [
                'Content-Type' => 'video/mp4',
            ]
        )->deleteFileAfterSend(true);
    }
}