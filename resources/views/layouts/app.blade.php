<!DOCTYPE html>
<html lang="en">

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
                Online Downloader
            </span>
        </a>

        <nav>
            <a href="{{ route('tiktok.downloader') }}">
                TikTok
            </a>

            <a href="{{ route('x.downloader') }}">
                X
            </a>

            
            <a href="{{ route('youtube.downloader') }}">
                Youtube
            </a>

            <a href="#">
                Instagram
            </a>

            <a href="#">
                API
            </a>

            <a href="#">
                Contact
            </a>

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

    <a href="{{ route('youtube.downloader') }}">
        Youtube
    </a>

    <a href="#">
        Instagram
    </a>

    <a href="#">
        API
    </a>

    <a href="#">
        Contact
    </a>

</div>


<main>

    

        @yield('content')

   

</main>


<footer>

    <div class="footer-container">

        <div>

            <h3>
                Online Video Downloader
            </h3>

            <p>
                Download videos from supported platforms quickly and easily.
                No login or installation required.
            </p>

        </div>

        <div>

            <h4>
                Quick Links
            </h4>

            <a href="{{ route('home') }}">
                Home
            </a>

            <a href="{{ route('tiktok.downloader') }}">
                TikTok Downloader
            </a>

            <a href="{{ route('x.downloader') }}">
                X Downloader
            </a>

            <a href="{{ route('youtube.downloader') }}">
                Youtube
            </a>

            <a href="#">
                Privacy
            </a>

            <a href="#">
                Terms
            </a>

            <a href="#">
                Contact
            </a>

        </div>

        <div>

            <h4>
                Supported Platforms
            </h4>

            <p>
                ✅ TikTok
            </p>

            <p>
                ✅ X
            </p>

            <p>
                🚧 Instagram (Coming Soon)
            </p>

        </div>

    </div>

<div class="copyright">

    © {{ date('Y') }}

    TikTok, X & Youtube Downloader.

    All Rights Reserved.

</div>

    
</footer>


<script>

const button=document.getElementById('menuButton');

const menu=document.getElementById('mobileMenu');

button.addEventListener('click',()=>{

    menu.classList.toggle('show');

});

</script>

</body>

</html>