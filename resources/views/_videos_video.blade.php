@extends('layouts.brandon')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Videos</div>
    
                    <div class="card-body">
                        {{ $video->video_name }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection