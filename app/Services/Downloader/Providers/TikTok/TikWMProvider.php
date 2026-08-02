<?php

namespace App\Services\Downloader\Providers\TikTok;

use App\Services\Downloader\DTO\AuthorData;
use App\Services\Downloader\DTO\DownloadData;
use App\Services\Downloader\DTO\MediaData;
use App\Services\Downloader\DTO\StatisticsData;
use App\Services\Downloader\DTO\TikTokVideoData;
use App\Services\Downloader\Scrapers\TikTok\TikWM\TikWMService;

class TikWMProvider implements TikTokProviderContract
{
    public function __construct(
        private readonly TikWMService $service,
    ) {
    }

    public function fetch(string $url): TikTokVideoData
    {
        $data = $this->service->getVideoData($url);

        return new TikTokVideoData(

            id: (string) ($data['id'] ?? ''),

            title: $data['title'] ?? '',

            description: $data['title'] ?? '',

            duration: $data['duration'] ?? null,

            width: null,

            height: null,

            author: new AuthorData(

                id: $data['author']['id'] ?? null,

                username: $data['author']['unique_id'] ?? null,

                nickname: $data['author']['nickname'] ?? null,

                avatar: $data['author']['avatar'] ?? null,

            ),

            media: new MediaData(

                thumbnail: $data['origin_cover'] ?? null,

                cover: $data['cover'] ?? null,

            ),

            downloads: new DownloadData(

                playUrl: $data['play'] ?? null,

                hdPlayUrl: $data['hdplay'] ?? null,

                watermarkUrl: $data['wmplay'] ?? null,

                musicUrl: $data['music'] ?? null,

                playSize: null,

                hdSize: null,

                watermarkSize: null,

                format: 'mp4',

                formatId: null,
            ),

            statistics: new StatisticsData(

            views: $data['play_count'] ?? null,

            likes: $data['digg_count'] ?? null,

            comments: $data['comment_count'] ?? null,

            shares: $data['share_count'] ?? null,

            downloads: $data['download_count'] ?? null,

            favorites: $data['collect_count'] ?? null,

        ),

            provider: 'tikwm',

            sourceUrl: $url,

            filename: sprintf(
                '%s-%s.mp4',
                config('downloader.filename_prefix', 'tiktok'),
                $data['id'] ?? time()
            ),

            extra: $data,

        );
    }
}