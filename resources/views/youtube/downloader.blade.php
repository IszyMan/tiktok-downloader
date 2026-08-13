@extends('layouts.app')

@section('title', 'YouTube Video Downloader | Download YouTube Videos in HD')
@section('meta_description', 'Download YouTube videos online in HD quality. Paste a YouTube video link and save')

@section('canonical', route('youtube.downloader'))

@section('content')

@php

    $platform = 'youtube';

    $pageTitle =
        'YouTube Video Downloader';

    $pageDescription =
        'Download YouTube videos in high quality. No installation, no login and no complicated steps.';

    $inputPlaceholder =
        'Paste YouTube video link here...';

@endphp

@include('downloader.content')


<div class="youtube-disclaimer">

    <p>
        <strong>Important:</strong>
        YouTube's Terms restrict downloading or reproducing
        content except where expressly authorized by YouTube
        or the rights holder. Only download content you are
        authorized to download.
    </p>

</div>

@endsection