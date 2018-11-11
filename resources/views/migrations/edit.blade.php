@extends('layouts.admin.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header flex space-between items-center">
                        <p>Update Migration</p>
                    </div>

                    <div class="card-body">
                        <form method="POST" id="migration_form">
                            <div class="row">
                                <div class="col-md-6">
                                    <input name="migration_name" type="text" class="form-control" placeholder="Migration name" value="{{ $migration->name }}" />
                                </div>
                                <div class="col-md-6">
                                    <input name="migration_table_name" type="text" class="form-control" placeholder="Table Name" />
                                </div>
                            </div>

                            <div class="row migration_buttons">
                                <div class="col-md-4">
                                    <button id="add_migration_row" class="btn btn-primary mt-2 full-width"><i class="far fa-plus"></i> Add New Column</button>
                                </div>

                                <div class="col-md-4">
                                    <button id="add_incrementing_id" class="btn btn-primary mt-2 full-width"><i class="far fa-plus"></i> Add Incrementing ID</button>
                                </div>

                                <div class="col-md-4">
                                    <button id="add_timestamps" class="btn btn-primary mt-2 full-width"><i class="far fa-plus"></i> Add Timestamps</button>
                                </div>
                            </div>

                            <div id="migration_rows" class="mt-2">

                            </div>

                            @csrf

                            <div class="custom-control custom-checkbox mt-2 mb-2">
                                <input name="run_migration" type="checkbox" class="custom-control-input" id="customCheck1">
                                <label class="custom-control-label" for="customCheck1">Run Migration Immediately</label>
                            </div>

                            <button class="btn btn-success create_migration hidden">Create Migration</button>
                        </form>

                        <div id="migration_row_template" class="hidden">
                            <div class="migration_row row mb-2">
                                <div class="col-md-3">
                                    <select name="migration[]['migration_type']" class="migration_type form-control">
                                        <option value="string" data-default-allowed="true">String</option>
                                        <option value="integer" data-default-allowed="true">Integer</option>
                                        <option value="boolean" data-default-allowed="false">Boolean</option>
                                        <option value="bigInteger" data-default-allowed="false">Big Integer</option>
                                        <option value="json" data-default-allowed="false">JSON</option>
                                        <option value="date" data-default-allowed="false">Date</option>
                                        <option value="dateTime" data-default-allowed="false">DateTime</option>
                                        <option value="dateTimeTz" data-default-allowed="false">DateTimeTz</option>
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
        $(function() {
            let index = 0;

            $(document).on('click', '#add_migration_row', function (e) {
                e.preventDefault();

                let new_migration_row = $('#migration_row_template').clone();

                $(new_migration_row).find('.migration_type').attr('name', `migration_rows[${index}][migration_type]`);
                $(new_migration_row).find('.migration_name').attr('name', `migration_rows[${index}][migration_name]`);
                $(new_migration_row).find('.migration_default_value').attr('name', `migration_rows[${index}][migration_default_value]`);

                $('#migration_rows').append(new_migration_row.html());

                $('.create_migration').removeClass('hidden');

                index++;
            });

            $(document).on('click', '#add_incrementing_id', function(e) {
                e.preventDefault();
            });

            $(document).on('click', '#add_timestamps', function(e) {
                e.preventDefault();
            });

            $(document).on('change', '.migration_type', function () {
                let selected = $(this).find(':selected');

                if (selected.attr('data-default-allowed') === "true") {
                    $(this).parent().parent().find('.migration_default_value').prop('disabled', false);
                } else {
                    $(this).parent().parent().find('.migration_default_value').prop('disabled', true);
                }
            });

            $(document).on('click', '.remove_migration_row', function () {
                $(this).parent().parent().remove();

                if ($('#migration_rows').find('.migration_row').length === 0) {
                    $('.create_migration').addClass('hidden');
                }
            });

            $(document).on('keyup', '[name="migration_name"]', function () {

                if (/(?<=create_)(.*?)(?=_table)/.test($(this).val())) {
                    $('[name="migration_table_name"]').val(/(?<=create_)(.*?)(?=_table)/.exec($(this).val())[0]);

                    $('[name="migration_table_name"]').prop('readonly', true);
                } else {
                    $('[name="migration_table_name"]').prop('readonly', false);
                }
            });

        });
    </script>
@endsection

@section('style')
    <style>
        .remove_migration_row {
            font-size: 1.3em;
            margin-top: 0.5em;
        }

        .migration_buttons .btn i {
            margin-right: .5em;
        }

        input:disabled {
            cursor: not-allowed;
        }
    </style>
@endsection
