<?php

namespace App\Services\Downloader\Scrapers\TikTok\Detector;

use App\Services\Downloader\Scrapers\TikTok\Enums\ChallengeType;

class ChallengeDetector
{
    /**
     * Determine what kind of page was returned.
     */
    public function detect(string $html): ChallengeType
    {
        if ($this->isEmpty($html)) {
            return ChallengeType::EMPTY;
        }

        if ($this->isValidTikTokPage($html)) {
            return ChallengeType::VALID;
        }

        if ($this->isWaf($html)) {
            return ChallengeType::WAF;
        }

        if ($this->isCaptcha($html)) {
            return ChallengeType::CAPTCHA;
        }

        if ($this->isLoginPage($html)) {
            return ChallengeType::LOGIN_REQUIRED;
        }

        return ChallengeType::UNKNOWN;
    }

    /**
     * Empty or whitespace response.
     */
    private function isEmpty(string $html): bool
    {
        return blank(trim($html));
    }

    /**
     * Detect common Web Application Firewall pages.
     */
    private function isWaf(string $html): bool
    {
        return $this->containsAny($html, [
            'verify you are human',
            'verify to continue',
            'access denied',
            'attention required',
            'security check',
            'temporarily blocked',
            'cf-challenge',
            'cloudflare',

            // TikTok-specific
            'slardarwaf',
            '_wafchallengeid',
            'waf-aiso',
            'please wait...',
        ]);
    }

    /**
     * Detect TikTok login pages.
     */
    private function isLoginPage(string $html): bool
    {
        return $this->containsAny($html, [
            'login to tiktok',
            'log in to tiktok',
            'continue with phone',
            'continue with email',
            'use qr code',
        ]);
    }

    /**
     * Detect captcha pages.
     */
    private function isCaptcha(string $html): bool
    {
        return $this->containsAny($html, [
            'captcha',
            'g-recaptcha',
            'hcaptcha',
            'recaptcha',
        ]);
    }

    /**
     * Detect a valid TikTok page.
     */
    private function isValidTikTokPage(string $html): bool
    {
        return $this->containsAny($html, [
            '__UNIVERSAL_DATA_FOR_REHYDRATION__',
            'SIGI_STATE',
            '__DEFAULT_SCOPE__',
        ]);
    }

    /**
     * Determine whether the HTML contains any of the given markers.
     */
    private function containsAny(string $html, array $needles): bool
    {
        $html = strtolower($html);

        foreach ($needles as $needle) {
            if (str_contains($html, strtolower($needle))) {
                return true;
            }
        }

        return false;
    }
}