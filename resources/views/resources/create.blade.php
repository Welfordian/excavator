@extends('layouts.admin.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">New Resource</div>

                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <input name="name" type="text" class="form-control" placeholder="Resource Name">

                            <div class="input-group mt-2 upload">
                                <label class="input-group-btn">
                                    <span class="btn btn-primary">
                                        Browse&hellip; <input name="resource" type="file" style="display: none;">
                                    </span>
                                </label>

                                <input id="file-name" type="text" class="form-control" readonly>
                            </div>

                            <div class="custom-control custom-checkbox mt-2">
                                <input name="secure" type="checkbox" class="custom-control-input" id="customCheck1">
                                <label class="custom-control-label" for="customCheck1">Requires Authentication</label>
                            </div>

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
<script>

    $(function() {

        $('input[type="file"]').on('change', function() {
           $('#file-name').val($(this).val().match(/[^\\/]*$/)[0]);
        });

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
