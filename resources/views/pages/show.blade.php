@extends('layouts.admin.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header flex space-between items-center">
                        <p>Manage Pages </p>
                        <a href="{{ route('pages.showCreate') }}">
                            <button class="btn btn-success pull-right">Create New</button>
                        </a>
                    </div>

                    <div class="card-body">
                        @if(count($pages))
                            @foreach($pages as $page)
                                <p><a href="{{ route('pages.showEdit', $page->id) }}">{{ $page->uri }}</a></p>
                            @endforeach
                        @else
                            <p>No Pages</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
