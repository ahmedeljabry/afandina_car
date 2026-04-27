@extends('layouts.admin_layout')

@section('title', 'Car Detail Rental Terms')

@section('page-title')
    {{ __('Car Detail Rental Terms') }}
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item">{{ __('Car Attributes') }}</li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Rental Terms') }}</li>
@endsection

@include('includes.admin.form_theme')

@push('styles')
    <style>
        .car-terms-shell {
            display: grid;
            gap: 1.5rem;
        }

        .car-terms-hero,
        .car-terms-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 24px;
            box-shadow: 0 20px 45px rgba(15, 23, 42, 0.07);
        }

        .car-terms-hero,
        .car-terms-card {
            padding: 1.5rem;
        }

        .car-terms-hero p {
            color: #64748b;
            margin: 0.5rem 0 0;
            max-width: 720px;
        }

        .car-terms-locale-nav {
            display: flex;
            gap: .75rem;
            flex-wrap: nowrap;
            overflow-x: auto;
            padding-bottom: .25rem;
        }

        .car-terms-locale-nav .nav-link {
            border-radius: 999px;
            border: 1px solid #dbe2f0;
            background: #fff;
            color: #334155;
            font-weight: 700;
            white-space: nowrap;
        }

        .car-terms-locale-nav .nav-link.active {
            background: linear-gradient(135deg, #2563eb, #0ea5e9);
            border-color: transparent;
            color: #fff;
        }

        .car-terms-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
        }

        .car-terms-field {
            padding: 1rem;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
        }

        .car-terms-field--full {
            grid-column: 1 / -1;
        }

        .car-terms-field .form-control {
            border-radius: 14px;
            background: #fff;
        }

        @media (max-width: 991.98px) {
            .car-terms-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <form action="{{ route('admin.car-detail-terms.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="car-terms-shell">
            <div class="car-terms-hero">
                <h2 class="mb-0">{{ __('Rental Terms For Car Details Page') }}</h2>
                <p>{{ __('These four content blocks are global and appear on every car details page. Update them per language here, without mixing them into the homepage editor.') }}</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-0">
                    <strong>{{ __('Please correct the following errors:') }}</strong>
                    <ul class="mb-0 mt-2 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="car-terms-card">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start gap-3 mb-4">
                    <div>
                        <h4 class="mb-1">{{ __('Rental Terms Content') }}</h4>
                        <p class="text-muted mb-0">{{ __('Each locale has its own mileage, fuel, deposit, and rental policy text.') }}</p>
                    </div>
                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">{{ count($activeLanguages) }} {{ __('Locales') }}</span>
                </div>

                <ul class="nav nav-pills car-terms-locale-nav mb-4" role="tablist">
                    @foreach ($activeLanguages as $language)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                                type="button"
                                data-bs-toggle="pill"
                                data-bs-target="#car-terms-locale-{{ $language->code }}"
                                role="tab"
                                aria-controls="car-terms-locale-{{ $language->code }}"
                                aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                {{ $language->name }}
                            </button>
                        </li>
                    @endforeach
                </ul>

                <div class="tab-content">
                    @foreach ($activeLanguages as $language)
                        @php
                            $locale = $language->code;
                            $translation = $translationsByLocale[$locale] ?? null;
                        @endphp

                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                            id="car-terms-locale-{{ $locale }}"
                            role="tabpanel">
                            <div class="car-terms-grid">
                                @foreach ($fieldLabels as $fieldName => $fieldLabel)
                                    <div class="car-terms-field car-terms-field--full">
                                        <label for="{{ $fieldName }}_{{ $locale }}" class="font-weight-bold mb-2">{{ __($fieldLabel) }}</label>
                                        <textarea
                                            name="{{ $fieldName }}[{{ $locale }}]"
                                            id="{{ $fieldName }}_{{ $locale }}"
                                            class="form-control @error($fieldName . '.' . $locale) is-invalid @enderror"
                                            rows="5">{{ old($fieldName . '.' . $locale, data_get($translation, $fieldName)) }}</textarea>
                                        @error($fieldName . '.' . $locale)
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-success btn-lg rounded-pill px-4">
                    <i class="fas fa-save me-1"></i>{{ __('Save Rental Terms') }}
                </button>
            </div>
        </div>
    </form>
@endsection
