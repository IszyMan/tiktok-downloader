<?php

namespace App\Services\Downloader\DTO;

class AuthorData
{
    public function __construct(

        public readonly ?string $id,

        public readonly ?string $username,

        public readonly ?string $nickname,

        public readonly ?string $avatar,
    ) {
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}