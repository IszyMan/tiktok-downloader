<?php

namespace App\Services\Downloader\Scrapers\TikTok\TikWM;

use RuntimeException;
use Illuminate\Support\Facades\Http;

class TikWMService
{
    public function getVideoData(string $url): array
    {
        $response = Http::withHeaders([
            'x-tikwmapi-key' => config('services.tikwm.api_key'),
        ])
        ->timeout(30)
        ->get(config('services.tikwm.base_url'), [
            'url' => $url,
            'hd'  => 1,
        ]);

        if (! $response->successful()) {
            throw new RuntimeException(
                'TikWM request failed.'
            );
        }

        $json = $response->json();
        

        if (($json['code'] ?? -1) !== 0) {
            throw new RuntimeException(
                $json['msg'] ?? 'TikWM returned an error.'
            );
        }

        return $json['data'];
    }
      
}