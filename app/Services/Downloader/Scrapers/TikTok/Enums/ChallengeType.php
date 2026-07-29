<?php

namespace App\Services\Downloader\Scrapers\TikTok\Enums;

enum ChallengeType: string
{
    case VALID = 'valid';

    case EMPTY = 'empty';

    case CAPTCHA = 'captcha';

    case LOGIN_REQUIRED = 'login_required';

    case WAF = 'waf';

    case UNKNOWN = 'unknown';
}