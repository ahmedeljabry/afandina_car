@extends('layouts.admin_layout')

@section('title', 'Pages Management')

@section('page-title')
    Pages Management
@endsection

@section('page-actions')
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
        <i class="ft-arrow-left"></i> {{ __('Back to Dashboard') }}
    </a>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-file-alt mr-2"></i>
                        Available Pages
                    </h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Page Name</th>
                                    <th>Slug</th>
                                    <th>Status</th>
                                    <th>Last Updated</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pages as $page)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <strong>{{ $page->name }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge badge-secondary">{{ $page->slug }}</span>
                                        </td>
                                        <td>
                                            @if($page->is_active)
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check-circle"></i> Active
                                                </span>
                                            @else
                                                <span class="badge badge-danger">
                                                    <i class="fas fa-times-circle"></i> Inactive
                                                </span>
                                            @endif
                                        </td>
                                        <td>{{ $page->updated_at->format('Y-m-d H:i') }}</td>
                                        <td>
                                            <a href="{{ route('admin.pages.edit', $page->slug) }}" 
                                               class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="alert alert-info mb-0">
                                                <i class="fas fa-info-circle mr-2"></i>
                                                No pages found. Please create pages through database seeding.
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



