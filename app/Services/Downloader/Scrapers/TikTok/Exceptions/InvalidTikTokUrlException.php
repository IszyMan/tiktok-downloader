<?php

namespace App\Services\Downloader\Scrapers\TikTok\Exceptions;

use Exception;

class InvalidTikTokUrlException extends Exception
{
    public function __construct(
        string $message = 'The provided TikTok URL is invalid.'
    ) {
        parent::__construct($message);
    }
}