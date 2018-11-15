@extends('layouts.admin.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header flex space-between items-center">
                        <p>Update Layout</p>
                    </div>

                    <div class="card-body">
                        <form method="POST">
                            <input class="form-control" type="text" name="name" placeholder="Layout Name" value="{{ $layout->name }}" />

                            <div id="editor">{{ $layout->template }}</div>

                            <input type="hidden" name="template" />

                            @csrf

                            <button class="btn btn-success mt-2">Update Layout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.1/ace.js"></script>

    <script>
        var editor = ace.edit("editor");
        editor.setTheme("ace/theme/chrome");
        editor.session.setMode("ace/mode/php_laravel_blade");

        $('form').submit(function() {
            $('[name="template"]').val(editor.getValue());
        });
    </script>
@endsection

@section('style')
    <style>

        #editor {
            margin-top: 0.5em;
            width: 100%;
            height: 500px;
        }
    </style>
@endsection
