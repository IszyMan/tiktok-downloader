@extends('layouts.app')

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | Platform / Preview Helpers
    |--------------------------------------------------------------------------
    |
    | The controller already uses VideoProviderManager to detect the
    | platform. Here we only use the returned DTO to adjust the UI.
    |
    */

    $isX = isset($video) && $video->provider === 'ytdlp-x';

    $isTikTok = isset($video) && ! $isX;

    $thumbnail = null;

    if (isset($video)) {
        $thumbnail =
            $video->media->cover
            ?? $video->media->thumbnail
            ?? null;
    }

    $platformName = $isX ? 'X Video' : 'TikTok Video';
@endphp


<div class="hero-content">

    <div class="hero-inner">

        {{-- =========================================================
             INITIAL DOWNLOAD FORM
        ========================================================== --}}

        @if(!isset($video))

            <span class="hero-badge">
                TikTok & X • Fast • HD Quality
            </span>

            <h1>
                TikTok & X Video Downloader
            </h1>

            <p>
                Download videos from TikTok and X in HD quality.
                No installation, no login and no complicated steps.
            </p>


            {{-- Validation / Error Message --}}

            @if ($errors->any())

                <div class="error-box">
                    {{ $errors->first() }}
                </div>

            @endif


            {{-- Download Form --}}

            <form
                action="{{ route('download') }}"
                method="POST"
                class="download-form">

                @csrf

                <input
                    type="url"
                    name="url"
                    value="{{ old('url') }}"
                    placeholder="Paste TikTok or X video link here..."
                    required>

                <button type="submit">
                    Download
                </button>

            </form>


            {{-- Supported Platforms --}}

            <div class="supported-platforms">

                <span>
                    Supports:
                </span>

                <span class="platform-pill">
                    TikTok
                </span>

                <span class="platform-pill">
                    X
                </span>

            </div>


        {{-- =========================================================
             VIDEO PREVIEW
        ========================================================== --}}

        @else

            <span class="hero-badge">
                {{ $platformName }} Ready
            </span>

            <p class="preview-description">
                Your video has been detected.
                Preview it below and choose how you'd like to download it.
            </p>


            <div class="preview-card">


                {{-- =================================================
                     BACKGROUND IMAGE
                ================================================== --}}

                @if($thumbnail)

                    <img
                        class="preview-background"
                        src="{{ $thumbnail }}"
                        alt="{{ $video->title ?? $platformName }}"
                        loading="lazy">

                @endif


                <div class="preview-overlay">


                    {{-- =================================================
                         PLATFORM LABEL
                    ================================================== --}}

                    <div class="preview-platform">

                        <span class="platform-label">

                            {{ $platformName }}

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
                                    alt="{{ $video->title ?? $platformName }}"
                                    loading="lazy">

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
                                            loading="lazy">

                                    @endif

                                    <div>

                                        <strong>

                                            {{ $video->author->nickname
                                                ?? $video->author->username
                                                ?? 'Unknown creator' }}

                                        </strong>

                                        @if(!empty($video->author->username))

                                            <small>
                                                @{{ $video->author->username }}
                                            </small>

                                        @endif

                                    </div>

                                </div>

                            @endif


                            {{-- =================================================
                                 VIDEO STATS
                            ================================================== --}}

                            @if(isset($video->statistics))

                                <div class="video-stats">

                                    <span>
                                        ▶
                                        {{ number_format($video->statistics->views ?? 0) }}
                                    </span>

                                    <span>
                                        ❤️
                                        {{ number_format($video->statistics->likes ?? 0) }}
                                    </span>

                                    <span>
                                        🔁
                                        {{ number_format($video->statistics->shares ?? 0) }}
                                    </span>

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

                        @if(!empty($video->width) && !empty($video->height))

                            <span>
                                🎥
                                {{ $video->width }}×{{ $video->height }}
                            </span>

                        @endif

                        @if($isX)

                            <span>
                                X
                            </span>

                        @else

                            <span>
                                TikTok
                            </span>

                        @endif

                    </div>


                    {{-- =================================================
                         DOWNLOAD BUTTONS
                    ================================================== --}}

                    <div class="download-buttons">


                        {{-- =================================================
                             X DOWNLOAD
                             X currently does not provide a separate
                             watermark download.
                        ================================================== --}}

                        @if($isX)

                            <form
                                method="POST"
                                action="{{ route('download') }}">

                                @csrf

                                <input
                                    type="hidden"
                                    name="url"
                                    value="{{ $url }}">

                                <input
                                    type="hidden"
                                    name="action"
                                    value="hd">

                                <button
                                    type="submit"
                                    class="btn-primary">

                                    ⬇ Download HD

                                </button>

                            </form>


                        {{-- =================================================
                             TIKTOK DOWNLOAD
                        ================================================== --}}

                        @else

                            {{-- Watermark --}}

                            <form
                                method="POST"
                                action="{{ route('download') }}">

                                @csrf

                                <input
                                    type="hidden"
                                    name="url"
                                    value="{{ $url }}">

                                <input
                                    type="hidden"
                                    name="action"
                                    value="watermark">

                                <button
                                    type="submit"
                                    class="btn-primary">

                                    ⬇ Download (Watermark)

                                </button>

                            </form>


                            {{-- HD / No Watermark --}}

                            <form
                                method="POST"
                                action="{{ route('download') }}">

                                @csrf

                                <input
                                    type="hidden"
                                    name="url"
                                    value="{{ $url }}">

                                <input
                                    type="hidden"
                                    name="action"
                                    value="hd">

                                <button
                                    type="submit"
                                    class="btn-secondary">

                                    ⬇ Download HD (No Watermark)

                                </button>

                            </form>

                        @endif


                        {{-- Download Another --}}

                        <a
                            href="{{ route('home') }}"
                            class="btn-outline">

                            Download Another Video

                        </a>

                    </div>


                </div>

            </div>

        @endif

    </div>

</div>



{{-- ================================================================
     FEATURES
================================================================ --}}

<div class="features-card">


    <div class="feature-item">

        <span>
            ⚡
        </span>

        <div>

            <h3>
                Fast Download
            </h3>

            <p>
                Download TikTok and X videos in seconds.
            </p>

        </div>

    </div>


    <div class="divider"></div>


    <div class="feature-item">

        <span>
            🎥
        </span>

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

        <span>
            🔒
        </span>

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



{{-- ================================================================
     HOW IT WORKS
================================================================ --}}

<section class="how-it-works">

    <h2>
        How to Download TikTok & X Videos
    </h2>

    <p class="section-description">
        Download videos in just four simple steps.
    </p>


    <div class="steps">


        {{-- Step 1 --}}

        <div class="step">

            <div class="step-number">
                1
            </div>

            <h3>
                Copy Link
            </h3>

            <p>
                Open TikTok or X and copy the video URL.
            </p>

        </div>


        {{-- Step 2 --}}

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


        {{-- Step 3 --}}

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


        {{-- Step 4 --}}

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



{{-- ================================================================
     FAQ
================================================================ --}}

<section class="faq-section">

    <h2>
        Frequently Asked Questions
    </h2>


    {{-- FAQ 1 --}}

    <div class="faq-item">

        <h3>
            Is the TikTok and X downloader free?
        </h3>

        <p>
            Yes. You can use the downloader to download supported
            TikTok and X videos without creating an account.
        </p>

    </div>


    {{-- FAQ 2 --}}

    <div class="faq-item">

        <h3>
            Can I download X videos?
        </h3>

        <p>
            Yes. Paste a public X video URL and the downloader
            will automatically detect the platform.
        </p>

    </div>


    {{-- FAQ 3 --}}

    <div class="faq-item">

        <h3>
            Can I download TikTok videos without a watermark?
        </h3>

        <p>
            Yes. When a no-watermark version is available,
            you can choose the HD no-watermark download option.
        </p>

    </div>


    {{-- FAQ 4 --}}

    <div class="faq-item">

        <h3>
            Can I use the downloader on my phone?
        </h3>

        <p>
            Yes. The downloader works on Android, iPhone,
            tablets and desktop browsers.
        </p>

    </div>


    {{-- FAQ 5 --}}

    <div class="faq-item">

        <h3>
            Do I need to install anything?
        </h3>

        <p>
            No. Everything works directly in your web browser.
            No application or browser extension is required.
        </p>

    </div>


</section>

@endsection