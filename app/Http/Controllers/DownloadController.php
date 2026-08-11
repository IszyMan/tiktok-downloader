<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Downloader\VideoDownloader;
use App\Services\Downloader\Providers\VideoProviderManager;

class DownloadController extends Controller
{
    public function __construct(
        private readonly VideoProviderManager $provider,
        private readonly VideoDownloader $downloader,
    ) {
    }

    public function download(Request $request)
    {
        $validated = $request->validate([
            'url' => ['required', 'url'],
        ]);

        $action = $request->input('action', 'preview');

        /*
         * Fetch metadata from either TikTok or X.
         */
        $video = $this->provider->fetch(
            $validated['url']
        );

        /*
         * Preview
         */
        if ($action === 'preview') {

            return view('home', [
                'video' => $video,
                'url'   => $validated['url'],
            ]);
        }

        /*
         * Download
         */
        if (in_array($action, ['hd', 'watermark'])) {

            $tempDirectory = storage_path('app/temp');

            /*
             * Make sure the temporary directory exists.
             */
            if (! is_dir($tempDirectory)) {
                mkdir(
                    $tempDirectory,
                    0755,
                    true
                );
            }

            $temp = $tempDirectory . '/' . $video->filename;

            $this->downloader->download(
                $video,
                $temp,
                $action,
            );

            return response()
                ->download(
                    $temp,
                    $video->filename
                )
                ->deleteFileAfterSend(true);
        }

        return back();
    }
}