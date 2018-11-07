@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Manage Pages</div>

                <div class="card-body">
                    @foreach($pages as $page)
                        <a href=""
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
