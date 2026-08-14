@extends('layouts.app')

@section('title', 'Instagram Video Downloader | Download Instagram Videos')

@section(
    'meta_description',
    'Download Instagram videos and reels in high quality. Save Instagram videos quickly without installing an app.'
)

@section('canonical', url('/instagram-downloader'))

@section('content')

@php
    $platform = 'instagram';

    $pageTitle = 'Instagram Video Downloader';

    $pageDescription =
        'Download Instagram videos and reels quickly in high quality. No installation, no login and no complicated steps.';

    $inputPlaceholder =
        'Paste Instagram video or Reel link here...';
@endphp

@include('downloader.content')

@endsection