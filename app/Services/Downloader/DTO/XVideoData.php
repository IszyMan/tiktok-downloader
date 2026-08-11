<?php

namespace App\Services\Downloader\DTO;

class XVideoData extends VideoData
{
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,

            'duration' => $this->duration,
            'width' => $this->width,
            'height' => $this->height,

            'author' => $this->author->toArray(),

            'media' => $this->media->toArray(),

            'downloads' => $this->downloads->toArray(),

            'statistics' => $this->statistics->toArray(),

            'provider' => $this->provider,

            'sourceUrl' => $this->sourceUrl,

            'filename' => $this->filename,

            'extra' => $this->extra,
        ];
    }
}