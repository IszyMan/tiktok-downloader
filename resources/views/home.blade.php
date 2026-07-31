@extends('layouts.app')

@section('content')

<section class="hero">

    <div class="hero-content">

        <div class="hero-inner">

            <span class="hero-badge">
                No Watermark • Fast • HD Quality
            </span>

            <h1>
                TikTok Video Downloader
            </h1>

            <p>
                Download TikTok videos without watermark for free.
                No installation, no login and no limits.
            </p>

            @if ($errors->any())
                <div class="error-box">
                    {{ $errors->first() }}
                </div>
            @endif

            <form
                action="{{ route('download') }}"
                method="POST"
                class="download-form">

                @csrf

                <input
                    type="url"
                    name="url"
                    value="{{ old('url') }}"
                    placeholder="Paste TikTok URL here..."
                    required>

                <button type="submit">
                    Download
                </button>

            </form>

        </div>

    </div>

</section>



<section class="features-section">

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

                    Download videos in seconds.

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

                    Best quality available.

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

                    Just paste your link.

                </p>

            </div>

        </div>

    </div>

</section>



<section class="how-section">

    <h2>

        How to Download TikTok Videos

    </h2>

    <p class="section-description">

        Download your favorite TikTok videos in just four simple steps.

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

                Open TikTok and copy the video URL.

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

                Paste the copied link into the download box.

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

                Click the Download button.

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

                Save your video to your device.

            </p>

        </div>

    </div>

</section>



<section class="faq">

    <h2>

        Frequently Asked Questions

    </h2>

    <div class="faq-item">

        <h3>

            Is this downloader free?

        </h3>

        <p>

            Yes. You can download TikTok videos completely free.

        </p>

    </div>

    <div class="faq-item">

        <h3>

            Can I use it on mobile?

        </h3>

        <p>

            Yes. It works perfectly on Android, iPhone, tablets and desktop browsers.

        </p>

    </div>

    <div class="faq-item">

        <h3>

            Do I need to install anything?

        </h3>

        <p>

            No installation is required. Everything runs directly in your browser.

        </p>

    </div>

</section>

@endsection