@extends('layouts.app')

@section('title', 'X Video Downloader | Download Videos from X')

@section('meta_description', 'Download videos from X quickly and easily in HD quality. Save public X videos directly to your phone, tablet or computer with SaveTikVideo.')

@section('canonical', url('/x-downloader'))

@section('content')

@php
    $platform = 'x';

    $pageTitle = 'X Video Downloader';

    $pageDescription =
        'Download videos from X quickly in HD quality. No installation, no login and no complicated steps.';

    $inputPlaceholder =
        'Paste X video link here...';
@endphp

@include('downloader.content')

@endsection