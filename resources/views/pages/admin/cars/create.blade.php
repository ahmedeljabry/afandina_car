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

@section('content')
    @include('pages.admin.cars.partials.workspace', ['mode' => 'create'])
@endsection
