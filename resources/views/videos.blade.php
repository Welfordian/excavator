@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Videos</div>

                <div class="card-body">
                    @foreach($videos as $video)
                        <p>{{ $video->video_name }}</p>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection