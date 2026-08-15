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
        | Make sure the requested language exists.
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
        | Store the visitor's language.
        |--------------------------------------------------------------------------
        */

        session([
            'locale' => $locale,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Return to the previous page.
        |--------------------------------------------------------------------------
        */

        return redirect()->back();
    }
}