<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Supported languages.
     */
    private array $supportedLocales = [
        'en',
        'fr',
        'es',
        'de',
        'pt',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        /*
        |--------------------------------------------------------------------------
        | Get the first segment of the URL
        |--------------------------------------------------------------------------
        |
        | Examples:
        |
        | /
        | /tiktok-downloader
        | /de
        | /de/tiktok-downloader
        |
        */

        $urlLocale = $request->segment(1);


        /*
        |--------------------------------------------------------------------------
        | Determine the active language
        |--------------------------------------------------------------------------
        |
        | If the first URL segment is a supported language,
        | use it.
        |
        | Otherwise the site is English.
        |
        */

        if (
            $urlLocale &&
            in_array(
                $urlLocale,
                $this->supportedLocales,
                true
            )
        ) {

            $locale = $urlLocale;

        } else {

            $locale = 'en';

        }


        /*
        |--------------------------------------------------------------------------
        | Set Laravel application locale
        |--------------------------------------------------------------------------
        */

        app()->setLocale($locale);


        /*
        |--------------------------------------------------------------------------
        | Remember the language in the session
        |--------------------------------------------------------------------------
        |
        | This is useful when moving around the site, but the URL
        | remains the primary source of truth.
        |
        */

        session([
            'locale' => $locale,
        ]);


        return $next($request);
    }
}