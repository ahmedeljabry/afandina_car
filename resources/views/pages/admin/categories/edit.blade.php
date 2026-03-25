@extends('layouts.admin_layout')

@section('title', 'Edit Category')

@section('page-title')
    {{ __('Edit Category') }}
@endsection

@section('content')
    @include('pages.admin.categories._form')
@endsection
