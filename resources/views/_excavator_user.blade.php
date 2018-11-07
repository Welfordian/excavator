@extends('layouts.excavator')
@section('content')
<h1>Welcome to excavator!</h1>
{{ $user->name }}

<h2>Other users</h2>
@foreach($users as $user)
    {{ $user->name }}
@endforeach


@endsection