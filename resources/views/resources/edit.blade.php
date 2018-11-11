@extends('layouts.admin.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">New Resource</div>

                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <input name="name" type="text" class="form-control" placeholder="Resource Name" value="{{ $resource->name }}">

                            <div class="custom-control custom-checkbox mt-2">
                                <input name="secure" type="checkbox" class="custom-control-input" id="customCheck1" @if ($resource->secure) checked @endif >
                                <label class="custom-control-label" for="customCheck1">Requires Authentication</label>
                            </div>

                            @csrf

                            <p class="mt-2 flex space-between items-center">
                                <a href="/resource/{{ $resource->uuid }}">Download File</a>
                                <a id="copy-resource" href="/resource/{{ $resource->uuid }}" title="Some tooltip text!" data-toggle="tooltip" data-trigger="click">Share File</a>
                            </p>

                            <button class="btn btn-success mt-2">Create</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.0/clipboard.min.js"></script>

    <script>

        $(function() {

            $('#copy-resource').attr('data-clipboard-text', 'https://' + window.location.host + '/resource/{{ $resource->uuid }}');

            $('input[type="file"]').on('change', function() {
                $('#file-name').val($(this).val().match(/[^\\/]*$/)[0]);
            });

            $('#copy-resource').click((e) => {
                e.preventDefault();

                new Noty({
                    text: 'Link copied to clipboard!',
                    type: 'success',
                    theme: 'sunset',
                    timeout: 3000
                }).show();
            });

            new ClipboardJS('#copy-resource');

        });

    </script>
@endsection

@section('style')
    <style>
        .upload .input-group-btn {
            margin-bottom: unset;
        }

        .upload .btn {
            border-top-right-radius: 0px;
            border-bottom-right-radius: 0px;
        }
    </style>
@endsection
