<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LanguageController extends Controller
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
     * Change the application language.
     */
    public function switch(
        Request $request,
        string $locale
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Validate language
        |--------------------------------------------------------------------------
        */

        if (! in_array(
            $locale,
            $this->supportedLocales,
            true
        )) {
            abort(404);
        }


        /*
        |--------------------------------------------------------------------------
        | Save language
        |--------------------------------------------------------------------------
        */

        session([
            'locale' => $locale,
        ]);


        /*
        |--------------------------------------------------------------------------
        | Get current page
        |--------------------------------------------------------------------------
        */

        $referer = $request->headers->get('referer');


        if (! $referer) {

            return redirect(
                $locale === 'en'
                    ? url('/')
                    : url('/' . $locale)
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Extract current path
        |--------------------------------------------------------------------------
        */

        $path = parse_url(
            $referer,
            PHP_URL_PATH
        );


        $path = trim(
            $path ?? '',
            '/'
        );


        /*
        |--------------------------------------------------------------------------
        | Remove existing language prefix
        |--------------------------------------------------------------------------
        */

        $segments = $path === ''
            ? []
            : explode('/', $path);


        if (
            ! empty($segments) &&
            in_array(
                $segments[0],
                $this->supportedLocales,
                true
            )
        ) {

            array_shift($segments);
        }


        /*
        |--------------------------------------------------------------------------
        | Rebuild equivalent URL
        |--------------------------------------------------------------------------
        */

        $pagePath = implode(
            '/',
            $segments
        );


        /*
        |--------------------------------------------------------------------------
        | English has no prefix
        |--------------------------------------------------------------------------
        */

        if ($locale === 'en') {

            return redirect(
                $pagePath
                    ? url('/' . $pagePath)
                    : url('/')
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Other languages have prefix
        |--------------------------------------------------------------------------
        */

        return redirect(
            $pagePath
                ? url('/' . $locale . '/' . $pagePath)
                : url('/' . $locale)
        );
    }
}