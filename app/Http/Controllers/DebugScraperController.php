<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Downloader\Scrapers\TikTok\Parser\TikTokParser;
use App\Services\Downloader\Scrapers\TikTok\Resolver\TikTokResolver;
use App\Services\Downloader\Scrapers\TikTok\Fetcher\FetchManager;

class DebugScraperController extends Controller
{
    public function __invoke(
        Request $request,
        TikTokResolver $resolver,
        FetchManager $fetchManager,
        TikTokParser $parser,
    ) {
        $request->validate([
            'url' => ['required', 'url'],
        ]);

        // Normalize the TikTok URL.
        $resolved = $resolver->resolve($request->url);

        // Fetch a valid HTML page.
        $html = $fetchManager->fetch($resolved);

        // Save the HTML for debugging.
        file_put_contents(
            storage_path('app/tiktok.html'),
            $html
        );

        $data = $parser->parse($html);

        return response()->json([
            'id' => $data->id,
            'description' => $data->description,
            'author' => $data->author->nickname,
            'verified' => $data->author->verified,
            'download' => $data->video->downloadAddr,
            'playUrls' => $data->video->playUrls,
            'plays' => $data->stats->playCount,
        ]);
    }
}