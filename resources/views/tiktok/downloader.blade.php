@extends('layouts.app')

@section('title', 'TikTok Video Downloader | Download TikTok Videos Without Watermark')

@section('meta_description', 'Download TikTok videos quickly in HD quality without watermark. Save TikTok videos on your phone or computer for free with SaveTikVideo.')

@section('canonical', url('/tiktok-downloader'))

@section('content')

@php
    $platform = 'tiktok';

    $pageTitle = 'TikTok Video Downloader';

    $pageDescription =
        'Download TikTok videos quickly in HD quality. No installation, no login and no complicated steps.';

    $inputPlaceholder =
        'Paste TikTok video link here...';
@endphp

@include('downloader.content')

@endsection