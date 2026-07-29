@extends('layouts.app')

@section('content')

<div class="container mt-5">

    <h1>TikTok Downloader</h1>

    @if ($errors->any())

        <div class="alert alert-danger">

            {{ $errors->first() }}

        </div>

    @endif

    <form method="POST" action="{{ route('download') }}">

        @csrf

        <div class="mb-3">

            <input
                type="text"
                name="url"
                class="form-control"
                placeholder="Paste TikTok URL here"
                value="{{ old('url') }}">

        </div>

        <button class="btn btn-primary">

            Download

        </button>

    </form>

</div>

@endsection