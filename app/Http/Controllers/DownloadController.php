<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RuntimeException;
use App\Services\Downloader\VideoDownloader;
use App\Services\Downloader\UrlPlatformDetector;
use App\Services\Downloader\Providers\VideoProviderManager;

class DownloadController extends Controller
{
    public function __construct(
        private readonly VideoProviderManager $provider,
        private readonly VideoDownloader $downloader,
        private readonly UrlPlatformDetector $platformDetector,
    ) {
    }

    /**
     * Universal downloader homepage.
     */
    public function home()
    {
        return view('home');
    }

    /**
     * TikTok-only downloader page.
     */
    public function tiktok()
    {
        return view('tiktok.downloader');
    }

    /**
     * X-only downloader page.
     */
    public function x()
    {
        return view('x.downloader');
    }

    /**
     * YouTube-only downloader page.
     */
    public function youtube()
    {
        return view('youtube.downloader');
    }


    /**
     * Instagram-only downloader page.
     */
    public function instagram()
    {
        return view('instagram.downloader');
    }

    /**
     * Preview or download a video.
     */
    public function download(Request $request)
    {
        $validated = $request->validate([
            'url' => ['required', 'url'],
        ]);

        $url = $validated['url'];


        $requestedPlatform = $request->input(
            'platform',
            'universal'
        );

        /*
        |--------------------------------------------------------------------------
        | Detect actual platform from URL
        |--------------------------------------------------------------------------
        */

        try {
            $detectedPlatform = $this->platformDetector->detect($url);
        } catch (RuntimeException $e) {

            return back()
                ->withInput()
                ->withErrors([
                    'url' => $e->getMessage(),
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Platform-specific pages must reject URLs
        | belonging to another platform.
        |--------------------------------------------------------------------------
        */

        if (
            $requestedPlatform !== 'universal' &&
            $detectedPlatform !== $requestedPlatform
        ) {

            if ($detectedPlatform === 'x') {

                return back()
                    ->withInput()
                    ->withErrors([
                        'url' =>
                            'X URL detected. Please use our X Downloader instead.',
                    ]);
            }

            if ($detectedPlatform === 'tiktok') {

                return back()
                    ->withInput()
                    ->withErrors([
                        'url' =>
                            'TikTok URL detected. Please use our TikTok Downloader instead.',
                    ]);
            }

            if ($detectedPlatform === 'youtube') {

                return back()
                    ->withInput()
                    ->withErrors([
                        'url' =>
                            'YouTube URL detected. Please use our YouTube Downloader instead.',
                    ]);
            }


            if ($detectedPlatform === 'instagram') {

                return back()
                    ->withInput()
                    ->withErrors([
                        'url' =>
                            'Instagram URL detected. Please use our Instagram Downloader instead.',
                    ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Fetch metadata from the correct provider
        |--------------------------------------------------------------------------
        */

        try {

            $video = $this->provider->fetch($url);

        } catch (\Throwable $e) {

            return back()
                ->withInput()
                ->withErrors([
                    'url' =>
                        'Arwotuni! We could not process this video. Please check the URL and try again.',
                ]);
        }

        

        $action = $request->input('action', 'preview');

        
        if ($action === 'preview') {

            $view = match ($requestedPlatform) {

                'tiktok' => 'tiktok.downloader',

                'x' => 'x.downloader',

                'youtube' => 'youtube.downloader',

                'instagram' => 'instagram.downloader',

                default => 'home',
            };

            return view($view, [

                'video' => $video,

                'url' => $url,

                'platform' => $requestedPlatform,

            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Download
        |--------------------------------------------------------------------------
        */

        if (in_array($action, ['hd', 'watermark', 'mp3'], true)) {

            $tempDirectory = storage_path('app/temp');

            /*
            |--------------------------------------------------------------------------
            | Make sure temporary directory exists
            |--------------------------------------------------------------------------
            */

            if (!is_dir($tempDirectory)) {

                mkdir(
                    $tempDirectory,
                    0755,
                    true
                );
            }

            $filename = $video->filename;

            if (
                $action === 'mp3' &&
                str_ends_with(
                    strtolower($filename),
                    '.mp4'
                )
            ) {
                $filename = pathinfo(
                    $filename,
                    PATHINFO_FILENAME
                ) . '.mp3';
            }

            $temp = $tempDirectory . '/' . $filename;

            $this->downloader->download(
                $video,
                $temp,
                $action,
            );

            return response()
                ->download($temp, $filename)
                ->deleteFileAfterSend(true);
        }

        return back();
    }
}