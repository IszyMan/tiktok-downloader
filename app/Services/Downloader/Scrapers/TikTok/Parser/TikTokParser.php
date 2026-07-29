<?php

namespace App\Services\Downloader\Scrapers\TikTok\Parser;

use RuntimeException;
use App\Services\Downloader\Scrapers\TikTok\DTO\TikTokVideoData;
use App\Services\Downloader\Scrapers\TikTok\DTO\VideoData;
use App\Services\Downloader\Scrapers\TikTok\DTO\AuthorData;
use App\Services\Downloader\Scrapers\TikTok\DTO\MusicData;
use App\Services\Downloader\Scrapers\TikTok\DTO\StatsData;
use App\Services\Downloader\Scrapers\TikTok\DTO\AuthorStatsData;

class TikTokParser
{
    /**
     * Parse the TikTok HTML into a TikTokVideoData DTO.
     *
     * @throws RuntimeException
     */
    public function parse(string $html): TikTokVideoData
    {
        $json = $this->extractHydrationJson($html);

        $data = $this->decodeJson($json);

        $videoDetail = $this->extractVideoDetail($data);

        $itemStruct = $this->extractItemStruct($videoDetail);

        return $this->mapToVideoData($itemStruct);
    }
    /**
     * Extract the hydration JSON from the HTML.
     *
     * @throws RuntimeException
     */
    private function extractHydrationJson(string $html): string
    {
        if (! preg_match(
            '/<script id="__UNIVERSAL_DATA_FOR_REHYDRATION__"[^>]*>(.*?)<\/script>/s',
            $html,
            $matches
        )) {
            throw new RuntimeException(
                'TikTok hydration data not found.'
            );
        }

        return $matches[1];
    }

    /**
     * Decode the hydration JSON.
     *
     * @throws RuntimeException
     */
    private function decodeJson(string $json): array
    {
        $data = json_decode($json, true);

        if (! is_array($data)) {
            throw new RuntimeException(
                'Unable to decode TikTok hydration JSON.'
            );
        }

        return $data;
    }


    /**
     * Extract the video detail section.
     *
     * @throws RuntimeException
     */
    private function extractVideoDetail(array $data): array
    {
        return $data['__DEFAULT_SCOPE__']['webapp.video-detail']
            ?? throw new RuntimeException(
                'TikTok video detail not found.'
            );
    }

    /**
     * Extract the item structure.
     *
     * @throws RuntimeException
     */
    private function extractItemStruct(array $videoDetail): array
    {
        return $videoDetail['itemInfo']['itemStruct']
            ?? throw new RuntimeException(
                'TikTok itemStruct not found.'
            );
    }

    /**
     * Convert TikTok data into a DTO.
     *
     * @throws RuntimeException
     */
    private function mapToVideoData(array $itemStruct): TikTokVideoData
    {
        $video = $itemStruct['video'];

        return new TikTokVideoData(
            id: $itemStruct['id'],
            description: $itemStruct['desc'],
            createTime: (int) $itemStruct['createTime'],

            video: new VideoData(
                id: $video['id'],
                duration: (int) $video['duration'],
                width: (int) $video['width'],
                height: (int) $video['height'],
                ratio: $video['ratio'],

                cover: $video['cover'],
                originCover: $video['originCover'],
                dynamicCover: $video['dynamicCover'],

                playAddr: $video['playAddr'] ?? '',

                downloadAddr:
                    ($video['downloadAddr'] ?? '')
                    ?: ($video['playAddr'] ?? '')
                    ?: ($video['PlayAddrStruct']['UrlList'][0] ?? ''),

                playUrls: $video['PlayAddrStruct']['UrlList'] ?? [],
            ),

            author: new AuthorData(
                id: $itemStruct['author']['id'],
                uniqueId: $itemStruct['author']['uniqueId'],
                nickname: $itemStruct['author']['nickname'],
                avatar: $itemStruct['author']['avatarLarger'],
                signature: $itemStruct['author']['signature'],
                verified: (bool) $itemStruct['author']['verified'],
                secUid: $itemStruct['author']['secUid'],
            ),

            music: new MusicData(
                id: $itemStruct['music']['id'],
                title: $itemStruct['music']['title'],
                authorName: $itemStruct['music']['authorName'],
                playUrl: $itemStruct['music']['playUrl'],
                duration: (int) $itemStruct['music']['duration'],
                original: (bool) $itemStruct['music']['original'],
            ),

            stats: new StatsData(
                playCount: (int) $itemStruct['stats']['playCount'],
                diggCount: (int) $itemStruct['stats']['diggCount'],
                commentCount: (int) $itemStruct['stats']['commentCount'],
                shareCount: (int) $itemStruct['stats']['shareCount'],
                collectCount: (int) $itemStruct['stats']['collectCount'],
            ),

            authorStats: new AuthorStatsData(
                followerCount: (int) $itemStruct['authorStats']['followerCount'],
                followingCount: (int) $itemStruct['authorStats']['followingCount'],
                heartCount: (int) $itemStruct['authorStats']['heartCount'],
                videoCount: (int) $itemStruct['authorStats']['videoCount'],
                diggCount: (int) $itemStruct['authorStats']['diggCount'],
                friendCount: (int) $itemStruct['authorStats']['friendCount'],
            ),
        );
    }
}