@extends('layouts.app')

@section('title', 'Free Video Downloader | Download TikTok & X Videos')

@section('meta_description', 'Download TikTok and X videos quickly in HD quality with SaveTikVideo. Free online video downloader with no login, no installation and simple video downloads.')

@section('canonical', url('/'))

@section('content')

@php
    $platform = 'universal';

    $pageTitle = 'Free TikTok, X, Instagram  & Facebook Video Downloader';

    $pageDescription =
        'Download TikTok, X, Instagram and Facebook videos quickly in HD quality. No installation, no login and no complicated steps.';

    $inputPlaceholder =
        'Paste TikTok, X, Instagram or Facebook video link here...';
@endphp

@include('downloader.content')

@endsection