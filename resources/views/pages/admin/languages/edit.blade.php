@extends('layouts.admin_layout')

@section('title', 'Edit ' . $modelName)

@section('page-title')
    Edit {{ $modelName }}
@endsection

@section('content')<!-- Display errors -->


    <div class="card card-primary card-outline card-tabs shadow-lg">
        <div class="card-header p-0 pt-1 border-bottom-0 bg-light">
            <!-- Tabs Header -->
            <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active text-dark" id="custom-tabs-general-tab" data-toggle="pill"
                        href="#custom-tabs-general" role="tab" aria-controls="custom-tabs-general" aria-selected="true">
                        <i class="fas fa-info-circle"></i> General Data
                    </a>
                </li>
            </ul>
        </div>

        <div class="card-body">
            <!-- Form -->
            <form action="{{ route('admin.' . $modelName . '.update', $item->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="tab-content" id="custom-tabs-three-tabContent">
                    <!-- General Data Tab Content -->
                    <div class="tab-pane fade show active" id="custom-tabs-general" role="tabpanel"
                        aria-labelledby="custom-tabs-general-tab">
                        <div class="form-group">
                            <label for="code" class="font-weight-bold">Code <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control form-control-lg shadow-sm" id="code"
                                value="{{ old('code', $item->code) }}" placeholder="e.g., en, ar">
                        </div>
                        <div class="form-group">
                            <label for="name" class="font-weight-bold">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-lg shadow-sm" id="name"
                                value="{{ old('name', $item->name) }}" placeholder="{{ $modelName }} name">
                        </div>

                        <div class="form-group">
                            <label for="is_active" class="font-weight-bold">Active</label>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="is_active" class="custom-control-input" id="is_active" {{ old('is_active', $item->is_active) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Active</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-success btn-lg mt-3">
                    <i class="fas fa-save"></i> Update
                </button>
            </form>
        </div>
</div>@endsection