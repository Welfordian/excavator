@extends('layouts.admin.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Create Page</div>

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

                            <p class="available-vars mt-2">
                                <span>Variables available for use: </span>
                                <span id="available-vars"></span>
                            </p>

                            <div id="editor" class="mt-2"></div>

                            <input type="hidden" name="template">

                            @csrf

                            <button class="btn btn-success mt-2">Create Page</button>
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
        $(() => {
            let requiredModels = [];
            let uriModels = [];

            let citynames = new Bloodhound({
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

            $('.tagahead').on('beforeItemAdd', function(event) {
                if (! Object.values(citynames.index.datums).map(a => a.name.toLowerCase()).includes(event.item.toLowerCase())) {
                    event.cancel = true;
                } else {
                    if (! requiredModels.includes(event.item.toLowerCase()) && !uriModels.includes(event.item.toLowerCase())) {
                        requiredModels.push(event.item.toLowerCase() + 's');
                    }
                }

                renderIncludedModels();
            });

            $('.tagahead').on('beforeItemRemove', function(event) {
                if (! uriModels.includes(event.item.toLowerCase())) {
                    delete requiredModels[event.item.toLowerCase()];
                }
            });

            let editor = ace.edit("editor");
            editor.setTheme("ace/theme/chrome");
            editor.session.setMode("ace/mode/php_laravel_blade");

            function renderIncludedModels() {
                let includedModels = requiredModels.concat(uriModels);

                $('#available-vars').html('');

                includedModels.forEach((model) => {
                    $('#available-vars').append(`<code class="badge badge-primary ml-2">$${model}</code>`);
                })
            }

            $('form').submit(function() {
                $('[name="template"]').val(editor.getValue());
            });

            $('[name="uri"]').on('keyup', function() {
                uriModels = [];
                let modelPattern = /(?<=\{)(.*?)(?=\:)/g;
                let uri = $(this).val();

                if (/(?<=\{)(.*?)(?=\:)/g.test(uri) && /(?<=\{)(.*?)(?=\:)/.exec(uri)[1].trim().length) {
                    let match;

                    while ((match = modelPattern.exec(uri)) != null) {
                        if (! uriModels.includes(match[1].toLowerCase()) && ! requiredModels.includes(match[1].toLowerCase())) {
                            uriModels.push(match[1].toLowerCase());
                        }
                    }

                    renderIncludedModels();
                }
            });
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

        code {
            font-size: 100% !important;
            color: #ffffff;
            word-break: break-word;
            font-weight: normal !important;
        }
    </style>
@endsection
