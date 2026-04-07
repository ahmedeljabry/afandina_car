@extends('layouts.admin_layout')

@section('title', __('Edit Car'))
@section('page-title', __('Edit Car'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.cars.index') }}">{{ __('Cars') }}</a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Edit') }}</li>
@endsection

@section('page-actions')
    <a href="{{ route('admin.cars.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center me-2 mb-2">
        <i class="ti ti-arrow-left me-1"></i>{{ __('Back to Cars') }}
    </a>
    <a href="{{ route('admin.cars.edit_images', $item->id) }}" class="btn btn-primary d-inline-flex align-items-center mb-2">
        <i class="ti ti-photo me-1"></i>{{ __('Manage Images') }}
    </a>
@endsection

@section('content')
    @include('pages.admin.cars.partials.workspace', ['mode' => 'edit'])
@endsection
