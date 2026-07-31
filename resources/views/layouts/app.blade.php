<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        TikTok Downloader
    </title>

    @vite(['resources/css/app.css'])

</head>

<body>

<header>

    <div class="container">

        <a
            href="/"
            class="logo"
        >

            <span class="logo-icon">

                ♪

            </span>

            <span>

                TikTok Downloader

            </span>

        </a>

        <nav>

            <a href="/">Home</a>

            <a href="#">TikTok</a>

            <a href="#">Instagram</a>

            <a href="#">X</a>

            <a href="#">API</a>

            <a href="#">Contact</a>

        </nav>

        <button
            id="menuButton"
            class="menu-button"
        >

            ☰

        </button>

    </div>

</header>


<div
    id="mobileMenu"
    class="mobile-menu"
>

    <a href="/">Home</a>

    <a href="#">TikTok</a>

    <a href="#">Instagram</a>

    <a href="#">X</a>

    <a href="#">API</a>

    <a href="#">Contact</a>

</div>


<main>

    @yield('content')

</main>


<footer>

    <div class="footer-container">

        <div>

            <h3>

                TikTok Downloader

            </h3>

            <p>

                Download TikTok videos without watermark in HD quality.

            </p>

        </div>

        <div>

            <h4>

                Quick Links

            </h4>

            <a href="/">Home</a>

            <a href="#">Privacy</a>

            <a href="#">Terms</a>

            <a href="#">Contact</a>

        </div>

        <div>

            <h4>

                Supported Platforms

            </h4>

            <p>

                ✅ TikTok

            </p>

            <p>

                🚧 Instagram (Coming Soon)

            </p>

            <p>

                🚧 X Downloader (Coming Soon)

            </p>

        </div>

    </div>

    <div class="copyright">

        © {{ date('Y') }}

        TikTok Downloader.

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