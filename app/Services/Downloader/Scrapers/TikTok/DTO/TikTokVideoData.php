<?php

namespace App\Services\Downloader\Scrapers\TikTok\DTO;

class TikTokVideoData
{
    public function __construct(
        public readonly string $id,
        public readonly string $description,
        public readonly int $createTime,

        public readonly VideoData $video,
        public readonly AuthorData $author,
        public readonly MusicData $music,
        public readonly StatsData $stats,
        public readonly AuthorStatsData $authorStats,
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'createTime' => $this->createTime,
            'video' => $this->video->toArray(),
            'author' => $this->author->toArray(),
            'music' => $this->music->toArray(),
            'stats' => $this->stats->toArray(),
        ];
    }
}