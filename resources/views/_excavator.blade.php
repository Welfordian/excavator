@extends('layouts.excavator')
@section('content')
<h1>Welcome to excavator!</h1>
<p>Our videos:-</p>
@foreach($videos as $video)
    <p>{{ $video->video_name }}</p>
@endforeach
@endsection