@extends('layouts.admin.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header flex space-between items-center">
                        <p>Manage Layouts</p>
                        <a href="{{ route('layouts.showCreate') }}">
                            <button class="btn btn-success pull-right">Create New</button>
                        </a>
                    </div>

                    <div class="card-body">
                        @if(count($layouts))
                            @foreach($layouts as $layout)
                                <p><a href="{{ route('layouts.showEdit', $layout->id) }}">{{ $layout->name }}</a></p>
                            @endforeach
                        @else
                            <p>No Layouts</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
