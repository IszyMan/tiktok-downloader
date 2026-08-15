<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title> @yield('title', 'Online Video Downloader') </title>
    <meta name="description" content="@yield('meta_description', 'Download videos online quickly and easily.')">
    <link rel="canonical" href="@yield('canonical', url()->current())">


    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>

</head>

<body>

<header>

    <div class="container">

        <a
            href="{{ route('home') }}"
            class="logo"
        >
            <span class="logo-icon">
                ♪
            </span>

            <span>
                {{ __('common.site_name') }}
            </span>
        </a>

        <nav>
            <a href="{{ route('tiktok.downloader') }}">
                TikTok
            </a>

            <a href="{{ route('x.downloader') }}">
                X
            </a>

            <a href="{{ route('instagram.downloader') }}">
                Instagram
            </a>

            <a href="{{ route('facebook.downloader') }}">
                Facebook
            </a>

            
            <a href="{{ route('youtube.downloader') }}">
                Youtube
            </a>

            <a href="#">
                {{ __('common.contact') }}
            </a>

            <div class="language-switcher">

                <button
                    type="button"
                    class="language-current"
                >
                    {{ strtoupper(app()->getLocale()) }}
                    ▾
                </button>

                <div class="language-menu">

                    <a
                        href="{{ route('language.switch', 'en') }}"
                    >
                        🇬🇧 English
                    </a>

                    <a
                        href="{{ route('language.switch', 'fr') }}"
                    >
                        🇫🇷 Français
                    </a>

                    <a
                        href="{{ route('language.switch', 'es') }}"
                    >
                        🇪🇸 Español
                    </a>

                    <a
                        href="{{ route('language.switch', 'de') }}"
                    >
                        🇩🇪 Deutsch
                    </a>

                    <a
                        href="{{ route('language.switch', 'pt') }}"
                    >
                        🇵🇹 Português
                    </a>

                </div>

            </div>

        </nav>

        <button
            id="menuButton"
            class="menu-button"
            type="button"
            aria-label="Open navigation menu"
        >
            ☰
        </button>

    </div>

</header>


<div id="mobileMenu" class="mobile-menu">    

    <a href="{{ route('tiktok.downloader') }}">
        TikTok
    </a>

    <a href="{{ route('x.downloader') }}">
        X
    </a>

    <a href="{{ route('instagram.downloader') }}">
        Instagram
    </a>

    <a href="{{ route('facebook.downloader') }}">
        Facebook
    </a>

    <a href="{{ route('youtube.downloader') }}">
        Youtube
    </a>


    <a href="#">
        Contact
    </a>

    <div class="language-switcher">

        <button
            type="button"
            class="language-current"
        >
            {{ strtoupper(app()->getLocale()) }}
            ▾
        </button>

        <div class="language-menu">

            <a
                href="{{ route('language.switch', 'en') }}"
            >
                🇬🇧 English
            </a>

            <a
                href="{{ route('language.switch', 'fr') }}"
            >
                🇫🇷 Français
            </a>

            <a
                href="{{ route('language.switch', 'es') }}"
            >
                🇪🇸 Español
            </a>

            <a
                href="{{ route('language.switch', 'de') }}"
            >
                🇩🇪 Deutsch
            </a>

            <a
                href="{{ route('language.switch', 'pt') }}"
            >
                🇵🇹 Português
            </a>

        </div>

    </div>

</div>


<main>

    

        @yield('content')

   

</main>


<footer>

    <div class="footer-container">

        <div>

            <h3>
                {{ __('common.site_name') }}
            </h3>

            <p>
                {{ __('common.footer_description') }}
            </p>

            <br><br>

            <p>
                {{ __('common.current_language') }}
                {{ app()->getLocale() }}
            </p>

        </div>

        <div>

            <h4>
                {{ __('common.quick_links') }}
            </h4>

            <a href="{{ route('tiktok.downloader') }}">
                {{ __('common.tiktok_downloader') }}
            </a>

            <a href="{{ route('x.downloader') }}">
                {{ __('common.x_downloader') }}
            </a>

            <a href="{{ route('instagram.downloader') }}">
                {{ __('common.instagram_downloader') }}
            </a>

            <a href="{{ route('facebook.downloader') }}">
                Facebook Downloader
            </a>

            <a href="{{ route('youtube.downloader') }}">
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

        </div>

    </div>

<div class="copyright">

    © {{ date('Y') }}

    TikTok, X, Instagram & YouTube Downloader.

    {{ __('common.all_rights_reserved') }}

</div>

    
</footer>


<script>

const button=document.getElementById('menuButton');

const menu=document.getElementById('mobileMenu');

button.addEventListener('click',()=>{

    menu.classList.toggle('show');

});

</script>


<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Language dropdown
    |--------------------------------------------------------------------------
    */

    const languageSwitchers =
        document.querySelectorAll('.language-switcher');


    languageSwitchers.forEach(function (switcher) {

        const button =
            switcher.querySelector('.language-current');

        if (!button) {
            return;
        }


        button.addEventListener('click', function (event) {

            event.stopPropagation();

            /*
            |--------------------------------------------------------------------------
            | Close other language menus
            |--------------------------------------------------------------------------
            */

            languageSwitchers.forEach(function (otherSwitcher) {

                if (otherSwitcher !== switcher) {

                    otherSwitcher.classList.remove('open');

                }

            });


            /*
            |--------------------------------------------------------------------------
            | Toggle current menu
            |--------------------------------------------------------------------------
            */

            switcher.classList.toggle('open');

        });

    });


    /*
    |--------------------------------------------------------------------------
    | Close language menu when clicking outside
    |--------------------------------------------------------------------------
    */

    document.addEventListener('click', function () {

        languageSwitchers.forEach(function (switcher) {

            switcher.classList.remove('open');

        });

    });

});

</script>

</body>

</html>