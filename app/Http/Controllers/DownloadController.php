<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Downloader\DownloaderService;
use App\Exceptions\InvalidTikTokUrlException;

class DownloadController extends Controller
{
    public function download(
        Request $request,
        DownloaderService $downloader
    ) {
        $request->validate([
            'url' => ['required', 'url'],
        ]);

        try {

            $result = $downloader->download($request->url);

            return view('result', compact('result'));

        } catch (InvalidTikTokUrlException $e) {

            return back()->withErrors([
                'url' => $e->getMessage()
            ]);

        }
    }
}