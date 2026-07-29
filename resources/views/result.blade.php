@extends('layouts.app')

@section('content')

<div class="container mt-5">

    <h2>Download Result</h2>

    <table class="table">

        <tr>

            <th>Success</th>

            <td>{{ $result->success ? 'Yes' : 'No' }}</td>

        </tr>

        <tr>

            <th>Video</th>

            <td>{{ $result->videoUrl }}</td>

        </tr>

        <tr>

            <th>Title</th>

            <td>{{ $result->title }}</td>

        </tr>

        <tr>

            <th>Author</th>

            <td>{{ $result->author }}</td>

        </tr>

    </table>

</div>

@endsection