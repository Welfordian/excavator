@extends('layouts.admin.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header flex space-between items-center">
                        <p>Manage Resources</p>
                        <a href="{{ route('resources.showCreate') }}">
                            <button class="btn btn-success pull-right">Create New</button>
                        </a>
                    </div>

                    <div class="card-body">
                        @if(count($resources))
                            @foreach($resources as $resource)
                                <p><a href="{{ route('resources.showEdit', $resource->id) }}">{{ $resource->name }}</a></p>
                            @endforeach
                        @else
                            <p>No Resources</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
