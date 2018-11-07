@extends('layouts.admin.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header flex space-between items-center">
                        <p>Manage Middleware</p>
                        <a href="{{ route('middleware.showCreate') }}">
                            <button class="btn btn-success pull-right">Create New</button>
                        </a>
                    </div>

                    <div class="card-body">
                        @if(count($middlewares))
                            @foreach($middlewares as $middleware)
                                <p><a href="{{ route('middleware.showEdit', $middleware->id) }}">{{ $middleware->name }}</a></p>
                            @endforeach
                        @else
                            <p>No Middleware</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
