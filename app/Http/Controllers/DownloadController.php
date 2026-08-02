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

        $action = $request->input('action', 'preview');

        $video = $this->provider->fetch($validated['url']);

        // Preview
        if ($action === 'preview') {

            return view('home', [
                'video' => $video,
                'url'   => $validated['url'],
            ]);
        }

        // Download
        if (in_array($action, ['hd', 'watermark'])) {

            $temp = storage_path(
                'app/temp/' . $video->filename
            );

            $this->downloader->download(
                $video,
                $temp,
                $action,
            );

            return response()
                ->download($temp, $video->filename)
                ->deleteFileAfterSend(true);
        }

        return back();
    }
}