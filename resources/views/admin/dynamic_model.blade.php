@extends('layouts.admin.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Create {{ $title_name }}</div>

                    <div class="card-body">
                        <form method="POST">
                            @foreach($columns as $column)
                                @if ($column['type'] === 'int' && $column['name'] !== 'id')
                                    <div class="form-group">
                                        <label for="{{ $column['name'] }}">{{ ucwords(str_replace('_', ' ', $column['name'])) }}</label>
                                        <input type="number" class="form-control" id="{{ $column['name'] }}" name="{{ $column['name'] }}"/>
                                    </div>
                                @endif

                                @if ($column['type'] === 'varchar' && $column['name'] !== 'remember_token')
                                        @if ($column['name'] === 'password')
                                            <div class="form-group">
                                                <label for="{{ $column['name'] }}">{{ ucwords(str_replace('_', ' ', $column['name'])) }}</label>
                                                <input type="password" class="form-control" id="{{ $column['name'] }}" name="{{ $column['name'] }}" />
                                            </div>
                                        @else
                                            <div class="form-group">
                                                <label for="{{ $column['name'] }}">{{ ucwords(str_replace('_', ' ', $column['name'])) }}</label>
                                                <input type="text" class="form-control" id="{{ $column['name'] }}" name="{{ $column['name'] }}" />
                                            </div>
                                        @endif
                                @endif

                                @if ($column['type'] === 'timestamp' && $column['name'] !== 'created_at' && $column['name'] !== 'updated_at' && $column['name'] !== 'email_verified_at')
                                    <div class="form-group">
                                        <label for="{{ $column['name'] }}">{{ ucwords(str_replace('_', ' ', $column['name'])) }}</label>
                                        <input type="date" class="form-control" id="{{ $column['name'] }}" name="{{ $column['name'] }}"/>
                                    </div>
                                @endif

                                @if ($column['type'] === 'text')
                                    <div class="form-group">
                                        <label for="{{ $column['name'] }}">{{ ucwords(str_replace('_', ' ', $column['name'])) }}</label>
                                        <textarea name="{{ $column['name'] }}" class="form-control" id="{{ $column['name'] }}"></textarea>
                                    </div>
                                @endif
                            @endforeach

                            @csrf

                            <input type="hidden" name="table_name" value="{{ $table }}" />
                            <button class="btn btn-success">Create {{ $title_name }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
