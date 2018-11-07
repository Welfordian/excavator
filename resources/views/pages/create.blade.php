@extends('layouts.admin.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">New Page</div>

                    <div class="card-body">
                        <form method="POST">
                            <input name="uri" type="text" class="form-control" placeholder="Page URI">
                            <input name="with" class="tagahead mt-2" placeholder="Models">
                            <select name="layout" class="form-control mt-2">
                                <option value="0">Select a layout</option>
                                @foreach($layouts as $layout)
                                    <option value="{{ $layout->id }}">{{ $layout->name }}</option>
                                @endforeach
                            </select>

                            <div id="editor" class="mt-2"></div>

                            <input type="hidden" name="template">

                            @csrf


                            <button class="btn btn-success mt-2">Create</button>
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
        var citynames = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            prefetch: {
                url: '{{ route('listModels') }}',
                filter: function(list) {
                    return $.map(list, function(model) {
                        return { name: model }; });
                },
                cache: false,
            }
        });
        citynames.initialize();

        $('.tagahead').tagsinput({
            placeholder: 'Models',
            typeaheadjs: {
                name: 'citynames',
                displayKey: 'name',
                valueKey: 'name',
                source: citynames.ttAdapter()
            }
        });

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
