@extends('layouts.app')

@section('title', 'Free Video Downloader | Download TikTok & X Videos')

@section('meta_description', 'Download TikTok and X videos quickly in HD quality with SaveTikVideo. Free online video downloader with no login, no installation and simple video downloads.')

@section('canonical', url('/'))

@section('content')

@php
    $platform = 'universal';

    $pageTitle = 'Free TikTok & X Video Downloader';

    $pageDescription =
        'Download TikTok and X videos quickly in HD quality. No installation, no login and no complicated steps.';

    $inputPlaceholder =
        'Paste TikTok or X video link here...';
@endphp

@include('downloader.content')

@endsection