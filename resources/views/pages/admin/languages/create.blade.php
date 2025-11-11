@extends('layouts.admin_layout')

@section('title', 'Add {{$modelName}}')


@section('page-title')
    {{ isset($item) ? __('Edit :entity', ['entity' => $modelName]) : __('Add :entity', ['entity' => $modelName]) }}
@endsection

@include('includes.admin.form_theme')



@section('content')

    @php
        $languageCount = isset($activeLanguages) ? $activeLanguages->count() : 0;
        $formStats = [];
        if ($languageCount) {
            $formStats[] = ['icon' => 'fas fa-language', 'label' => $languageCount . ' ' . __('Locales')];
        }
        $formStats[] = ['icon' => 'fas fa-layer-group', 'label' => __('Guided workflow')];
        $formStats[] = ['icon' => 'fas fa-save', 'label' => __('Content safety')];
        $formTitle = isset($item)
            ? __('Update :entity', ['entity' => $modelName])
            : __('Add :entity', ['entity' => $modelName]);
        $formDescription = isset($item)
            ? __('Review the content, adjust translations and assets, then save confidently.')
            : __('Complete the details below to publish a polished entry.');
    @endphp

    @include('includes.admin.form_header', [
        'title' => $formTitle,
        'description' => $formDescription,
        'stats' => $formStats
    ])




                <div class="card form-card card-primary card-outline card-tabs shadow-lg">
                    <div class="card-header p-0 pt-1 border-bottom-0 bg-light">
                        <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active text-dark" id="custom-tabs-general-tab" data-toggle="pill" href="#custom-tabs-general" role="tab" aria-controls="custom-tabs-general" aria-selected="true">
                                    <i class="fas fa-info-circle"></i> General Data
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('admin.'.$modelName.'.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="tab-content" id="custom-tabs-three-tabContent">
                                <div class="tab-pane fade show active" id="custom-tabs-general" role="tabpanel" aria-labelledby="custom-tabs-general-tab">
                                    <div class="form-group">
                                        <label for="code" class="font-weight-bold">Code</label>
                                        <input type="text" name="code" class="form-control form-control-lg shadow-sm" id="code" value="{{ old('code') }}" placeholder="e.g., en, ar">
                                    </div>
                                    <div class="form-group">
                                        <label for="name" class="font-weight-bold">Name</label>
                                        <input type="text" name="name" class="form-control form-control-lg shadow-sm" id="name" value="{{ old('name') }}" placeholder="{{$modelName}} name">
                                    </div>

                                    <div class="form-group">
                                        <label for="is_active" class="font-weight-bold">Active</label>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" name="is_active" class="custom-control-input" id="is_active" {{ old('is_active', 1) == 1 ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="is_active">Active</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success btn-lg mt-3">
                                <i class="fas fa-save"></i> Save
                            </button>
                        </form>
                    </div>
                </div>@endsection

@push('styles')
    <style>
        .custom-switch .custom-control-input:checked ~ .custom-control-label::before {
            background-color: #28a745;
            border-color: #28a745;
        }

        .custom-switch .custom-control-input:focus ~ .custom-control-label::before {
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }
    </style>
@endpush
