@extends('layouts.app')

@section('title', 'Facebook Video Downloader | Download Facebook Videos')

@section(
    'meta_description',
    'Download Facebook videos in high quality. Save public Facebook videos quickly without installing an app or creating an account.'
)

@section('canonical', url('/facebook-downloader'))

@section('content')

@php

    $platform = 'facebook';

    $pageTitle = 'Facebook Video Downloader';

    $pageDescription =
        'Download Facebook videos quickly in high quality. No installation, no login and no complicated steps.';

    $inputPlaceholder =
        'Paste Facebook video link here...';

@endphp

@include('downloader.content')

@endsection