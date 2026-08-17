@php

    /*
    |--------------------------------------------------------------------------
    | Downloader Page Configuration
    |--------------------------------------------------------------------------
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
    | Current Locale
    |--------------------------------------------------------------------------
    */

    $locale = app()->getLocale();


    /*
    |--------------------------------------------------------------------------
    | Download POST Route
    |--------------------------------------------------------------------------
    */

    $downloadRoute = match ($platform) {

        'tiktok' => $locale === 'en'
            ? route('tiktok.download')
            : route($locale . '.tiktok.download'),

        'x' => $locale === 'en'
            ? route('x.download')
            : route($locale . '.x.download'),

        'youtube' => $locale === 'en'
            ? route('youtube.download')
            : route($locale . '.youtube.download'),

        'instagram' => $locale === 'en'
            ? route('instagram.download')
            : route($locale . '.instagram.download'),

        'facebook' => $locale === 'en'
            ? route('facebook.download')
            : route($locale . '.facebook.download'),

        default => $locale === 'en'
            ? route('download')
            : route($locale . '.download'),

    };


    /*
    |--------------------------------------------------------------------------
    | Downloader GET Routes
    |--------------------------------------------------------------------------
    */

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


    /*
    |--------------------------------------------------------------------------
    | Home Route
    |--------------------------------------------------------------------------
    */

    $homeRoute = $locale === 'en'
        ? route('home')
        : route($locale . '.home');


    /*
    |--------------------------------------------------------------------------
    | Translation-Ready Page Content
    |--------------------------------------------------------------------------
    */

    $pageTitle = match ($platform) {

        'tiktok' => __('downloader.pages.tiktok.title'),

        'x' => __('downloader.pages.x.title'),

        'youtube' => __('downloader.pages.youtube.title'),

        'instagram' => __('downloader.pages.instagram.title'),

        'facebook' => __('downloader.pages.facebook.title'),

        default => __('downloader.pages.universal.title'),

    };


    $pageDescription = match ($platform) {

        'tiktok' => __('downloader.pages.tiktok.description'),

        'x' => __('downloader.pages.x.description'),

        'youtube' => __('downloader.pages.youtube.description'),

        'instagram' => __('downloader.pages.instagram.description'),

        'facebook' => __('downloader.pages.facebook.description'),

        default => __('downloader.pages.universal.description'),

    };


    $inputPlaceholder = match ($platform) {

        'tiktok' => __('downloader.input.tiktok'),

        'x' => __('downloader.input.x'),

        'youtube' => __('downloader.input.youtube'),

        'instagram' => __('downloader.input.instagram'),

        'facebook' => __('downloader.input.facebook'),

        default => __('downloader.input.universal'),

    };


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


    /*
    |--------------------------------------------------------------------------
    | Preview Platform
    |--------------------------------------------------------------------------
    */

    $previewPlatform = match (true) {

        $videoIsX => 'x',

        $videoIsYouTube => 'youtube',

        $videoIsInstagram => 'instagram',

        $videoIsFacebook => 'facebook',

        $videoIsTikTok => 'tiktok',

        default => 'universal',

    };


    $previewPlatformName = match ($previewPlatform) {

        'x' => __('common.x'),

        'youtube' => __('common.youtube'),

        'instagram' => __('common.instagram'),

        'facebook' => __('common.facebook'),

        'tiktok' => __('common.tiktok'),

        default => __('downloader.video'),

    };


    /*
    |--------------------------------------------------------------------------
    | Preview Badge
    |--------------------------------------------------------------------------
    */

    $previewBadge = __('downloader.preview.ready', [
        'platform' => $previewPlatformName,
    ]);


    /*
    |--------------------------------------------------------------------------
    | Another Video Route
    |--------------------------------------------------------------------------
    */

    $anotherVideoRoute = match ($platform) {

        'tiktok' => $tiktokRoute,

        'x' => $xRoute,

        'youtube' => $youtubeRoute,

        'instagram' => $instagramRoute,

        'facebook' => $facebookRoute,

        default => $homeRoute,

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

                        {{ __('downloader.badges.tiktok') }}

                    @elseif($isX)

                        {{ __('downloader.badges.x') }}

                    @elseif($isYouTube)

                        {{ __('downloader.badges.youtube') }}

                    @elseif($isInstagram)

                        {{ __('downloader.badges.instagram') }}

                    @elseif($isFacebook)

                        {{ __('downloader.badges.facebook') }}

                    @else

                        {{ __('downloader.badges.universal') }}

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

                    @php
                        $errorMessage = strtolower($errors->first());
                    @endphp

                    <div class="error-box">

                        <div>
                            {{ $errors->first() }}
                        </div>


                        @if(str_contains($errorMessage, 'x url detected'))

                            <div class="error-action">

                                <a href="{{ $xRoute }}">
                                    {{ __('downloader.errors.go_to_x') }} →
                                </a>

                            </div>


                        @elseif(str_contains($errorMessage, 'tiktok url detected'))

                            <div class="error-action">

                                <a href="{{ $tiktokRoute }}">
                                    {{ __('downloader.errors.go_to_tiktok') }} →
                                </a>

                            </div>


                        @elseif(str_contains($errorMessage, 'youtube url detected'))

                            <div class="error-action">

                                <a href="{{ $youtubeRoute }}">
                                    {{ __('downloader.errors.go_to_youtube') }} →
                                </a>

                            </div>


                        @elseif(str_contains($errorMessage, 'instagram url detected'))

                            <div class="error-action">

                                <a href="{{ $instagramRoute }}">
                                    {{ __('downloader.errors.go_to_instagram') }} →
                                </a>

                            </div>


                        @elseif(str_contains($errorMessage, 'facebook url detected'))

                            <div class="error-action">

                                <a href="{{ $facebookRoute }}">
                                    {{ __('downloader.errors.go_to_facebook') }} →
                                </a>

                            </div>

                        @endif

                    </div>

                @endif


                {{-- =================================================
                     DOWNLOAD FORM
                ================================================== --}}

                <form
                    action="{{ $downloadRoute }}"
                    method="POST"
                    class="download-form"
                >

                    @csrf

                    <input
                        type="hidden"
                        name="platform"
                        value="{{ $platform }}"
                    >


                    <div class="url-input-wrapper">

                        <input
                            type="url"
                            id="download-url"
                            name="url"
                            value="{{ old('url') }}"
                            placeholder="{{ $inputPlaceholder }}"
                            autocomplete="url"
                            required
                        >


                        <button
                            type="button"
                            class="paste-button"
                            id="paste-url-button"
                        >
                            {{ __('downloader.paste') }}
                        </button>

                    </div>


                    <button
                        type="submit"
                        class="download-submit"
                        id="download-submit-button"
                    >

                        <span class="download-button-spinner"></span>

                        <span class="download-button-text">
                            {{ __('downloader.download') }}
                        </span>

                    </button>

                </form>


                {{-- =================================================
                     SUPPORTED PLATFORMS
                ================================================== --}}

                <div class="supported-platforms">

                    <span>
                        {{ __('downloader.supports') }}:
                    </span>


                    @if($isUniversal || $isTikTok)

                        <a
                            href="{{ $tiktokRoute }}"
                            class="platform-pill platform-tiktok"
                        >
                            {{ __('common.tiktok') }}
                        </a>

                    @endif


                    @if($isUniversal || $isX)

                        <a
                            href="{{ $xRoute }}"
                            class="platform-pill platform-x"
                        >
                            {{ __('common.x') }}
                        </a>

                    @endif


                    @if($isUniversal || $isInstagram)

                        <a
                            href="{{ $instagramRoute }}"
                            class="platform-pill platform-instagram"
                        >
                            {{ __('common.instagram') }}
                        </a>

                    @endif


                    @if($isUniversal || $isFacebook)

                        <a
                            href="{{ $facebookRoute }}"
                            class="platform-pill platform-facebook"
                        >
                            {{ __('common.facebook') }}
                        </a>

                    @endif


                    @if($isUniversal || $isYouTube)

                        <a
                            href="{{ $youtubeRoute }}"
                            class="platform-pill platform-youtube"
                        >
                            {{ __('common.youtube') }}
                        </a>

                    @endif

                </div>


            {{-- =================================================
                 VIDEO PREVIEW
            ================================================== --}}

            @else

                <span class="hero-badge">
                    {{ $previewBadge }}
                </span>


                <p class="preview-description">
                    {{ __('downloader.preview.description') }}
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

                                    {{ $video->title ?: __('downloader.video') }}

                                </h3>


                                {{-- Author --}}

                                @if(isset($video->author))

                                    <div class="author-row">

                                        @if(!empty($video->author->avatar))

                                            <img
                                                class="author-avatar"
                                                src="{{ $video->author->avatar }}"
                                                alt="{{ $video->author->nickname ?? $video->author->username ?? __('downloader.author') }}"
                                                loading="lazy"
                                            >

                                        @endif


                                        <div>

                                            <strong>

                                                {{ $video->author->nickname
                                                    ?? $video->author->username
                                                    ?? __('downloader.unknown_creator') }}

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
                                {{ $previewPlatformName }}
                            </span>

                        </div>


                        {{-- =================================================
                             DOWNLOAD BUTTONS
                        ================================================== --}}

                        <div class="download-buttons">


                            {{-- =================================================
                                 YOUTUBE
                            ================================================== --}}

                            @if($videoIsYouTube)

                                <form
                                    method="POST"
                                    action="{{ $downloadRoute }}"
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
                                        ⬇ {{ __('downloader.buttons.youtube_video') }}
                                    </button>

                                </form>


                                <form
                                    method="POST"
                                    action="{{ $downloadRoute }}"
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
                                        🎵 {{ __('downloader.buttons.youtube_mp3') }}
                                    </button>

                                </form>


                            {{-- =================================================
                                 INSTAGRAM
                            ================================================== --}}

                            @elseif($videoIsInstagram)

                                <form
                                    method="POST"
                                    action="{{ $downloadRoute }}"
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
                                        ⬇ {{ __('downloader.buttons.instagram_video') }}
                                    </button>

                                </form>


                                <form
                                    method="POST"
                                    action="{{ $downloadRoute }}"
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
                                        🎵 {{ __('downloader.buttons.audio') }}
                                    </button>

                                </form>


                            {{-- =================================================
                                 X
                            ================================================== --}}

                            @elseif($videoIsX)

                                <form
                                    method="POST"
                                    action="{{ $downloadRoute }}"
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
                                        ⬇ {{ __('downloader.buttons.x_hd') }}
                                    </button>

                                </form>


                            {{-- =================================================
                                 FACEBOOK
                            ================================================== --}}

                            @elseif($videoIsFacebook)

                                <form
                                    method="POST"
                                    action="{{ $downloadRoute }}"
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
                                        ⬇ {{ __('downloader.buttons.facebook_video') }}
                                    </button>

                                </form>


                            {{-- =================================================
                                 TIKTOK
                            ================================================== --}}

                            @else

                                <form
                                    method="POST"
                                    action="{{ $downloadRoute }}"
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
                                        ⬇ {{ __('downloader.buttons.watermark') }}
                                    </button>

                                </form>


                                <form
                                    method="POST"
                                    action="{{ $downloadRoute }}"
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
                                        ⬇ {{ __('downloader.buttons.no_watermark') }}
                                    </button>

                                </form>

                            @endif


                            {{-- =================================================
                                 DOWNLOAD ANOTHER
                            ================================================== --}}

                            <a
                                href="{{ $anotherVideoRoute }}"
                                class="btn-outline"
                            >
                                {{ __('downloader.download_another') }}
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


            {{-- Fast Download --}}

            <div class="feature-item">

                <span>⚡</span>

                <div>

                    <h3>
                        {{ __('downloader.features.fast_title') }}
                    </h3>

                    <p>

                        @if($isTikTok)

                            {{ __('downloader.features.fast.tiktok') }}

                        @elseif($isX)

                            {{ __('downloader.features.fast.x') }}

                        @elseif($isYouTube)

                            {{ __('downloader.features.fast.youtube') }}

                        @elseif($isInstagram)

                            {{ __('downloader.features.fast.instagram') }}

                        @elseif($isFacebook)

                            {{ __('downloader.features.fast.facebook') }}

                        @else

                            {{ __('downloader.features.fast.universal') }}

                        @endif

                    </p>

                </div>

            </div>


            <div class="divider"></div>


            {{-- HD Quality --}}

            <div class="feature-item">

                <span>🎥</span>

                <div>

                    <h3>
                        {{ __('downloader.features.quality_title') }}
                    </h3>

                    <p>
                        {{ __('downloader.features.quality_description') }}
                    </p>

                </div>

            </div>


            <div class="divider"></div>


            {{-- No Login --}}

            <div class="feature-item">

                <span>🔒</span>

                <div>

                    <h3>
                        {{ __('downloader.features.login_title') }}
                    </h3>

                    <p>
                        {{ __('downloader.features.login_description') }}
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

                {{ __('downloader.how_it_works.title.tiktok') }}

            @elseif($isX)

                {{ __('downloader.how_it_works.title.x') }}

            @elseif($isYouTube)

                {{ __('downloader.how_it_works.title.youtube') }}

            @elseif($isInstagram)

                {{ __('downloader.how_it_works.title.instagram') }}

            @elseif($isFacebook)

                {{ __('downloader.how_it_works.title.facebook') }}

            @else

                {{ __('downloader.how_it_works.title.universal') }}

            @endif

        </h2>


        <p class="section-description">
            {{ __('downloader.how_it_works.description') }}
        </p>


        <div class="steps">


            {{-- Step 1 --}}

            <div class="step">

                <div class="step-number">
                    1
                </div>

                <h3>
                    {{ __('downloader.how_it_works.steps.copy_title') }}
                </h3>

                <p>

                    @if($isTikTok)

                        {{ __('downloader.how_it_works.steps.copy.tiktok') }}

                    @elseif($isX)

                        {{ __('downloader.how_it_works.steps.copy.x') }}

                    @elseif($isYouTube)

                        {{ __('downloader.how_it_works.steps.copy.youtube') }}

                    @elseif($isInstagram)

                        {{ __('downloader.how_it_works.steps.copy.instagram') }}

                    @elseif($isFacebook)

                        {{ __('downloader.how_it_works.steps.copy.facebook') }}

                    @else

                        {{ __('downloader.how_it_works.steps.copy.universal') }}

                    @endif

                </p>

            </div>


            {{-- Step 2 --}}

            <div class="step">

                <div class="step-number">
                    2
                </div>

                <h3>
                    {{ __('downloader.how_it_works.steps.paste_title') }}
                </h3>

                <p>
                    {{ __('downloader.how_it_works.steps.paste_description') }}
                </p>

            </div>


            {{-- Step 3 --}}

            <div class="step">

                <div class="step-number">
                    3
                </div>

                <h3>
                    {{ __('downloader.how_it_works.steps.download_title') }}
                </h3>

                <p>
                    {{ __('downloader.how_it_works.steps.download_description') }}
                </p>

            </div>


            {{-- Step 4 --}}

            <div class="step">

                <div class="step-number">
                    4
                </div>

                <h3>
                    {{ __('downloader.how_it_works.steps.save_title') }}
                </h3>

                <p>
                    {{ __('downloader.how_it_works.steps.save_description') }}
                </p>

            </div>

        </div>

    </section>


    {{-- =============================================================
         FAQ
    ============================================================= --}}

    <section class="faq-section">

        <h2>
            {{ __('downloader.faq.title') }}
        </h2>


        {{-- =========================================================
             UNIVERSAL FAQ
        ========================================================== --}}

        @if($isUniversal)

            <div class="faq-item">

                <h3>
                    {{ __('downloader.faq.universal.free_question') }}
                </h3>

                <p>
                    {{ __('downloader.faq.universal.free_answer') }}
                </p>

            </div>


            <div class="faq-item">

                <h3>
                    {{ __('downloader.faq.universal.multiple_question') }}
                </h3>

                <p>
                    {{ __('downloader.faq.universal.multiple_answer') }}
                </p>

            </div>


            <div class="faq-item">

                <h3>
                    {{ __('downloader.faq.universal.watermark_question') }}
                </h3>

                <p>
                    {{ __('downloader.faq.universal.watermark_answer') }}
                </p>

            </div>


        {{-- =========================================================
             TIKTOK FAQ
        ========================================================== --}}

        @elseif($isTikTok)

            <div class="faq-item">

                <h3>
                    {{ __('downloader.faq.tiktok.free_question') }}
                </h3>

                <p>
                    {{ __('downloader.faq.tiktok.free_answer') }}
                </p>

            </div>


            <div class="faq-item">

                <h3>
                    {{ __('downloader.faq.tiktok.watermark_question') }}
                </h3>

                <p>
                    {{ __('downloader.faq.tiktok.watermark_answer') }}
                </p>

            </div>


            <div class="faq-item">

                <h3>
                    {{ __('downloader.faq.tiktok.phone_question') }}
                </h3>

                <p>
                    {{ __('downloader.faq.tiktok.phone_answer') }}
                </p>

            </div>


        {{-- =========================================================
             X FAQ
        ========================================================== --}}

        @elseif($isX)

            <div class="faq-item">

                <h3>
                    {{ __('downloader.faq.x.free_question') }}
                </h3>

                <p>
                    {{ __('downloader.faq.x.free_answer') }}
                </p>

            </div>


            <div class="faq-item">

                <h3>
                    {{ __('downloader.faq.x.phone_question') }}
                </h3>

                <p>
                    {{ __('downloader.faq.x.phone_answer') }}
                </p>

            </div>


            <div class="faq-item">

                <h3>
                    {{ __('downloader.faq.x.install_question') }}
                </h3>

                <p>
                    {{ __('downloader.faq.x.install_answer') }}
                </p>

            </div>


        {{-- =========================================================
             YOUTUBE FAQ
        ========================================================== --}}

        @elseif($isYouTube)

            <div class="faq-item">

                <h3>
                    {{ __('downloader.faq.youtube.free_question') }}
                </h3>

                <p>
                    {{ __('downloader.faq.youtube.free_answer') }}
                </p>

            </div>


            <div class="faq-item">

                <h3>
                    {{ __('downloader.faq.youtube.hd_question') }}
                </h3>

                <p>
                    {{ __('downloader.faq.youtube.hd_answer') }}
                </p>

            </div>


            <div class="faq-item">

                <h3>
                    {{ __('downloader.faq.youtube.mp3_question') }}
                </h3>

                <p>
                    {{ __('downloader.faq.youtube.mp3_answer') }}
                </p>

            </div>


        {{-- =========================================================
             INSTAGRAM FAQ
        ========================================================== --}}

        @elseif($isInstagram)

            <div class="faq-item">

                <h3>
                    {{ __('downloader.faq.instagram.free_question') }}
                </h3>

                <p>
                    {{ __('downloader.faq.instagram.free_answer') }}
                </p>

            </div>


            <div class="faq-item">

                <h3>
                    {{ __('downloader.faq.instagram.reels_question') }}
                </h3>

                <p>
                    {{ __('downloader.faq.instagram.reels_answer') }}
                </p>

            </div>


            <div class="faq-item">

                <h3>
                    {{ __('downloader.faq.instagram.phone_question') }}
                </h3>

                <p>
                    {{ __('downloader.faq.instagram.phone_answer') }}
                </p>

            </div>


        {{-- =========================================================
             FACEBOOK FAQ
        ========================================================== --}}

        @elseif($isFacebook)

            <div class="faq-item">

                <h3>
                    {{ __('downloader.faq.facebook.free_question') }}
                </h3>

                <p>
                    {{ __('downloader.faq.facebook.free_answer') }}
                </p>

            </div>


            <div class="faq-item">

                <h3>
                    {{ __('downloader.faq.facebook.hd_question') }}
                </h3>

                <p>
                    {{ __('downloader.faq.facebook.hd_answer') }}
                </p>

            </div>


            <div class="faq-item">

                <h3>
                    {{ __('downloader.faq.facebook.phone_question') }}
                </h3>

                <p>
                    {{ __('downloader.faq.facebook.phone_answer') }}
                </p>

            </div>

        @endif


        {{-- =========================================================
             COMMON FAQ
        ========================================================== --}}

        <div class="faq-item">

            <h3>
                {{ __('downloader.faq.common.install_question') }}
            </h3>

            <p>
                {{ __('downloader.faq.common.install_answer') }}
            </p>

        </div>


        <div class="faq-item">

            <h3>
                {{ __('downloader.faq.common.phone_question') }}
            </h3>

            <p>
                {{ __('downloader.faq.common.phone_answer') }}
            </p>

        </div>

    </section>


    {{-- =============================================================
         PASTE BUTTON
    ============================================================= --}}

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const pasteButton =
                document.getElementById('paste-url-button');

            const urlInput =
                document.getElementById('download-url');


            if (!pasteButton || !urlInput) {
                return;
            }


            pasteButton.addEventListener('click', async function () {

                try {

                    const text =
                        await navigator.clipboard.readText();


                    if (!text) {
                        return;
                    }


                    urlInput.value = text.trim();


                    urlInput.dispatchEvent(
                        new Event('input', {
                            bubbles: true
                        })
                    );


                    pasteButton.textContent =
                        '✓ {{ __('downloader.pasted') }}';


                    setTimeout(function () {

                        pasteButton.textContent =
                            '{{ __('downloader.paste') }}';

                    }, 1500);


                } catch (error) {

                    console.error(
                        'Unable to read clipboard:',
                        error
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Fallback
                    |--------------------------------------------------------------------------
                    */

                    urlInput.focus();

                }

            });

        });

    </script>


    {{-- =============================================================
         DOWNLOAD FORM LOADING STATE
    ============================================================= --}}

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const downloadForm =
                document.querySelector('.download-form');

            const downloadButton =
                document.getElementById(
                    'download-submit-button'
                );


            if (!downloadForm || !downloadButton) {
                return;
            }


            downloadForm.addEventListener(
                'submit',
                function () {

                    downloadButton.disabled = true;

                    downloadButton.classList.add(
                        'is-loading'
                    );


                    const text =
                        downloadButton.querySelector(
                            '.download-button-text'
                        );


                    if (text) {

                        text.textContent =
                            '{{ __('downloader.loading') }}';

                    }

                }
            );

        });

    </script>

</div>