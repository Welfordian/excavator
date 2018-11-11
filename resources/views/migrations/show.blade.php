@extends('layouts.admin.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header flex space-between items-center">
                        <p>Manage Migrations</p>
                        <a href="{{ route('migrations.showCreate') }}">
                            <button class="btn btn-success pull-right">Create New</button>
                        </a>
                    </div>

                    <div class="card-body">
                        @if(count($migrations))
                            @foreach($migrations as $migration)
                                <p><a href="{{ route('migrations.showEdit', $migration->id) }}">{{ $migration->name }}</a></p>
                            @endforeach
                        @else
                            <p>No Migrations</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
