@php

    /*
    |--------------------------------------------------------------------------
    | Downloader Page Configuration
    |--------------------------------------------------------------------------
    |
    | These values are supplied by the individual page:
    |
    | home.blade.php
    | tiktok/downloader.blade.php
    | x/downloader.blade.php
    |
    */

    $platform = $platform ?? 'universal';

    $isUniversal = $platform === 'universal';
    $isTikTok = $platform === 'tiktok';
    $isX = $platform === 'x';
    $isYouTube = $platform === 'youtube';
    $isInstagram = $platform === 'instagram';
    $isFacebook = $platform === 'facebook';


    /*
    |--------------------------------------------------------------------------
    | Video Preview
    |--------------------------------------------------------------------------
    */

    $hasVideo = isset($video);

    $thumbnail = null;

    if ($hasVideo) {

        $thumbnail =
            $video->media->cover
            ?? $video->media->thumbnail
            ?? null;
    }


    /*
    |--------------------------------------------------------------------------
    | Detect Actual Video Platform
    |--------------------------------------------------------------------------
    |
    | This is used only after a URL has been processed.
    |
    */

    $videoIsX = false;
    $videoIsTikTok = false;
    $videoIsYouTube = false;
    $videoIsInstagram = false;
    $videoIsFacebook = false;

    if ($hasVideo) {

        $videoIsX =
            ($video->provider ?? null) === 'ytdlp-x'
            ||
            ($video->extra['extractor'] ?? null) === 'twitter';

        $videoIsYouTube =
            ($video->provider ?? null) === 'ytdlp-youtube'
            ||
            ($video->extra['extractor'] ?? null) === 'youtube';

        $videoIsInstagram =
            ($video->provider ?? null) === 'ytdlp-instagram'
            ||
            ($video->extra['extractor'] ?? null) === 'instagram';

        $videoIsFacebook =
            ($video->provider ?? null) === 'ytdlp-facebook'
            ||
            ($video->extra['extractor'] ?? null) === 'facebook';    

        $videoIsTikTok =
            ($video->provider ?? null) === 'tikwm'
            ||
            ($video->extra['extractor'] ?? null) === 'tiktok';
    }

    $previewPlatformName = match (true) {

        $videoIsX => 'X Video',
        $videoIsYouTube => 'YouTube Video',
        $videoIsInstagram => 'Instagram Video',
        $videoIsFacebook => 'Facebook Video',
        $videoIsTikTok => 'TikTok Video',
        default => 'Video',
    };

@endphp


<div
    class="downloader-page theme-{{ $platform }}"
    data-platform="{{ $platform }}"
>


    {{-- =========================================================
         HERO
    ========================================================== --}}

    <section class="downloader-hero">

        <div class="hero-inner">


            {{-- =================================================
                 INITIAL DOWNLOAD FORM
            ================================================== --}}

            @if(! $hasVideo)

                {{-- Badge --}}

                <span class="hero-badge">

                    @if($isTikTok)

                        TikTok • No Watermark • Fast • HD

                    @elseif($isX)

                        X • Fast • HD Quality

                    @elseif($isYouTube)

                        YouTube • Fast • HD Quality 
                        
                    @elseif($isInstagram)

                        Instagram • Reels • Fast • HD Quality  

                    @elseif($isFacebook)

                        Facebook • Fast • HD Quality

                    @else

                        TikTok • X • Instagram • Facebook • YouTube • Fast • HD Quality

                    @endif

                </span>


                {{-- Heading --}}

                <h1>
                    {{ $pageTitle }}
                </h1>


                {{-- Description --}}

                <p class="hero-description">
                    {{ $pageDescription }}
                </p>


                {{-- =================================================
                     ERROR MESSAGE
                ================================================== --}}

                @if ($errors->any())

                    <div class="error-box">

                        <div>
                            {{ $errors->first() }}
                        </div>


                        @php
                            $errorMessage = strtolower($errors->first());
                        @endphp


                        @if(str_contains($errorMessage, 'x url detected'))

                            <div class="error-action">

                                <a href="{{ route('x.downloader') }}">
                                    Go to X Downloader →
                                </a>

                            </div>


                        @elseif(str_contains($errorMessage, 'tiktok url detected'))

                            <div class="error-action">

                                <a href="{{ route('tiktok.downloader') }}">
                                    Go to TikTok Downloader →
                                </a>

                            </div>


                        @elseif(str_contains($errorMessage, 'youtube url detected'))

                            <div class="error-action">

                                <a href="{{ route('youtube.downloader') }}">
                                    Go to YouTube Downloader →
                                </a>

                            </div>


                        @elseif(str_contains($errorMessage, 'instagram url detected'))

                            <div class="error-action">

                                <a href="{{ route('instagram.downloader') }}">
                                    Go to Instagram Downloader →
                                </a>

                            </div>


                        @elseif(str_contains($errorMessage, 'facebook url detected'))

                            <div class="error-action">

                                <a href="{{ route('facebook.downloader') }}">
                                    Go to Facebook Downloader →
                                </a>

                            </div>

                        @endif

                    </div>

                @endif

                {{-- =================================================
                     DOWNLOAD FORM
                ================================================== --}}

                <form
                    action="{{ route('download') }}"
                    method="POST"
                    class="download-form"
                >

                    @csrf

                    <input
                        type="hidden"
                        name="platform"
                        value="{{ $platform }}"
                    >

                    <input
                        type="url"
                        name="url"
                        value="{{ old('url') }}"
                        placeholder="{{ $inputPlaceholder }}"
                        required
                    >

                    <button type="submit">
                        Download
                    </button>

                </form>


                {{-- =================================================
                     SUPPORTED PLATFORMS
                ================================================== --}}

                <div class="supported-platforms">

                    <span>
                        Supports:
                    </span>


                    @if($isUniversal || $isTikTok)
                        <a
                            href="{{ route('tiktok.downloader') }}"
                            class="platform-pill platform-tiktok"
                        >
                            TikTok
                        </a>
                    @endif


                    @if($isUniversal || $isX)
                        <a
                            href="{{ route('x.downloader') }}"
                            class="platform-pill platform-x"
                        >
                            X
                        </a>
                    @endif

                    @if($isUniversal || $isInstagram)
                        <a
                            href="{{ route('instagram.downloader') }}"
                            class="platform-pill platform-instagram"
                        >
                            Instagram
                        </a>
                    @endif


                    @if($isUniversal || $isFacebook)
                        <a
                            href="{{ route('facebook.downloader') }}"
                            class="platform-pill platform-facebook"
                        >
                            Facebook
                        </a>
                    @endif

                    @if($isUniversal || $isYouTube)
                        <a
                            href="{{ route('youtube.downloader') }}"
                            class="platform-pill platform-youtube"
                        >
                            YouTube
                        </a>
                    @endif


                    

                </div>


            {{-- =================================================
                 VIDEO PREVIEW
            ================================================== --}}

            @else

                <span class="hero-badge">
                    {{ $previewPlatformName }} Ready
                </span>


                <p class="preview-description">
                    Your video has been detected.
                    Preview it below and choose how you'd like to download it.
                </p>


                {{-- =================================================
                     PREVIEW CARD
                ================================================== --}}

                <div
                    class="preview-card
                        @if($videoIsX)
                            platform-x
                        @elseif($videoIsYouTube)
                            platform-youtube
                        @elseif($videoIsInstagram)
                            platform-instagram
                        @elseif($videoIsFacebook)
                            platform-facebook    
                        @else
                            platform-tiktok
                        @endif"
                >


                    {{-- Background image --}}

                    @if($thumbnail)

                        <img
                            class="preview-background"
                            src="{{ $thumbnail }}"
                            alt="{{ $video->title ?? $previewPlatformName }}"
                            loading="lazy"
                        >

                    @endif


                    <div class="preview-overlay">


                        {{-- Platform label --}}

                        <div class="preview-platform">

                            <span class="platform-label">
                                {{ $previewPlatformName }}
                            </span>

                        </div>


                        {{-- =================================================
                             TOP PREVIEW INFORMATION
                        ================================================== --}}

                        <div class="preview-top">


                            {{-- Thumbnail --}}

                            <div class="preview-thumbnail">

                                @if($thumbnail)

                                    <img
                                        src="{{ $thumbnail }}"
                                        alt="{{ $video->title ?? $previewPlatformName }}"
                                        loading="lazy"
                                    >

                                @endif

                                <div class="play-icon">
                                    ▶
                                </div>

                            </div>


                            {{-- Information --}}

                            <div class="preview-info">


                                {{-- Title --}}

                                <h3 class="preview-title">
                                    {{ $video->title ?: 'Video' }}
                                </h3>


                                {{-- Author --}}

                                @if(isset($video->author))

                                    <div class="author-row">

                                        @if(!empty($video->author->avatar))

                                            <img
                                                class="author-avatar"
                                                src="{{ $video->author->avatar }}"
                                                alt="{{ $video->author->nickname ?? $video->author->username ?? 'Author' }}"
                                                loading="lazy"
                                            >

                                        @endif

                                        <div>

                                            <strong>
                                                {{ $video->author->nickname
                                                    ?? $video->author->username
                                                    ?? 'Unknown creator' }}
                                            </strong>

                                            

                                        </div>

                                    </div>

                                @endif


                                {{-- Video statistics --}}

                                @if(isset($video->statistics))

                                    <div class="video-stats">

                                        @if(isset($video->statistics->views))

                                            <span>
                                                ▶
                                                {{ number_format($video->statistics->views) }}
                                            </span>

                                        @endif

                                        @if(isset($video->statistics->likes))

                                            <span>
                                                ❤️
                                                {{ number_format($video->statistics->likes) }}
                                            </span>

                                        @endif

                                        @if(isset($video->statistics->shares))

                                            <span>
                                                🔁
                                                {{ number_format($video->statistics->shares) }}
                                            </span>

                                        @endif

                                    </div>

                                @endif

                            </div>

                        </div>


                        {{-- =================================================
                             VIDEO DETAILS
                        ================================================== --}}

                        <div class="preview-details">

                            @if(!empty($video->duration))

                                <span>
                                    ⏱
                                    {{ gmdate('i:s', (int) $video->duration) }}
                                </span>

                            @endif


                            @if(
                                !empty($video->width) &&
                                !empty($video->height)
                            )

                                <span>
                                    🎥
                                    {{ $video->width }}
                                    ×
                                    {{ $video->height }}
                                </span>

                            @endif


                            <span>
                                @if($videoIsX)
                                    X
                                @elseif($videoIsYouTube)
                                    YouTube
                                @elseif($videoIsInstagram)
                                    Instagram
                                @elseif($videoIsFacebook)
                                    Facebook    
                                @else
                                    TikTok
                                @endif
                            </span>

                        </div>


                        {{-- =================================================
                             DOWNLOAD BUTTONS
                        ================================================== --}}

                        <div class="download-buttons">
                            @if($videoIsYouTube)

                                {{-- YouTube Video --}}

                                <form
                                    method="POST"
                                    action="{{ route('download') }}"
                                >

                                    @csrf

                                    <input
                                        type="hidden"
                                        name="url"
                                        value="{{ $url }}"
                                    >

                                    <input
                                        type="hidden"
                                        name="platform"
                                        value="{{ $platform }}"
                                    >

                                    <input
                                        type="hidden"
                                        name="action"
                                        value="hd"
                                    >

                                    <button
                                        type="submit"
                                        class="btn-primary"
                                    >
                                        ⬇ Download Video (HD)
                                    </button>

                                </form>


                                {{-- YouTube MP3 --}}

                                <form
                                    method="POST"
                                    action="{{ route('download') }}"
                                >

                                    @csrf

                                    <input
                                        type="hidden"
                                        name="url"
                                        value="{{ $url }}"
                                    >

                                    <input
                                        type="hidden"
                                        name="platform"
                                        value="{{ $platform }}"
                                    >

                                    <input
                                        type="hidden"
                                        name="action"
                                        value="mp3"
                                    >

                                    <button
                                        type="submit"
                                        class="btn-secondary"
                                    >
                                        🎵 Download MP3
                                    </button>

                                </form>

                            @elseif($videoIsInstagram)

                                {{-- Instagram Video --}}

                                <form
                                    method="POST"
                                    action="{{ route('download') }}"
                                >

                                    @csrf

                                    <input
                                        type="hidden"
                                        name="url"
                                        value="{{ $url }}"
                                    >

                                    <input
                                        type="hidden"
                                        name="platform"
                                        value="{{ $platform }}"
                                    >

                                    <input
                                        type="hidden"
                                        name="action"
                                        value="hd"
                                    >

                                    <button
                                        type="submit"
                                        class="btn-secondary"
                                    >
                                        ⬇ Download Instagram Video
                                    </button>

                                </form>

                                {{-- Instagram Audio --}}

                                <form
                                    method="POST"
                                    action="{{ route('download') }}"
                                >

                                    @csrf

                                    <input
                                        type="hidden"
                                        name="url"
                                        value="{{ $url }}"
                                    >

                                    <input
                                        type="hidden"
                                        name="platform"
                                        value="{{ $platform }}"
                                    >

                                    <input
                                        type="hidden"
                                        name="action"
                                        value="mp3"
                                    >

                                    <button
                                        type="submit"
                                        class="btn-primary"
                                    >
                                        🎵 Download Audio
                                    </button>

                                </form>    

                            @elseif($videoIsX)

                                {{-- X HD Download --}}

                                <form
                                    method="POST"
                                    action="{{ route('download') }}"
                                >

                                    @csrf

                                    <input
                                        type="hidden"
                                        name="url"
                                        value="{{ $url }}"
                                    >

                                    <input
                                        type="hidden"
                                        name="platform"
                                        value="{{ $platform }}"
                                    >

                                    <input
                                        type="hidden"
                                        name="action"
                                        value="hd"
                                    >
                                    

                                    <button
                                        type="submit"
                                        class="btn-primary"
                                    >
                                        ⬇ Download HD
                                    </button>

                                </form>

                            @elseif($videoIsFacebook)
                            
                                {{-- Facebook Video --}}
                                <form
                                    method="POST"
                                    action="{{ route('download') }}"
                                >

                                    @csrf

                                    <input
                                        type="hidden"
                                        name="url"
                                        value="{{ $url }}"
                                    >

                                    <input
                                        type="hidden"
                                        name="platform"
                                        value="{{ $platform }}"
                                    >

                                    <input
                                        type="hidden"
                                        name="action"
                                        value="hd"
                                    >

                                    <button
                                        type="submit"
                                        class="btn-primary"
                                    >
                                        ⬇ Download Facebook Video
                                    </button>

                                </form>    

                            @else

                                {{-- TikTok Watermark --}}

                                <form
                                    method="POST"
                                    action="{{ route('download') }}"
                                >

                                    @csrf

                                    <input
                                        type="hidden"
                                        name="url"
                                        value="{{ $url }}"
                                    >

                                    <input
                                        type="hidden"
                                        name="platform"
                                        value="{{ $platform }}"
                                    >

                                    <input
                                        type="hidden"
                                        name="action"
                                        value="watermark"
                                    >

                                    <button
                                        type="submit"
                                        class="btn-primary"
                                    >
                                        ⬇ Download (Watermark)
                                    </button>

                                </form>


                                {{-- TikTok HD / No Watermark --}}

                                <form
                                    method="POST"
                                    action="{{ route('download') }}"
                                >

                                    @csrf

                                    <input
                                        type="hidden"
                                        name="url"
                                        value="{{ $url }}"
                                    >

                                    <input
                                        type="hidden"
                                        name="platform"
                                        value="{{ $platform }}"
                                    >

                                    <input
                                        type="hidden"
                                        name="action"
                                        value="hd"
                                    >

                                    <button
                                        type="submit"
                                        class="btn-secondary"
                                    >
                                        ⬇ Download HD (No Watermark)
                                    </button>

                                </form>

                            @endif


                            {{-- Download Another --}}

                            <a
                                href="{{ $isTikTok
                                    ? route('tiktok.downloader')
                                    : ($isX
                                        ? route('x.downloader')
                                        : ($isYouTube
                                            ? route('youtube.downloader')
                                            : ($isInstagram
                                                ? route('instagram.downloader')
                                                : ($isFacebook
                                                    ? route('facebook.downloader')
                                                    : route('home')
                                                )
                                            )
                                        )
                                    )
                                }}"
                                class="btn-outline"
                            >
                                Download Another Video
                            </a>

                        </div>


                    </div>

                </div>

            @endif

        </div>

    </section>


    {{-- =============================================================
         FEATURES
    ============================================================= --}}

    <section class="features-section">

        <div class="features-card">


            <div class="feature-item">

                <span>⚡</span>

                <div>

                    <h3>
                        Fast Download
                    </h3>

                    <p>

                        @if($isTikTok)

                            Download TikTok videos in seconds.

                        @elseif($isX)

                            Download X videos in seconds.

                        @elseif($isYouTube)

                            Download YouTube videos in seconds.

                        @elseif($isInstagram)

                            Download Instagram videos in seconds.
                            
                        @elseif($isFacebook)

                            Download Facebook videos in seconds.                            

                        @else

                            Download TikTok, X, Instagram, Facebook and YouTube videos in seconds.

                        @endif

                    </p>

                </div>

            </div>


            <div class="divider"></div>


            <div class="feature-item">

                <span>🎥</span>

                <div>

                    <h3>
                        HD Quality
                    </h3>

                    <p>
                        Get the best available video quality.
                    </p>

                </div>

            </div>


            <div class="divider"></div>


            <div class="feature-item">

                <span>🔒</span>

                <div>

                    <h3>
                        No Login
                    </h3>

                    <p>
                        Just paste your video link and download.
                    </p>

                </div>

            </div>


        </div>

    </section>


    {{-- =============================================================
         HOW IT WORKS
    ============================================================= --}}

    <section class="how-it-works">

        <h2>

            @if($isTikTok)

                How to Download TikTok Videos

            @elseif($isX)

                How to Download X Videos

            @elseif($isYouTube)

                How to Download YouTube Videos

            @elseif($isInstagram)

                How to Download Instagram Videos

            @elseif($isFacebook)

                How to Download Facebook Videos

            @else

                How to Download TikTok, X, Instagram, Facebook & YouTube Videos

            @endif

        </h2>


        <p class="section-description">
            Download videos in just four simple steps.
        </p>


        <div class="steps">


            <div class="step">

                <div class="step-number">
                    1
                </div>

                <h3>
                    Copy Link
                </h3>

                <p>

                    @if($isTikTok)

                        Open TikTok and copy the video URL.

                    @elseif($isX)

                        Open X and copy the video URL.

                    @elseif($isYouTube)

                        Open YouTube and copy the video URL.   
                        
                    @elseif($isInstagram)

                        Open Instagram and copy the video URL.    

                    @elseif($isFacebook)

                        Open Facebook and copy the video URL.

                    @else

                        Open TikTok, X, Instagram, Facebook or Youtube and copy the video URL.

                    @endif

                </p>

            </div>


            <div class="step">

                <div class="step-number">
                    2
                </div>

                <h3>
                    Paste URL
                </h3>

                <p>
                    Paste the copied link into the downloader above.
                </p>

            </div>


            <div class="step">

                <div class="step-number">
                    3
                </div>

                <h3>
                    Download
                </h3>

                <p>
                    Click the Download button and wait for your video to process.
                </p>

            </div>


            <div class="step">

                <div class="step-number">
                    4
                </div>

                <h3>
                    Save Video
                </h3>

                <p>
                    Save the downloaded video to your device.
                </p>

            </div>


        </div>

    </section>


    {{-- =============================================================
         FAQ
    ============================================================= --}}

    <section class="faq-section">

        <h2>
            Frequently Asked Questions
        </h2>


        @if($isUniversal)

            <div class="faq-item">

                <h3>
                    Is the TikTok, X, YouTube, Instagram and Facebook downloader free?
                </h3>

                <p>
                    Yes. You can use our downloader to download
                    supported TikTok, X, YouTube, Instagram and Facebook videos
                    without creating an account.
                </p>

            </div>


            <div class="faq-item">

                <h3>
                    Can I download videos from different platforms?
                </h3>

                <p>
                    Yes. Paste a supported TikTok, X, YouTube, Instagram or Facebook
                    URL and the downloader will automatically detect the platform.
                </p>

            </div>


            <div class="faq-item">

                <h3>
                    Can I download TikTok videos without a watermark?
                </h3>

                <p>
                    Yes. When a no-watermark version is available,
                    you can choose the HD no-watermark download option.
                </p>

            </div>


        @elseif($isTikTok)

            <div class="faq-item">

                <h3>
                    Is the TikTok downloader free?
                </h3>

                <p>
                    Yes. You can download supported TikTok videos
                    without creating an account.
                </p>

            </div>


            <div class="faq-item">

                <h3>
                    Can I download TikTok videos without a watermark?
                </h3>

                <p>
                    Yes. When a no-watermark version is available,
                    you can choose the HD no-watermark download option.
                </p>

            </div>


            <div class="faq-item">

                <h3>
                    Can I use the TikTok downloader on my phone?
                </h3>

                <p>
                    Yes. It works on Android, iPhone, tablets and
                    desktop browsers.
                </p>

            </div>


        @elseif($isX)

            <div class="faq-item">

                <h3>
                    Is the X downloader free?
                </h3>

                <p>
                    Yes. You can download supported X videos without
                    creating an account.
                </p>

            </div>


            <div class="faq-item">

                <h3>
                    Can I download videos from X on my phone?
                </h3>

                <p>
                    Yes. The X downloader works on Android, iPhone,
                    tablets and desktop browsers.
                </p>

            </div>


            <div class="faq-item">

                <h3>
                    Do I need to install anything?
                </h3>

                <p>
                    No. Everything works directly in your web browser.
                    No application or browser extension is required.
                </p>

            </div>

       

        @elseif($isYouTube)

            <div class="faq-item">

                <h3>
                    Is the YouTube downloader free?
                </h3>

                <p>
                    Yes. You can use the downloader to process
                    supported YouTube videos without creating an account.
                </p>

            </div>


            <div class="faq-item">

                <h3>
                    Can I download YouTube videos in HD?
                </h3>

                <p>
                    When higher-quality video and audio streams are
                    available, the downloader combines the best available
                    video and audio into a single video file.
                </p>

            </div>


            <div class="faq-item">

                <h3>
                    Can I download YouTube videos as MP3?
                </h3>

                <p>
                    Yes. The YouTube downloader provides an MP3 option
                    for extracting audio from supported videos.
                </p>

            </div>


        @elseif($isInstagram)

            <div class="faq-item">

                <h3>
                    Is the Instagram downloader free?
                </h3>

                <p>
                    Yes. You can download supported Instagram videos
                    without creating an account.
                </p>

            </div>


            <div class="faq-item">

                <h3>
                    Can I download Instagram Reels?
                </h3>

                <p>
                    Yes. Paste a supported public Instagram Reel URL
                    and the downloader will process the video.
                </p>

            </div>


            <div class="faq-item">

                <h3>
                    Can I use the Instagram downloader on my phone?
                </h3>

                <p>
                    Yes. The Instagram downloader works on Android,
                    iPhone, tablets and desktop browsers.
                </p>

            </div>

        @elseif($isFacebook)

            <div class="faq-item">

                <h3>
                    Is the Facebook downloader free?
                </h3>

                <p>
                    Yes. You can download supported public Facebook videos
                    without creating an account.
                </p>

            </div>


            <div class="faq-item">

                <h3>
                    Can I download Facebook videos in HD?
                </h3>

                <p>
                    Yes. When a higher-quality video is available,
                    the downloader selects the best available video
                    and combines it with the available audio.
                </p>

            </div>


            <div class="faq-item">

                <h3>
                    Can I use the Facebook downloader on my phone?
                </h3>

                <p>
                    Yes. The Facebook downloader works on Android,
                    iPhone, tablets and desktop browsers.
                </p>

            </div>    


        @endif


        {{-- Common FAQ --}}

        <div class="faq-item">

            <h3>
                Do I need to install anything?
            </h3>

            <p>
                No. Everything works directly in your web browser.
                No application or browser extension is required.
            </p>

        </div>


        <div class="faq-item">

            <h3>
                Can I use the downloader on my phone?
            </h3>

            <p>
                Yes. The downloader works on Android, iPhone,
                tablets desktop browsers and all devices.
            </p>

        </div>


    </section>


</div>