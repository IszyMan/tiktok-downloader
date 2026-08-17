<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', __('common.site_name'))
    </title>

    <meta
        name="description"
        content="@yield('meta_description', __('common.site_description'))"
    >

    <link
        rel="canonical"
        href="@yield('canonical', url()->current())"
    >


    {{-- =========================================================
         HREFLANG
    ========================================================== --}}

    @php

        $locale = app()->getLocale();

        /*
        |--------------------------------------------------------------------------
        | Determine the current downloader page
        |--------------------------------------------------------------------------
        */

        $currentRouteName = request()->route()?->getName();

        $pageType = match (true) {

            $currentRouteName === 'home'
                || $currentRouteName === 'en.home'
                || $currentRouteName === 'es.home'
                || $currentRouteName === 'fr.home'
                || $currentRouteName === 'de.home'
                || $currentRouteName === 'pt.home'
                => 'home',

            str_contains($currentRouteName ?? '', 'tiktok.downloader')
                => 'tiktok',

            str_contains($currentRouteName ?? '', 'x.downloader')
                => 'x',

            str_contains($currentRouteName ?? '', 'youtube.downloader')
                => 'youtube',

            str_contains($currentRouteName ?? '', 'instagram.downloader')
                => 'instagram',

            str_contains($currentRouteName ?? '', 'facebook.downloader')
                => 'facebook',

            default => 'home',

        };


        /*
        |--------------------------------------------------------------------------
        | Build localized GET routes
        |--------------------------------------------------------------------------
        */

        $localizedRoutes = [];


        foreach (['en', 'es', 'fr', 'de', 'pt'] as $language) {

            if ($pageType === 'home') {

                $localizedRoutes[$language] = $language === 'en'
                    ? route('home')
                    : route($language . '.home');

            } else {

                $localizedRoutes[$language] = $language === 'en'
                    ? route($pageType . '.downloader')
                    : route($language . '.' . $pageType . '.downloader');

            }

        }

    @endphp


    @foreach ($localizedRoutes as $language => $url)

        <link
            rel="alternate"
            hreflang="{{ $language }}"
            href="{{ $url }}"
        >

    @endforeach


    {{-- x-default points to English --}}
    <link
        rel="alternate"
        hreflang="x-default"
        href="{{ $localizedRoutes['en'] }}"
    >


    {{-- =========================================================
         CSS
    ========================================================== --}}

    <link
        rel="stylesheet"
        href="{{ asset('css/style.css') }}"
    >


    {{-- =========================================================
         JAVASCRIPT
    ========================================================== --}}

    <script
        src="{{ asset('js/app.js') }}"
        defer
    ></script>


    @stack('head')

</head>


<body>


{{-- =============================================================
     HEADER
============================================================== --}}

<header>

    @php

        /*
        |--------------------------------------------------------------------------
        | Current locale
        |--------------------------------------------------------------------------
        */

        $locale = app()->getLocale();


        /*
        |--------------------------------------------------------------------------
        | Navigation routes
        |--------------------------------------------------------------------------
        */

        $homeRoute = $locale === 'en'
            ? route('home')
            : route($locale . '.home');


        $tiktokRoute = $locale === 'en'
            ? route('tiktok.downloader')
            : route($locale . '.tiktok.downloader');


        $xRoute = $locale === 'en'
            ? route('x.downloader')
            : route($locale . '.x.downloader');


        $youtubeRoute = $locale === 'en'
            ? route('youtube.downloader')
            : route($locale . '.youtube.downloader');


        $instagramRoute = $locale === 'en'
            ? route('instagram.downloader')
            : route($locale . '.instagram.downloader');


        $facebookRoute = $locale === 'en'
            ? route('facebook.downloader')
            : route($locale . '.facebook.downloader');

    @endphp


    <div class="container">


        {{-- =====================================================
             LOGO
        ====================================================== --}}

        <a
            href="{{ $homeRoute }}"
            class="logo"
        >

            <span class="logo-icon">
                ♪
            </span>

            <span>
                {{ __('common.site_name') }}
            </span>

        </a>


        {{-- =====================================================
             DESKTOP NAVIGATION
        ====================================================== --}}

        <div class="header-navigation">


            {{-- =================================================
                 LANGUAGE SWITCHER
            ================================================== --}}

            <div class="language-switcher">

                <button
                    type="button"
                    class="language-current"
                    aria-label="{{ __('common.change_language') }}"
                >

                    {{ strtoupper($locale) }}

                    ▾

                </button>


                <div class="language-menu">


                    <a href="{{ route('language.switch', 'en') }}">

                        🇬🇧

                        {{ __('common.languages.en') }}

                    </a>


                    <a href="{{ route('language.switch', 'fr') }}">

                        🇫🇷

                        {{ __('common.languages.fr') }}

                    </a>


                    <a href="{{ route('language.switch', 'es') }}">

                        🇪🇸

                        {{ __('common.languages.es') }}

                    </a>


                    <a href="{{ route('language.switch', 'de') }}">

                        🇩🇪

                        {{ __('common.languages.de') }}

                    </a>


                    <a href="{{ route('language.switch', 'pt') }}">

                        🇵🇹

                        {{ __('common.languages.pt') }}

                    </a>


                </div>

            </div>


            {{-- =================================================
                 NAVIGATION
            ================================================== --}}

            <nav>

                <a href="{{ $tiktokRoute }}">
                    {{ __('common.tiktok_downloader') }}
                </a>


                <a href="{{ $xRoute }}">
                    {{ __('common.x_downloader') }}
                </a>


                <a href="{{ $instagramRoute }}">
                    {{ __('common.instagram_downloader') }}
                </a>


                <a href="{{ $facebookRoute }}">
                    {{ __('common.facebook_downloader') }}
                </a>


                <a href="{{ $youtubeRoute }}">
                    {{ __('common.youtube_downloader') }}
                </a>


                <a href="#">
                    {{ __('common.contact') }}
                </a>

            </nav>

        </div>


        {{-- =====================================================
             MOBILE MENU BUTTON
        ====================================================== --}}

        <button
            id="menuButton"
            class="menu-button"
            type="button"
            aria-label="{{ __('common.menu_open') }}"
            aria-expanded="false"
        >

            ☰

        </button>

    </div>

</header>



{{-- =============================================================
     MOBILE MENU
============================================================== --}}

<div
    id="mobileMenu"
    class="mobile-menu"
>


    <a href="{{ $tiktokRoute }}">
        {{ __('common.tiktok_downloader') }}
    </a>


    <a href="{{ $xRoute }}">
        {{ __('common.x_downloader') }}
    </a>


    <a href="{{ $instagramRoute }}">
        {{ __('common.instagram_downloader') }}
    </a>


    <a href="{{ $facebookRoute }}">
        {{ __('common.facebook_downloader') }}
    </a>


    <a href="{{ $youtubeRoute }}">
        {{ __('common.youtube_downloader') }}
    </a>


    <a href="#">
        {{ __('common.contact') }}
    </a>


    {{-- Mobile language links --}}

    <div class="mobile-language-links">

        <span>
            {{ __('common.language') }}
        </span>


        <a href="{{ route('language.switch', 'en') }}">
            🇬🇧 {{ __('common.languages.en') }}
        </a>


        <a href="{{ route('language.switch', 'fr') }}">
            🇫🇷 {{ __('common.languages.fr') }}
        </a>


        <a href="{{ route('language.switch', 'es') }}">
            🇪🇸 {{ __('common.languages.es') }}
        </a>


        <a href="{{ route('language.switch', 'de') }}">
            🇩🇪 {{ __('common.languages.de') }}
        </a>


        <a href="{{ route('language.switch', 'pt') }}">
            🇵🇹 {{ __('common.languages.pt') }}
        </a>

    </div>

</div>



{{-- =============================================================
     MAIN CONTENT
============================================================== --}}

<main>

    @yield('content')

</main>



{{-- =============================================================
     FOOTER
============================================================== --}}

<footer>


    <div class="footer-container">


        {{-- =====================================================
             SITE INFORMATION
        ====================================================== --}}

        <div>

            <h3>
                {{ __('common.site_name') }}
            </h3>


            <p>
                {{ __('common.footer_description') }}
            </p>

        </div>



        {{-- =====================================================
             QUICK LINKS
        ====================================================== --}}

        <div>

            <h4>
                {{ __('common.quick_links') }}
            </h4>


            <a href="{{ $tiktokRoute }}">
                {{ __('common.tiktok_downloader') }}
            </a>


            <a href="{{ $xRoute }}">
                {{ __('common.x_downloader') }}
            </a>


            <a href="{{ $instagramRoute }}">
                {{ __('common.instagram_downloader') }}
            </a>


            <a href="{{ $facebookRoute }}">
                {{ __('common.facebook_downloader') }}
            </a>


            <a href="{{ $youtubeRoute }}">
                {{ __('common.youtube_downloader') }}
            </a>


            <a href="#">
                {{ __('common.privacy') }}
            </a>


            <a href="#">
                {{ __('common.terms') }}
            </a>


            <a href="#">
                {{ __('common.contact') }}
            </a>

        </div>



        {{-- =====================================================
             SUPPORTED PLATFORMS
        ====================================================== --}}

        <div>

            <h4>
                {{ __('common.supported_platforms') }}
            </h4>


            <p>
                ✅ TikTok
            </p>


            <p>
                ✅ X
            </p>


            <p>
                ✅ Instagram
            </p>


            <p>
                ✅ Facebook
            </p>


            <p>
                ✅ YouTube
            </p>

        </div>

    </div>



    {{-- =========================================================
         COPYRIGHT
    ========================================================== --}}

    <div class="copyright">

        © {{ date('Y') }}

        {{ __('common.site_name') }}.

        {{ __('common.all_rights_reserved') }}

    </div>


</footer>



{{-- =============================================================
     MOBILE MENU SCRIPT
============================================================== --}}

<script>

document.addEventListener('DOMContentLoaded', function () {


    const button =
        document.getElementById('menuButton');


    const menu =
        document.getElementById('mobileMenu');


    if (!button || !menu) {
        return;
    }


    button.addEventListener('click', function () {


        const isOpen =
            menu.classList.toggle('show');


        button.setAttribute(
            'aria-expanded',
            isOpen ? 'true' : 'false'
        );

    });

});

</script>



{{-- =============================================================
     LANGUAGE DROPDOWN SCRIPT
============================================================== --}}

<script>

document.addEventListener('DOMContentLoaded', function () {


    const languageSwitchers =
        document.querySelectorAll('.language-switcher');


    languageSwitchers.forEach(function (switcher) {


        const button =
            switcher.querySelector('.language-current');


        if (!button) {
            return;
        }


        button.addEventListener(
            'click',
            function (event) {


                event.stopPropagation();


                /*
                |--------------------------------------------------------------------------
                | Close other dropdowns
                |--------------------------------------------------------------------------
                */

                languageSwitchers.forEach(
                    function (otherSwitcher) {


                        if (
                            otherSwitcher !== switcher
                        ) {

                            otherSwitcher.classList.remove(
                                'open'
                            );

                        }

                    }
                );


                /*
                |--------------------------------------------------------------------------
                | Toggle current dropdown
                |--------------------------------------------------------------------------
                */

                switcher.classList.toggle('open');

            }
        );

    });


    /*
    |--------------------------------------------------------------------------
    | Close when clicking outside
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'click',
        function () {


            languageSwitchers.forEach(
                function (switcher) {


                    switcher.classList.remove(
                        'open'
                    );

                }
            );

        }
    );

});

</script>


@stack('scripts')

</body>

</html>