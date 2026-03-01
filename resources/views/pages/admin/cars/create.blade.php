@extends('layouts.admin_layout')

@section('title', __('Create Car'))
@section('page-title', __('Create Car'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.cars.index') }}">{{ __('Cars') }}</a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Create') }}</li>
@endsection

@section('page-actions')
    <a href="{{ route('admin.cars.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center">
        <i class="ti ti-arrow-left me-1"></i>{{ __('Back to Cars') }}
    </a>
@endsection

@include('includes.admin.form_theme')



@push('styles')
    <style>
        .preview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }

        .preview-item {
            position: relative;
            border: 1px solid #e2e8f0;
            padding: 12px;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            background-color: #fff;
            text-align: center;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .preview-item img,
        .preview-item iframe {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 12px;
        }

        .remove-preview {
            position: absolute;
            top: 8px;
            right: 8px;
            background: linear-gradient(135deg, #ff4d4d, #e60000);
            color: white;
            border: none;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            font-size: 16px;
            line-height: 32px;
            text-align: center;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(255, 77, 77, 0.3);
            transition: all 0.3s ease;
        }

        .remove-preview:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(255, 77, 77, 0.5);
        }

        .preview-item:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6c757d, #5a6268);
            border-color: #6c757d;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, #5a6268, #4a5258);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
        }

        .loader-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(4px);
            z-index: 9999;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .loader {
            border: 5px solid #f3f3f3;
            border-radius: 50%;
            border-top: 5px solid #4c6ef5;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .form-card {
            border-radius: 24px;
            box-shadow: 0 20px 45px rgba(15, 23, 42, 0.12);
            border: none;
        }

        .card {
            border-radius: 20px;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
            border: 1px solid #eef1fb;
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 12px 32px rgba(15, 23, 42, 0.12);
        }

        .card-header {
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-bottom: 2px solid #e2e8f0;
            border-radius: 20px 20px 0 0;
            padding: 1.25rem 1.5rem;
        }

        .card-title {
            font-weight: 600;
            color: #1e293b;
            font-size: 1.1rem;
        }

        .form-control {
            border-radius: 12px;
            border: 1px solid #d0d7e2;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #4c6ef5;
            box-shadow: 0 0 0 3px rgba(76, 110, 245, 0.1);
        }

        .nav-tabs-modern .nav-link {
            border-radius: 16px;
            padding: 0.85rem 1.5rem;
            transition: all 0.3s ease;
        }

        .nav-tabs-modern .nav-link.active {
            background: linear-gradient(135deg, #4c6ef5, #6a82fb);
            color: #fff;
            box-shadow: 0 8px 20px rgba(76, 110, 245, 0.3);
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981, #059669);
            border: none;
            border-radius: 12px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
        }

        .custom-switch .custom-control-input:checked ~ .custom-control-label::before {
            background-color: #4c6ef5;
            border-color: #4c6ef5;
        }

        .seo-question-group {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            transition: all 0.3s ease;
        }

        .seo-question-group:hover {
            border-color: #4c6ef5;
            box-shadow: 0 4px 12px rgba(76, 110, 245, 0.1);
        }

        .tinymce {
            min-height: 300px;
        }

        .setup-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 12px;
            margin-bottom: 1rem;
        }

        .setup-card {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 12px 14px;
            background: #ffffff;
        }

        .setup-card h6 {
            margin-bottom: 4px;
            font-weight: 600;
            color: #0f172a;
        }

        .setup-card p {
            margin-bottom: 0;
            font-size: 13px;
            color: #64748b;
        }
    </style>
@endpush

@include('includes.admin.car_form_rebuild')

@section('content')

    @php
        $languageCount = isset($activeLanguages) ? $activeLanguages->count() : 0;
        $formTitle = isset($item)
            ? __('Update :entity', ['entity' => $modelName])
            : __('Add :entity', ['entity' => $modelName]);
    @endphp

    <div class="car-workbench">
        <section class="car-workbench__hero">
            <div class="car-workbench__hero-copy">
                <span class="car-workbench__eyebrow">{{ __('Car Studio') }}</span>
                <h2 class="car-workbench__title">{{ $formTitle }}</h2>
                <p class="car-workbench__subtitle">
                    {{ __('The old layout is gone. This new workspace is built for faster entry: start with core specs, move through translations, then finish with SEO and publish when the listing is complete.') }}
                </p>
                <div class="car-workbench__hero-tags">
                    <span class="car-workbench__tag">{{ __('3-stage workflow') }}</span>
                    @if($languageCount)
                        <span class="car-workbench__tag">{{ __(':count locales', ['count' => $languageCount]) }}</span>
                    @endif
                    <span class="car-workbench__tag">{{ __('Media uploads ready') }}</span>
                </div>
            </div>
            <div class="car-workbench__hero-cards">
                <div class="car-workbench__metric">
                    <span class="car-workbench__metric-label">{{ __('Stage 1') }}</span>
                    <strong class="car-workbench__metric-value">{{ __('Vehicle setup') }}</strong>
                    <span class="car-workbench__metric-note">{{ __('Specs, pricing, media, and switches') }}</span>
                </div>
                <div class="car-workbench__metric">
                    <span class="car-workbench__metric-label">{{ __('Stage 2') }}</span>
                    <strong class="car-workbench__metric-value">{{ __('Localized copy') }}</strong>
                    <span class="car-workbench__metric-note">{{ __('Manual or AI-generated content') }}</span>
                </div>
                <div class="car-workbench__metric">
                    <span class="car-workbench__metric-label">{{ __('Stage 3') }}</span>
                    <strong class="car-workbench__metric-value">{{ __('SEO finishing') }}</strong>
                    <span class="car-workbench__metric-note">{{ __('Metadata, robots, and Q&A') }}</span>
                </div>
                <div class="car-workbench__metric">
                    <span class="car-workbench__metric-label">{{ __('Goal') }}</span>
                    <strong class="car-workbench__metric-value">{{ __('Publish cleanly') }}</strong>
                    <span class="car-workbench__metric-note">{{ __('One focused screen for the full flow') }}</span>
                </div>
            </div>
        </section>

        <div class="car-workbench__layout">
            <aside class="car-workbench__aside">
                <div class="car-workbench__panel">
                    <div class="car-workbench__panel-head">
                        <span class="car-workbench__panel-icon"><i class="ti ti-sparkles"></i></span>
                        <div>
                            <h3 class="car-workbench__panel-title">{{ __('Build Flow') }}</h3>
                            <p class="car-workbench__panel-copy">{{ __('Use the tabs as a guided path instead of jumping around the page.') }}</p>
                        </div>
                    </div>
                    <ul class="car-workbench__steps">
                        <li>
                            <span class="car-workbench__step-label">{{ __('General') }}</span>
                            <span class="car-workbench__step-text">{{ __('Set brand, model, pricing, features, media, and status first.') }}</span>
                        </li>
                        <li>
                            <span class="car-workbench__step-label">{{ __('Translations') }}</span>
                            <span class="car-workbench__step-text">{{ __('Fill language-specific name, summary, and long description.') }}</span>
                        </li>
                        <li>
                            <span class="car-workbench__step-label">{{ __('SEO') }}</span>
                            <span class="car-workbench__step-text">{{ __('Add metadata, search directives, and question blocks last.') }}</span>
                        </li>
                    </ul>
                </div>
                <div class="car-workbench__panel">
                    <div class="car-workbench__panel-head">
                        <span class="car-workbench__panel-icon"><i class="ti ti-bolt"></i></span>
                        <div>
                            <h3 class="car-workbench__panel-title">{{ __('Quick Notes') }}</h3>
                            <p class="car-workbench__panel-copy">{{ __('A few practical reminders before you save the listing.') }}</p>
                        </div>
                    </div>
                    <ul class="car-workbench__facts">
                        <li>
                            <span class="car-workbench__fact-label">{{ __('Media') }}</span>
                            <span class="car-workbench__fact-value">{{ __('Upload a default cover image before adding the gallery.') }}</span>
                        </li>
                        <li>
                            <span class="car-workbench__fact-label">{{ __('AI') }}</span>
                            <span class="car-workbench__fact-value">{{ __('Use generated text as a draft, then review before publishing.') }}</span>
                        </li>
                        <li>
                            <span class="car-workbench__fact-label">{{ __('Publish') }}</span>
                            <span class="car-workbench__fact-value">{{ __('Leave inactive until pricing and translations are reviewed.') }}</span>
                        </li>
                    </ul>
                </div>
            </aside>

            <div class="car-workbench__main">

    <!-- Loader Overlay -->
    <div class="loader-overlay" id="loader-overlay">
        <div class="text-center">
            <div class="loader"></div>
            <p class="mt-3" style="color: #4c6ef5; font-weight: 600;">Processing...</p>
        </div>
    </div>

                <div class="card form-card card-primary card-outline card-tabs shadow-lg studio-shell-card">
                    <div class="card-header p-0 pt-1 border-bottom-0 bg-light">
                        <!-- Tabs Header -->
                        <ul class="nav nav-tabs nav-tabs-modern" id="custom-tabs-three-tab" role="tablist">
                            <!-- General Data Tab -->
                            <li class="nav-item">
                                <a class="nav-link active text-dark" id="custom-tabs-general-tab" data-bs-toggle="pill" href="#custom-tabs-general" role="tab" aria-controls="custom-tabs-general" aria-selected="true">
                                    <i class="fas fa-info-circle"></i> General Data
                                </a>
                            </li>
                            <!-- Translated Data Tab -->
                            <li class="nav-item">
                                <a class="nav-link text-dark" id="custom-tabs-translated-tab" data-bs-toggle="pill" href="#custom-tabs-translated" role="tab" aria-controls="custom-tabs-translated" aria-selected="false">
                                    <i class="fas fa-language"></i> Translated Data
                                </a>
                            </li>
                            <!-- SEO Data Tab -->
                            <li class="nav-item">
                                <a class="nav-link text-dark" id="custom-tabs-seo-tab" data-bs-toggle="pill" href="#custom-tabs-seo" role="tab" aria-controls="custom-tabs-seo" aria-selected="false">
                                    <i class="fas fa-search"></i> SEO Data
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <!-- Form -->
                        <form id="createCarForm" action="{{ route('admin.'.$modelName.'.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="tab-content tab-modern" id="custom-tabs-three-tabContent">
                                <div class="tab-pane fade show active" id="custom-tabs-general" role="tabpanel" aria-labelledby="custom-tabs-general-tab">
                                        <!-- Car Information -->
                                        <div class="card mb-4">
                                            <div class="card-header bg-light">
                                                <h3 class="card-title">Car Information</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="brand_id" class="font-weight-bold">Brand</label>
                                                            <select name="brand_id" id="brand_id" class="form-control shadow-sm select2 @error('brand_id') is-invalid @enderror">
                                                                <option value="">-- Select Brand --</option>
                                                                @foreach($brands as $brand)
                                                                    <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                                                        {{ $brand->translations()->first()->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('brand_id')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="car_model_id" class="font-weight-bold">Model</label>
                                                            <select name="car_model_id" id="car_model_id" class="form-control shadow-sm select2">
                                                                <option value="">-- Select Model --</option>
                                                                <!-- Models will be populated dynamically here -->
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="category_id" class="font-weight-bold">Car Categories</label>
                                                            <select name="category_ids[]" id="category_id" class="form-control shadow-sm select2 @error('category_ids') is-invalid @enderror @error('category_ids.*') is-invalid @enderror" multiple="multiple">
                                                                <option value="">-- Select Categories --</option>
                                                                @foreach($categories as $category)
                                                                    <option value="{{ $category->id }}" {{ in_array($category->id, (array) old('category_ids', [])) ? 'selected' : '' }}>
                                                                        {{ $category->translations()->first()->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('category_ids')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                            @error('category_ids.*')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="gearType_id" class="font-weight-bold">Gear Type</label>
                                                            <select name="gear_type_id" id="gear_type_id" class="form-control shadow-sm select2 @error('gear_type_id') is-invalid @enderror">
                                                                <option value="">-- Select Gear Type --</option>
                                                                @foreach($gearTypes as $gearType)
                                                                    <option value="{{ $gearType->id }}" {{ old('gear_type_id') == $gearType->id ? 'selected' : '' }}>
                                                                        {{ $gearType->translations()->first()->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('gear_type_id')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="color_id" class="font-weight-bold">Color</label>
                                                            <select name="color_id" id="color_id" class="form-control shadow-sm select2 @error('color_id') is-invalid @enderror">
                                                                <option value="">-- Select Color --</option>
                                                                @foreach($colors as $color)
                                                                    <option value="{{ $color->id }}" style="background-color: {{ $color->color_code }}; color: {{ $color->color_code == '#FFFFFF' ? '#000000' : '#FFFFFF' }};" {{ old('color_id') == $color->id ? 'selected' : '' }}>
                                                                        {{ $color->translations()->first()->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('color_id')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="year_id" class="font-weight-bold">Car Year</label>
                                                            <select name="year_id" id="year_id" class="form-control shadow-sm select2">
                                                                <option value="">-- Select Year --</option>
                                                                @foreach($years as $year)
                                                                    <option value="{{ $year->id }}" {{ old('year_id') == $year->id ? 'selected' : '' }}>
                                                                        {{ $year->year }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                            <!-- Car Details -->
                                            <div class="card mb-4">
                                                <div class="card-header bg-light">
                                                    <h3 class="card-title">Car Details</h3>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="door_count" class="font-weight-bold">Number of Doors</label>
                                                                <input type="number" name="door_count" class="form-control shadow-sm @error('door_count') is-invalid @enderror" max="6" min="1" id="door_count" value="{{ old('door_count') }}">
                                                                @error('door_count')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="luggage_capacity" class="font-weight-bold">Number Of Luggage Capacity</label>
                                                                <input type="number" name="luggage_capacity" class="form-control shadow-sm @error('luggage_capacity') is-invalid @enderror" max="20" min="0" id="luggage_capacity" value="{{ old('luggage_capacity') }}">
                                                                @error('luggage_capacity')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="passenger_capacity" class="font-weight-bold">Number of passenger</label>
                                                                <input type="number" name="passenger_capacity" class="form-control shadow-sm @error('passenger_capacity') is-invalid @enderror" max="20" min="1" id="passenger_capacity" value="{{ old('passenger_capacity') }}">
                                                                @error('passenger_capacity')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <!-- Pricing Information -->
                                        <div class="card mb-4">
                                            <div class="card-header bg-light">
                                                <h3 class="card-title">Pricing Information</h3>
                                            </div>
                                            <div class="card-body">
                                                <label>Daily: </label>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="daily_main_price" class="font-weight-bold">Daily Main Price</label>
                                                            <input type="number" name="daily_main_price" class="form-control shadow-sm @error('daily_main_price') is-invalid @enderror" id="daily_main_price" value="{{ old('daily_main_price') }}">
                                                            @error('daily_main_price')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="daily_discount_price" class="font-weight-bold">Daily Price With Discount</label>
                                                            <input type="number" name="daily_discount_price" class="form-control shadow-sm @error('daily_discount_price') is-invalid @enderror" id="daily_discount_price" value="{{ old('daily_discount_price') }}">
                                                            @error('daily_discount_price')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="daily_mileage_included" class="font-weight-bold">Daily Mileage Included</label>
                                                            <input type="number" name="daily_mileage_included" class="form-control shadow-sm @error('daily_mileage_included') is-invalid @enderror" id="daily_mileage_included" value="{{ old('daily_mileage_included') }}">
                                                            @error('daily_mileage_included')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>

                                                <label>Weakly: </label>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="weekly_main_price" class="font-weight-bold">Weekly Main Price</label>
                                                            <input type="text" name="weekly_main_price" class="form-control shadow-sm @error('weekly_main_price') is-invalid @enderror" id="weekly_main_price" value="{{ old('weekly_main_price') }}">
                                                            @error('weekly_main_price')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="weekly_discount_price" class="font-weight-bold">Weekly Price With Discount</label>
                                                            <input type="number" name="weekly_discount_price" class="form-control shadow-sm @error('weekly_discount_price') is-invalid @enderror" id="weekly_discount_price" value="{{ old('weekly_discount_price') }}">
                                                            @error('weekly_discount_price')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="weekly_mileage_included" class="font-weight-bold">Weekly Mileage Included</label>
                                                            <input type="number" name="weekly_mileage_included" class="form-control shadow-sm @error('weekly_mileage_included') is-invalid @enderror" id="weekly_mileage_included" value="{{ old('weekly_mileage_included') }}">
                                                            @error('weekly_mileage_included')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <label>Monthly: </label>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="monthly_main_price" class="font-weight-bold">Monthly Main Price</label>
                                                            <input type="number" name="monthly_main_price" class="form-control shadow-sm @error('monthly_main_price') is-invalid @enderror" id="monthly_main_price" value="{{ old('monthly_main_price') }}">
                                                            @error('monthly_main_price')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="monthly_discount_price" class="font-weight-bold">Monthly Price With Discount</label>
                                                            <input type="number" name="monthly_discount_price" class="form-control shadow-sm @error('monthly_discount_price') is-invalid @enderror" id="monthly_discount_price" value="{{ old('monthly_discount_price') }}">
                                                            @error('monthly_discount_price')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="monthly_mileage_included" class="font-weight-bold">Monthly Mileage Included</label>
                                                            <input type="number" name="monthly_mileage_included" class="form-control shadow-sm @error('monthly_mileage_included') is-invalid @enderror" id="monthly_mileage_included" value="{{ old('monthly_mileage_included') }}">
                                                            @error('monthly_mileage_included')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>




                                        <!-- Car Features -->
                                        <div class="card mb-4">
                                            <div class="card-header bg-light">
                                                <h3 class="card-title">Car Features</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" name="insurance_included" class="custom-control-input" id="insurance_included" {{ old('insurance_included') ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="insurance_included">Insurance Included</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" name="free_delivery" class="custom-control-input" id="free_delivery" {{ old('free_delivery') ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="free_delivery">Free Delivery</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" name="crypto_payment_accepted" class="custom-control-input" id="crypto_payment_accepted" {{ old('crypto_payment_accepted') ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="crypto_payment_accepted">Crypto Payment Accepted</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" name="is_featured" class="custom-control-input" id="is_featured" {{ old('is_featured') ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="is_featured">Featured</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" name="is_flash_sale" class="custom-control-input" id="is_flash_sale" {{ old('is_flash_sale') ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="is_flash_sale">Flash Sale</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" name="only_on_afandina" class="custom-control-input" id="only_on_afandina" {{ old('only_on_afandina') ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="only_on_afandina">Only On Afandina</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card mb-4">
                                            <div class="card-header bg-light">
                                                <h3 class="card-title">Car Features</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="features" class="font-weight-bold">Features related to Post</label>
                                                        <select id="features" class="form-control car-select" name="features[]" multiple="multiple" style="width: 100%;">
                                                            @foreach($features as $feature)
                                                                <option value="{{ $feature->id }}" data-icon="{{ $feature->icon->icon_class }}">
                                                                    {{ $feature->translations()->first()->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <!-- For multiple images -->

                                        <div class="card mb-4">
                                            <div class="card-header bg-light">
                                                <h3 class="card-title">Upload Media Files</h3>
                                            </div>
                                            <div class="card-body">

                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="font-weight-bold">Default Image</label>
                                                            <div class="custom-file">
                                                                <input type="file" name="default_image_path" class="custom-file-input image-upload" id="default_image_path" data-preview="imagePreviewLogo">
                                                                <label class="custom-file-label" for="default_image_path">Upload Default Image</label>
                                                            </div>
                                                            <div class="mt-3">
                                                                <img id="imagePreviewLogo" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='250' height='250' viewBox='0 0 250 250'%3E%3Crect width='100%25' height='100%25' fill='%23ddd'/%3E%3Ctext x='50%25' y='50%25' fill='%23555' font-size='20' text-anchor='middle' dy='.3em'%3E600x200%3C/text%3E%3C/svg%3E" alt="Logo Preview" class="shadow image-rectangle-preview" style="max-height: 250px; width: 250px; object-fit: cover; border: 2px solid #ddd;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <div class="form-group">
                                                            <label for="file_path">Upload Media Files (Images & Videos):</label>
                                                            <div class="custom-file">
                                                                <input type="file" class="custom-file-input" name="media[]" id="media-files" multiple accept="image/*,video/mp4,video/webm,video/ogg">
                                                                <label class="custom-file-label" for="media-files">Choose files</label>
                                                            </div>
                                                            <small class="form-text text-muted">
                                                                Supported formats: Images (JPG, PNG, GIF, WebP) and Videos (MP4, WebM, OGG). Maximum file size: 100MB
                                                            </small>
                                                        </div>

                                                        <div id="preview" class="preview-grid mt-3">
                                                            <!-- Preview items will be added here -->
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="alt">Alt Text (for all):</label>
                                                            <input type="text" class="form-control" name="alt" placeholder="Add alt text for images or videos">
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>
                                        </div>






                                        <!-- Activation Status -->
                                        <div class="card mb-4">
                                            <div class="card-header bg-light">
                                                <h3 class="card-title">Status</h3>
                                            </div>
                                            <div class="card-body">

                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <label class="form-label">Is Active?</label>

                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" name="is_active" class="custom-control-input" id="is_active" {{ old('is_active') ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="is_active">Active</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="status" class="font-weight-bold">Car Status</label>
                                                            <select name="status" id="status" class="form-control shadow-sm @error('status') is-invalid @enderror">
                                                                <option value="">-- Select Car Status --</option>
                                                                <option value="available" {{ old('status') == "available" ? 'selected' : '' }}>
                                                                    Available
                                                                </option>
                                                                <option value="not_available" {{ old('status') == "not_available" ? 'selected' : '' }}>
                                                                    Not Available
                                                                </option>
                                                            </select>
                                                            @error('status')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                <div class="tab-pane fade" id="custom-tabs-translated" role="tabpanel" aria-labelledby="custom-tabs-translated-tab">
                                    <div class="card shadow-sm border-0 mb-4 studio-pane-surface">
                                        <div class="card-body d-flex flex-column flex-lg-row justify-content-between align-items-lg-center">
                                            <div class="d-flex align-items-center mb-3 mb-lg-0">
                                                <span class="badge badge-primary mr-3"><i class="fas fa-robot"></i></span>
                                                <div>
                                                    <h5 class="mb-1 font-weight-bold">AI Content Assistant</h5>
                                                    <p class="mb-0 text-muted">Choose the language you want to auto-fill. Existing text in that language will be replaced.</p>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-wrap ai-language-buttons">
                                                <button type="button" class="btn btn-success mb-2 mr-2 generate-ai-all">
                                                    <i class="fas fa-magic mr-1"></i> Generate Content
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                        @foreach($activeLanguages as $lang)
                                            <li class="nav-item">
                                                <a class="nav-link @if($loop->first) active @endif bg-light text-dark" id="pills-{{ $lang->code }}-tab" data-bs-toggle="pill" href="#pills-{{ $lang->code }}" role="tab" aria-controls="pills-{{ $lang->code }}" aria-selected="true">{{ $lang->name }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="tab-content shadow-sm p-3 mb-4 bg-white rounded studio-pane-surface" id="pills-tabContent">
                                        @foreach($activeLanguages as $lang)
                                            <div class="tab-pane fade @if($loop->first) show active @endif" id="pills-{{ $lang->code }}" role="tabpanel" aria-labelledby="pills-{{ $lang->code }}-tab">
                                                <div class="form-group">
                                                    <label for="name_{{ $lang->code }}" class="font-weight-bold">Name ({{ $lang->name }})</label>
                                                    <input type="text" name="name[{{ $lang->code }}]" class="form-control form-control-lg shadow-sm @error('name.'.$lang->code) is-invalid @enderror" id="name_{{ $lang->code }}" value="{{ old('name.'.$lang->code) }}">
                                                    @error('name.'.$lang->code)
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="description_{{ $lang->code }}" class="font-weight-bold">Description ({{ $lang->name }})</label>
                                                    <textarea name="description[{{ $lang->code }}]" class="form-control form-control-lg shadow-sm @error('description.'.$lang->code) is-invalid @enderror" id="description_{{ $lang->code }}" rows="4">{{ old('description.'.$lang->code) }}</textarea>
                                                    @error('description.'.$lang->code)
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>


                                                <div class="form-group">
                                                    <label for="long_description_{{ $lang->code }}" class="font-weight-bold">Long Description ({{ $lang->name }})</label>
                                                    <textarea name="long_description[{{ $lang->code }}]" class="form-control form-control-lg shadow-sm tinymce @error('long_description.'.$lang->code) is-invalid @enderror" id="long_description_{{ $lang->code }}">{{ old('long_description.'.$lang->code) }}</textarea>
                                                    @error('long_description.'.$lang->code)
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- SEO Data Tab Content -->
                                <div class="tab-pane fade" id="custom-tabs-seo" role="tabpanel" aria-labelledby="custom-tabs-seo-tab">
                                    <ul class="nav nav-pills mb-3" id="pills-seo-tab" role="tablist">
                                        @foreach($activeLanguages as $lang)
                                            <li class="nav-item">
                                                <a class="nav-link @if($loop->first) active @endif bg-light text-dark" id="pills-seo-{{ $lang->code }}-tab" data-bs-toggle="pill" href="#pills-seo-{{ $lang->code }}" role="tab" aria-controls="pills-seo-{{ $lang->code }}" aria-selected="true">{{ $lang->name }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="tab-content shadow-sm p-3 mb-4 bg-white rounded studio-pane-surface" id="pills-seo-tabContent">
                                        @foreach($activeLanguages as $lang)
                                            <div class="tab-pane fade @if($loop->first) show active @endif" id="pills-seo-{{ $lang->code }}" role="tabpanel" aria-labelledby="pills-seo-{{ $lang->code }}-tab">
                                                <div class="form-group">
                                                    <label for="meta_title_{{ $lang->code }}" class="font-weight-bold">Meta Title ({{ $lang->name }})</label>
                                                    <input type="text" name="meta_title[{{ $lang->code }}]" class="form-control form-control-lg shadow-sm @error('meta_title.'.$lang->code) is-invalid @enderror" id="meta_title_{{ $lang->code }}" value="{{ old('meta_title.'.$lang->code) }}">
                                                    @error('meta_title.'.$lang->code)
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="meta_description_{{ $lang->code }}" class="font-weight-bold">Meta Description ({{ $lang->name }})</label>
                                                    <textarea name="meta_description[{{ $lang->code }}]" class="form-control form-control-lg shadow-sm @error('meta_description.'.$lang->code) is-invalid @enderror" id="meta_description_{{ $lang->code }}" rows="3">{{ old('meta_description.'.$lang->code) }}</textarea>
                                                    @error('meta_description.'.$lang->code)
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="meta_keywords_{{ $lang->code }}" class="font-weight-bold">Meta Keywords ({{ $lang->name }})</label>
                                                    <input type="text" name="meta_keywords[{{ $lang->code }}]" class="form-control form-control-lg shadow-sm @error('meta_keywords.'.$lang->code) is-invalid @enderror" id="meta_keywords_{{ $lang->code }}" data-role="tagsinput" value="{{ old('meta_keywords.'.$lang->code) }}">
                                                    @error('meta_keywords.'.$lang->code)
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>

                                                <div class="row studio-inline-card">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="robots_index_{{ $lang->code }}" class="font-weight-bold">
                                                                Robot Index ({{ $lang->name }})
                                                            </label>
                                                            <div class="custom-control custom-switch">
                                                                <input type="checkbox"
                                                                       name="robots_index[{{ $lang->code }}]"
                                                                       class="custom-control-input"
                                                                       id="robots_index_{{ $lang->code }}"
                                                                       value="index"
                                                                    {{ old('robots_index.'.$lang->code, $currentValues['robots_index'][$lang->code] ?? '') === 'index' ? 'checked' : '' }}>
                                                                <label class="custom-control-label" for="robots_index_{{ $lang->code }}">Index</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="robots_follow_{{ $lang->code }}" class="font-weight-bold">
                                                                Robot Follow ({{ $lang->name }})
                                                            </label>
                                                            <div class="custom-control custom-switch">
                                                                <input type="checkbox"
                                                                       name="robots_follow[{{ $lang->code }}]"
                                                                       class="custom-control-input"
                                                                       id="robots_follow_{{ $lang->code }}"
                                                                       value="follow"
                                                                    {{ old('robots_follow.'.$lang->code, $currentValues['robots_follow'][$lang->code] ?? '') === 'follow' ? 'checked' : '' }}>
                                                                <label class="custom-control-label" for="robots_follow_{{ $lang->code }}">Follow</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Dynamic SEO Questions/Answers Section -->
                                                <div class="seo-questions-container" id="seo-questions-{{ $lang->code }}">
                                                    <label class="font-weight-bold">SEO Questions/Answers ({{ $lang->name }})</label>
                                                    <div class="seo-question-group mb-3 p-3 border border-light rounded shadow-sm">
                                                        <div class="form-group">
                                                            <input type="text" name="seo_questions[{{ $lang->code }}][0][question]" class="form-control form-control-lg shadow-sm mb-2" placeholder="Enter Question" />
                                                        </div>
                                                        <div class="form-group">
                                                            <textarea name="seo_questions[{{ $lang->code }}][0][answer]" class="form-control form-control-lg shadow-sm" placeholder="Enter Answer"></textarea>
                                                        </div>
                                                        <button type="button" class="btn btn-sm btn-danger remove-question">Remove</button>
                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-info add-question mt-3" data-lang="{{ $lang->code }}">
                                                    <i class="fas fa-plus"></i> Add Question
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-success btn-lg mt-3">
                                <i class="fas fa-save"></i> Save
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Array to store selected media files
        let selectedFiles = [];
        const tagifyInstances = {};
        const aiLanguages = @json($activeLanguages->pluck('code')->toArray());

        function escapeHtml(value) {
            if (value === null || value === undefined) {
                return '';
            }

            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function buildSeoQuestionGroup(lang, index, qa = {question: '', answer: ''}) {
            const question = escapeHtml(qa.question ?? '');
            const answer = escapeHtml(qa.answer ?? '');

            return `
                <div class="seo-question-group mb-3 p-3 border border-light rounded shadow-sm">
                    <div class="form-group">
                        <input type="text" name="seo_questions[${lang}][${index}][question]" class="form-control form-control-lg shadow-sm mb-2" placeholder="Enter Question" value="${question}" />
                    </div>
                    <div class="form-group">
                        <textarea name="seo_questions[${lang}][${index}][answer]" class="form-control form-control-lg shadow-sm" placeholder="Enter Answer">${answer}</textarea>
                    </div>
                    <button type="button" class="btn btn-sm btn-danger remove-question">Remove</button>
                </div>
            `;
        }

        function collectAiContext(lang) {
            const context = {};

            const brandOption = $('#brand_id option:selected');
            context.brand = brandOption.val() ? brandOption.text().trim() : null;

            const modelOption = $('#car_model_id option:selected');
            context.model = modelOption.val() ? modelOption.text().trim() : null;

            const yearOption = $('#year_id option:selected');
            context.year = yearOption.val() ? yearOption.text().trim() : null;

            const colorOption = $('#color_id option:selected');
            context.color = colorOption.val() ? colorOption.text().trim() : null;

            const gearTypeOption = $('#gear_type_id option:selected');
            context.gear_type = gearTypeOption.val() ? gearTypeOption.text().trim() : null;

            const categoryNames = $('#category_id option:selected').map(function () {
                const value = $(this).val();
                return value ? $(this).text().trim() : null;
            }).get().filter(Boolean);

            context.categories = categoryNames;
            context.primary_category = categoryNames[0] || null;

            const englishNameField = $('#name_en');
            const englishName = englishNameField.length ? englishNameField.val().trim() : '';
            if (englishName.length) {
                context.original_name = englishName;
            }

            context.target_language = lang;

            const featureNames = $('#features option:selected').map(function () {
                const text = $(this).text().trim();
                return text.length ? text : null;
            }).get().filter(Boolean);

            context.features = featureNames;

            context.daily_price = $('#daily_main_price').val();
            context.weekly_price = $('#weekly_main_price').val();
            context.monthly_price = $('#monthly_main_price').val();
            context.passenger_capacity = $('#passenger_capacity').val();
            context.door_count = $('#door_count').val();
            context.luggage_capacity = $('#luggage_capacity').val();
            context.daily_main_price = $('#daily_main_price').val();
            context.daily_discount_price = $('#daily_discount_price').val();
            context.daily_mileage_included = $('#daily_mileage_included').val();
            context.weekly_main_price = $('#weekly_main_price').val();
            context.weekly_discount_price = $('#weekly_discount_price').val();
            context.weekly_mileage_included = $('#weekly_mileage_included').val();
            context.monthly_main_price = $('#monthly_main_price').val();
            context.monthly_discount_price = $('#monthly_discount_price').val();
            context.monthly_mileage_included = $('#monthly_mileage_included').val();

            context.insurance_included = $('#insurance_included').is(':checked');
            context.free_delivery = $('#free_delivery').is(':checked');
            context.is_flash_sale = $('#is_flash_sale').is(':checked');
            context.is_featured = $('#is_featured').is(':checked');
            context.only_on_afandina = $('#only_on_afandina').is(':checked');
            context.crypto_payment_accepted = $('#crypto_payment_accepted').is(':checked');

            return context;
        }

        function populateAiContent(lang, data) {
            if (!data) {
                return;
            }

            if (data.name) {
                $('#name_' + lang).val(data.name);
            }

            if (data.description) {
                $('#description_' + lang).val(data.description);
            }

            if (data.long_description) {
                const editor = typeof tinymce !== 'undefined'
                    ? tinymce.get('long_description_' + lang)
                    : null;
                if (editor) {
                    editor.setContent(data.long_description);
                } else {
                    $('#long_description_' + lang).val(data.long_description);
                }
            }

            if (data.meta_title) {
                $('#meta_title_' + lang).val(data.meta_title);
            }

            if (data.meta_description) {
                $('#meta_description_' + lang).val(data.meta_description);
            }

            if (Array.isArray(data.meta_keywords)) {
                if (tagifyInstances[lang]) {
                    tagifyInstances[lang].removeAllTags();
                    tagifyInstances[lang].addTags(data.meta_keywords);
                } else {
                    $('#meta_keywords_' + lang).val(data.meta_keywords.join(', '));
                }
            }

            if (Array.isArray(data.seo_questions)) {
                const container = $('#seo-questions-' + lang);
                container.empty();

                data.seo_questions.forEach(function (qa, index) {
                    container.append(buildSeoQuestionGroup(lang, index, qa));
                });

                if (data.seo_questions.length === 0) {
                    container.append(buildSeoQuestionGroup(lang, 0));
                }
            }
        }

        function populateGeneralFields(data) {
            if (!data || typeof data !== 'object') {
                return;
            }

            const mappings = {
                door_count: '#door_count',
                luggage_capacity: '#luggage_capacity',
                passenger_capacity: '#passenger_capacity',
                daily_main_price: '#daily_main_price',
                daily_discount_price: '#daily_discount_price',
                daily_mileage_included: '#daily_mileage_included',
                weekly_main_price: '#weekly_main_price',
                weekly_discount_price: '#weekly_discount_price',
                weekly_mileage_included: '#weekly_mileage_included',
                monthly_main_price: '#monthly_main_price',
                monthly_discount_price: '#monthly_discount_price',
                monthly_mileage_included: '#monthly_mileage_included'
            };

            Object.entries(mappings).forEach(([key, selector]) => {
                const value = data[key];
                if (value !== undefined && value !== null && value !== '') {
                    const input = $(selector);
                    if (input.length) {
                        input.val(value);
                    }
                }
            });
        }

        // Handle media file preview
        document.getElementById('media-files').addEventListener('change', function(event) {
            var files = Array.from(event.target.files);
            selectedFiles = selectedFiles.concat(files);
            displayMediaPreviews();
        });

        // Function to display media previews
        function displayMediaPreviews() {
            var previewDiv = document.getElementById('preview');
            previewDiv.innerHTML = ''; // Clear previous previews

            selectedFiles.forEach((file, index) => {
                let reader = new FileReader();
                reader.onload = function(e) {
                    let div = document.createElement('div');
                    div.classList.add('preview-item');
                    div.setAttribute('data-index', index);

                    if (file.type.startsWith('image/')) {
                        div.setAttribute('data-type', 'image');
                        div.innerHTML = `
                            <img src="${e.target.result}" class="img-fluid">
                            <button type="button" class="remove-preview" data-type="image" data-index="${index}">×</button>
                        `;
                    } else if (file.type.startsWith('video/')) {
                        div.setAttribute('data-type', 'video');
                        div.innerHTML = `
                            <video src="${e.target.result}" controls class="img-fluid"></video>
                            <button type="button" class="remove-preview" data-type="video" data-index="${index}">×</button>
                        `;
                    }
                    previewDiv.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }

        // Event delegation for preview removal
        document.getElementById('preview').addEventListener('click', function(event) {
            if (event.target.classList.contains('remove-preview')) {
                let previewType = event.target.getAttribute('data-type');
                let inputIndex = event.target.getAttribute('data-index');
                let previewItem = event.target.closest('.preview-item');

                if (previewType === 'youtube') {
                    let input = document.querySelector(`.youtube-link[data-index="${inputIndex}"]`);
                    if (input) {
                        input.value = '';
                        delete input.dataset.previewId;
                    }
                } else {
                    selectedFiles.splice(inputIndex, 1);
                    displayMediaPreviews();
                    return;
                }
                previewItem.remove();
            }
        });

        $(document).ready(function() {
            // Function to dynamically add SEO Questions/Answers
            $('.add-question').on('click', function() {
                var lang = $(this).data('lang');
                var container = $('#seo-questions-' + lang);
                var count = container.find('.seo-question-group').length;
                console.log('Adding question for language:', lang, 'Count:', count); // Debugging line
                var newQuestionGroup = `
                    <div class="seo-question-group mb-3 p-3 border border-light rounded shadow-sm">
                        <div class="form-group">
                            <input type="text" name="seo_questions[` + lang + `][` + count + `][question]" class="form-control form-control-lg shadow-sm mb-2" placeholder="Enter Question" />
                        </div>
                        <div class="form-group">
                            <textarea name="seo_questions[` + lang + `][` + count + `][answer]" class="form-control form-control-lg shadow-sm" placeholder="Enter Answer"></textarea>
                        </div>
                        <button type="button" class="btn btn-sm btn-danger remove-question">Remove</button>
                    </div>`;
                container.append(newQuestionGroup);
            });

            // Function to remove an SEO Question/Answer
            $(document).on('click', '.remove-question', function() {
                $(this).closest('.seo-question-group').remove();
            });


            @foreach($activeLanguages as $lang)
            {
                const metaKeywordsInput = document.querySelector('#meta_keywords_{{ $lang->code }}');
                if (metaKeywordsInput && typeof Tagify !== 'undefined') {
                    tagifyInstances['{{ $lang->code }}'] = new Tagify(metaKeywordsInput, {
                        placeholder: 'Enter meta keywords'
                    });
                } else if (metaKeywordsInput) {
                    metaKeywordsInput.setAttribute('placeholder', 'Enter meta keywords (comma separated)');
                }
            }
            @endforeach
        });


        $(document).ready(function() {
            var brandSelect = $('#brand_id');
            var modelSelect = $('#car_model_id');
            var initialModelId = '{{ old('car_model_id') }}';

            function loadModels(brandId, selectedModelId = null) {
                modelSelect.empty().append('<option value="">-- Select Model --</option>');

                if (!brandId) {
                    return;
                }

                $.ajax({
                    url: "{{ route('admin.get.models', '') }}/" + brandId,
                    type: "GET",
                    success: function(data) {
                        data.forEach(function(model) {
                            var selected = selectedModelId && selectedModelId == model.id ? 'selected' : '';
                            modelSelect.append('<option value="' + model.id + '" ' + selected + '>' + model.name + '</option>');
                        });

                        if (selectedModelId) {
                            modelSelect.val(selectedModelId).trigger('change');
                        }
                    }
                });
            }

            brandSelect.on('change', function() {
                loadModels($(this).val());
            });

            if (brandSelect.val()) {
                loadModels(brandSelect.val(), initialModelId);
            }
        });

        $(document).ready(function() {
            // Handle form submission
            $('#createCarForm').on('submit', function(e) {
                e.preventDefault();
                
                if (typeof tinymce !== 'undefined') {
                    tinymce.triggerSave();
                }
                
                var form = $(this);
                var submitBtn = form.find('button[type="submit"]');
                var formData = new FormData(this);

                // Show loading overlay
                $('#loader-overlay').css('display', 'flex');

                // Disable submit button
                submitBtn.prop('disabled', true);

                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        // Hide loader
                        $('#loader-overlay').hide();

                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message,
                                showConfirmButton: true,
                                confirmButtonText: 'OK',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = response.redirect;
                                }
                            });
                        }
                    },
                    error: function(xhr) {
                        // Hide loading overlay
                        $('#loader-overlay').hide();

                        // Enable submit button
                        submitBtn.prop('disabled', false);

                        var errors = xhr.responseJSON.errors;

                        // Clear previous errors
                        $('.is-invalid').removeClass('is-invalid');
                        $('.invalid-feedback').remove();

                        if (errors) {
                            // Show error alert at the top
                            var errorHtml = '<div class="alert alert-danger alert-dismissible fade show shadow-sm mt-3 p-4 rounded-lg" role="alert">' +
                                '<div class="d-flex">' +
                                '<i class="fas fa-exclamation-triangle mr-2" style="font-size: 24px;"></i>' +
                                '<div class="flex-grow-1">' +
                                '<h5 class="alert-heading mb-2">Please correct the following errors:</h5>' +
                                '<ul class="mb-0 pl-3">';

                            $.each(errors, function(key, messages) {
                                messages.forEach(function(message) {
                                    errorHtml += '<li>' + message + '</li>';

                                    // Add error class and message to form field
                                    var input = $('[name="' + key + '"]');
                                    if (input.length) {
                                        input.addClass('is-invalid');
                                        if (!input.next('.invalid-feedback').length) {
                                            input.after('<div class="invalid-feedback">' + message + '</div>');
                                        }
                                    }
                                });
                            });

                            errorHtml += '</ul></div>' +
                                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                                '<span aria-hidden="true">&times;</span>' +
                                '</button>' +
                                '</div></div>';

                            // Remove any existing error alerts
                            $('.alert-danger').remove();
                            // Add the new error alert at the top of the form
                            form.before(errorHtml);
                        }

                        // Also show in SweetAlert
                        var errorMessage = '<ul class="list-unstyled mb-0">';
                        $.each(errors, function(key, messages) {
                            messages.forEach(function(message) {
                                errorMessage += '<li><i class="fas fa-times-circle mr-2" style="color: #f44336;"></i>' + message + '</li>';
                            });
                        });
                        errorMessage += '</ul>';

                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error!',
                            html: errorMessage,
                            showConfirmButton: true,
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });

        function generateContentForLanguage(lang, options = {}) {
            const opts = Object.assign({
                button: null,
                manageLoader: true,
                silent: false
            }, options);

            return new Promise((resolve, reject) => {
                const nameField = $('#name_' + lang);
                let nameValue = (nameField.val() || '').trim();
                const englishNameField = $('#name_en');
                const englishName = englishNameField.length ? englishNameField.val().trim() : '';

                if (!nameValue.length && lang !== 'en' && englishName.length) {
                    nameValue = englishName;
                }

                if (!nameValue.length) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Car name required',
                        text: 'Please enter at least one car name so the AI can generate content.'
                    });
                    nameField.focus();
                    return reject(new Error('Car name required for AI generation (' + lang.toUpperCase() + ')'));
                }

                const payload = {
                    language: lang,
                    name: nameValue,
                    context: collectAiContext(lang)
                };

                const button = opts.button && opts.button.length ? opts.button : null;
                let originalHtml = null;
                if (button) {
                    originalHtml = button.html();
                    button.data('original-html', originalHtml);
                    button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span> Generating...');
                }

                if (opts.manageLoader) {
                    $('#loader-overlay').css('display', 'flex');
                }

                $.ajax({
                    url: "{{ route('admin.cars.generate-content') }}",
                    method: 'POST',
                    data: JSON.stringify(payload),
                    contentType: 'application/json',
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response && response.success) {
                            populateAiContent(lang, response.data);
                            const tabTrigger = document.getElementById('pills-' + lang + '-tab');
                            if (tabTrigger && window.bootstrap && window.bootstrap.Tab) {
                                window.bootstrap.Tab.getOrCreateInstance(tabTrigger).show();
                            }
                            if (!opts.silent) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'AI content generated',
                                    text: 'Review the generated ' + lang.toUpperCase() + ' copy and adjust it as needed.',
                                    timer: 2500,
                                    showConfirmButton: false
                                });
                            }
                            resolve(response.data);
                        } else {
                            const message = response && response.message ? response.message : 'Unable to generate AI content right now.';
                            if (!opts.silent) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Generation failed',
                                    text: message
                                });
                            }
                            reject(new Error(message));
                        }
                    },
                    error: function(xhr) {
                        let message = 'Unable to generate AI content right now.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }

                        if (!opts.silent) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Generation failed',
                                text: message
                            });
                        }
                        reject(new Error(message));
                    },
                    complete: function() {
                        if (opts.manageLoader) {
                            $('#loader-overlay').hide();
                        }
                        if (button) {
                            button.prop('disabled', false).html(button.data('original-html'));
                        }
                    }
                });
            });
        }

        $(document).ready(function() {
            $(document).on('click', '.generate-ai-content', function() {
                const $button = $(this);
                const lang = $button.data('lang');
                generateContentForLanguage(lang, { button: $button })
                    .catch(() => {});
            });

            $(document).on('click', '.generate-ai-all', async function() {
                const $button = $(this);
                if (!aiLanguages.length) {
                    Swal.fire({
                        icon: 'info',
                        title: 'No languages configured',
                        text: 'Please add at least one active language before using AI generation.'
                    });
                    return;
                }

                const englishNameField = $('#name_en');
                const englishName = englishNameField.length ? englishNameField.val().trim() : '';

                if (!englishName) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Add English name',
                        text: 'Enter the car name in English before generating AI content.'
                    });
                    if (englishNameField.length) {
                        englishNameField.focus();
                    }
                    return;
                }

                const originalHtml = $button.html();
                $button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span> Generating...');
                $('#loader-overlay').css('display', 'flex');

                const languages = Array.from(new Set(['en', ...aiLanguages]));

                try {
                    for (const lang of languages) {
                        const data = await generateContentForLanguage(lang, {
                            manageLoader: false,
                            silent: true
                        });

                        if (lang === 'en') {
                            populateGeneralFields(data);
                        }
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'All content generated',
                        text: 'General details and translations were filled automatically. Review before saving.',
                        timer: 3000,
                        showConfirmButton: false
                    });
                } catch (error) {
                    const message = error && error.message ? error.message : 'Unable to generate AI content right now.';
                    Swal.fire({
                        icon: 'error',
                        title: 'Generation interrupted',
                        text: message
                    });
                } finally {
                    $('#loader-overlay').hide();
                    $button.prop('disabled', false).html(originalHtml);
                }
            });
        });

        $(document).ready(function() {
            function formatFeature(feature) {
                if (!feature.id) {
                    return feature.text;
                }

                var $feature = $(
                    '<span><i class="' + $(feature.element).data('icon') + '"></i> ' +
                    feature.text + '</span>'
                );
                return $feature;
            }

            $('.car-select').select2({
                templateResult: formatFeature,
                templateSelection: formatFeature,
                allowClear: true,
                placeholder: "Select features"
            });
        });
    </script>
@endpush
