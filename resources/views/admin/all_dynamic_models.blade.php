@extends('layouts.admin.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header flex space-between items-center">
                        <p>Manage {{ ucwords(str_replace('_', ' ', $table)) }}</p>
                        <a href="/admin/{{ $table }}/create">
                            <button class="btn btn-success pull-right">Create New</button>
                        </a>
                    </div>

                    <div class="card-body">
                        @foreach($models as $model)
                            <p>
                                <a href="/admin/{{ $table }}/{{ $model->getAttribute('id') }}">{{ $title_name }} #{{ $model->getAttribute('id') }}</a>
                            </p>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
