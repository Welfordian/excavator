@extends('layouts.app')

@section('content')
    <div class="container">
        @foreach($posts as $post)
            <a href="/posts/{{ $post->id }}">{{ $post->title }}</a>
        @endforeach
    </div>
@endsection