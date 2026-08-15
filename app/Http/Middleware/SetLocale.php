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

        $locale = session(
            'locale',
            config('app.locale', 'en')
        );

        /*
        |--------------------------------------------------------------------------
        | Make sure the selected language is supported.
        |--------------------------------------------------------------------------
        */

        if (! in_array(
            $locale,
            $this->supportedLocales,
            true
        )) {
            $locale = 'en';
        }

        /*
        |--------------------------------------------------------------------------
        | Tell Laravel which language to use.
        |--------------------------------------------------------------------------
        */

        app()->setLocale($locale);

        return $next($request);
    }
}