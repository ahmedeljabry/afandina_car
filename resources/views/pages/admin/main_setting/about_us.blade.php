@extends('layouts.admin_layout')

@section('title', __('Home Page'))

@section('page-title')
    {{ __('Afandina') }}
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Basic Settings') }}</li>
    <li class="breadcrumb-item active">{{ __('About Us') }}</li>
@endsection

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ __('About Us') }}</h3>
        </div>

        <form>
            <div class="card-body">
                <div class="form-group">
                    <label for="exampleInputEmail1">{{ __('Email address') }}</label>
                    <input type="email" class="form-control" id="exampleInputEmail1" placeholder="{{ __('Enter email') }}">
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">{{ __('Description') }}</label>
                    <textarea style="height: 100px" id="summernote"></textarea>
                </div>
                <div class="form-group">
                    <label for="exampleInputFile">{{ __('File input') }}</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="exampleInputFile">
                            <label class="custom-file-label" for="exampleInputFile">{{ __('Choose file') }}</label>
                        </div>
                        <div class="input-group-append">
                            <span class="input-group-text">{{ __('Upload') }}</span>
                        </div>
                    </div>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                    <label class="form-check-label" for="exampleCheck1">{{ __('Check me out') }}</label>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
            </div>
        </form>
    </div>

    <div class="card mt-2">
        <div class="card-body">
            <textarea id="codeMirrorDemo"></textarea>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function () {
            $('#summernote').summernote();
            CodeMirror.fromTextArea(document.getElementById("codeMirrorDemo"), {
                mode: "htmlmixed",
                theme: "monokai"
            });
        });
    </script>
@endpush
