@extends('layouts.app')

@section('title', __('home.meta_title'))

@section('meta_description', __('home.meta_description'))

@section('canonical', url('/'))

@section('content')

@php

    $platform = 'universal';

    $pageTitle = __('home.title');

    $pageDescription = __('home.description');

    $inputPlaceholder = __('home.input_placeholder');

@endphp

@include('downloader.content')

@endsection