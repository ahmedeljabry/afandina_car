@extends('layouts.admin_layout')

@section('title', 'Create Category')

@section('page-title')
    {{ __('Create Category') }}
@endsection

@section('content')
    @include('pages.admin.categories._form')
@endsection
