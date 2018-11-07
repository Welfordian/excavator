@extends('layouts.admin.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header flex space-between items-center">
                        <p>Create Middleware</p>
                    </div>

                    <div class="card-body">
                        <form method="POST">
                            <input class="form-control" type="text" name="name" placeholder="Middleware Name" />
                            <select id="listed-routes" multiple="multiple">
                                @foreach ($routes as $route)
                                    <option value="{{ $route->id }}">{{ $route->uri }}</option>
                                @endforeach
                            </select>

                            <p class="available-vars mt-2">
                                <span>Variables available for use: </span>
                                <code class="badge badge-primary">$request</code>
                                <code class="badge badge-primary">$next</code>
                            </p>

                            <div id="editor"></div>

                            <input type="hidden" name="logic" />

                            @csrf

                            <button class="btn btn-success mt-2">Create Middleware</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.1/ace.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>

    <script>
        var editor = ace.edit("editor");
        editor.setTheme("ace/theme/chrome");
        editor.session.setMode({path:"ace/mode/php", inline:true});

        $('form').submit(function() {
            $('[name="logic"]').val(editor.getValue());
        });

        $(document).ready(function() {
            $('#listed-routes').multiselect({
                buttonClass: 'btn btn-primary mt-2',
                buttonText: function() {
                    return 'Select Routes'
                },
                checkboxName: function(option) {
                    return 'routes[]';
                },
                numberDisplayed: 0
            });
        });
    </script>
@endsection

@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css" type="text/css">
    <style>
        .multiselect-container {
            width: 500px;
        }

        #editor {
            margin-top: 0.5em;
            width: 100%;
            height: 500px;
        }

        code {
            font-size: 100% !important;
            color: #ffffff;
            word-break: break-word;
            font-weight: normal !important;
        }
    </style>
@endsection
