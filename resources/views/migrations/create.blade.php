@extends('layouts.admin.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header flex space-between items-center">
                        <p>Create Migration</p>
                    </div>

                    <div class="card-body">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active pane-link" id="pills-create-tab" data-toggle="pill" href="#pills-create" role="tab" aria-controls="pills-home" aria-selected="true">Create Table</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link pane-link" id="pills-update-tab" data-toggle="pill" href="#pills-update" role="tab" aria-controls="pills-profile" aria-selected="false">Update Table</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link pane-link" id="pills-drop-tab" data-toggle="pill" href="#pills-drop" role="tab" aria-controls="pills-contact" aria-selected="false">Drop Table</a>
                            </li>
                        </ul>

                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-create" role="tabpanel" aria-labelledby="pills-home-tab">
                                <form method="POST" id="migration_form-create">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input name="migration_name" type="text" class="form-control migration_name-create" placeholder="Migration name (required)" />
                                        </div>
                                        <div class="col-md-6">
                                            <input name="migration_table_name" type="text" class="form-control migration_table_name-create" placeholder="Table Name (required)" />
                                        </div>
                                    </div>

                                    <div class="row migration_buttons">
                                        <div class="col-md-6">
                                            <button id="add_migration_row-create" class="btn btn-secondary mt-2 full-width"><i class="far fa-plus"></i> Add New Column</button>
                                        </div>

                                        <div class="col-md-6">
                                            <button id="add_timestamps-create" class="btn btn-secondary mt-2 full-width"><i class="far fa-plus"></i> Add Timestamps</button>
                                        </div>
                                    </div>

                                    <div id="migration_rows-create" class="mt-2">

                                    </div>

                                    @csrf

                                    <input type="hidden" name="migration_method" value="create" />

                                    <div class="custom-control custom-checkbox mt-2 mb-2">
                                        <input name="run_migration" type="checkbox" class="custom-control-input" id="customCheck1">
                                        <label class="custom-control-label" for="customCheck1">Run Migration Immediately</label>
                                    </div>

                                    <button class="btn btn-success create_migration hidden">Create Migration</button>
                                </form>
                            </div>



                            <div class="tab-pane fade" id="pills-update" role="tabpanel" aria-labelledby="pills-profile-tab">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input name="migration_name" type="text" class="form-control migration_name-update" placeholder="Migration name" />
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-control migration_table_name-update">
                                            @foreach($tables as $table)
                                                <option value="{{ $table }}">{{ $table }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row migration_buttons">
                                    <div class="col-md-6">
                                        <button id="add_migration_row-update" class="btn btn-secondary mt-2 full-width"><i class="far fa-plus"></i> Add New Column</button>
                                    </div>

                                    <div class="col-md-6">
                                        <button id="add_timestamps-update" class="btn btn-secondary mt-2 full-width"><i class="far fa-plus"></i> Add Timestamps</button>
                                    </div>
                                </div>

                                <div id="migration_rows-update" class="mt-2">

                                </div>
                            </div>



                            <div class="tab-pane fade" id="pills-drop" role="tabpanel" aria-labelledby="pills-contact-tab">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input name="migration_name" type="text" class="form-control" placeholder="Migration name" />
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-control">
                                            @foreach($tables as $table)
                                                <option value="{{ $table }}">{{ $table }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        </div>

                        <div id="migration_row_template" class="hidden">
                            <div class="migration_row row mb-2">
                                <div class="col-md-3">
                                    <select name="migration[]['migration_type']" class="migration_type form-control">
                                        <option value="string" data-default-allowed="true">String</option>
                                        <option value="text" data-default-allowed="false">Text</option>
                                        <option value="integer" data-default-allowed="true">Integer</option>
                                        <option value="boolean" data-default-allowed="false">Boolean</option>
                                        <option value="bigInteger" data-default-allowed="false">Big Integer</option>
                                        <option value="json" data-default-allowed="false">JSON</option>
                                        <option value="date" data-default-allowed="false">Date</option>
                                        <option value="dateTime" data-default-allowed="false">DateTime</option>
                                        <option value="dateTimeTz" data-default-allowed="false">DateTimeTz</option>
                                        <option value="timestamp" data-default-allowed="false">Timestamp</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <input type="text" class="form-control migration_name" placeholder="Column name" />
                                </div>

                                <div class="col-md-4">
                                    <input type="text" class="form-control migration_default_value" placeholder="Default value" />
                                </div>

                                <div class="col-md-1">
                                    <i class="far fa-minus-square remove_migration_row"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(() => {
            const migrations = app.migrations({
                all_tables: {!! json_encode($all_tables) !!},
                table_columns: {!! json_encode($table_columns) !!}
            });

            migrations.create().update().drop();
        });
    </script>
@endsection

@section('style')
    <style>
        .remove_migration_row {
            font-size: 1.3em;
            margin-top: 0.5em;
            color: #e3342f;
        }

        .migration_buttons .btn i {
            margin-right: .5em;
        }

        input:disabled {
            cursor: not-allowed;
        }

        .popover-header {
            padding: 0.5rem 0.75rem;
            margin-bottom: 0;
            font-size: 0.8rem !important;
            color: inherit;
            background-color: #f7f7f7;
            border-bottom: 1px solid #ebebeb;
            border-top-left-radius: calc(0.3rem - 1px);
            border-top-right-radius: calc(0.3rem - 1px);
        }
    </style>
@endsection
