@extends('layouts.admin_layout')

@section('title', 'List of ' . $modelName)

@section('page-title')
    {{ $modelName }} List
@endsection

@include('includes.admin.datatable_theme')

@section('content')
    @php
        $itemsCollection = $items instanceof \Illuminate\Pagination\AbstractPaginator ? collect($items->items()) : collect($items);
        $totalItems = $itemsCollection->count();
        $activeItems = $itemsCollection->where('is_active', true)->count();
        $homeItems = $itemsCollection->where('show_in_home', true)->count();
        $inactiveItems = max($totalItems - $activeItems, 0);
    @endphp

    <div class="management-hero">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <div>
                <h2 class="mb-1">{{ __('Manage') }} {{ $modelName }}</h2>
                <p>{{ __('Storytell with confidence—control featured slots and live articles.') }}</p>
            </div>
            <div class="mt-3 mt-md-0">
                <span class="stat-pill">
                    <i class="fas fa-newspaper"></i> {{ $totalItems }} {{ __('posts') }}
                </span>
                <span class="stat-pill">
                    <i class="fas fa-home"></i> {{ $homeItems }} {{ __('featured') }}
                </span>
                <span class="stat-pill">
                    <i class="fas fa-toggle-on"></i> {{ $activeItems }} {{ __('active') }}
                </span>
                <span class="stat-pill">
                    <i class="fas fa-toggle-off"></i> {{ $inactiveItems }} {{ __('inactive') }}
                </span>
            </div>
        </div>
    </div>

    <div class="card management-card">
        <div class="card-header">
            <div class="d-flex flex-column flex-md-row justify-content-between gap-2">
                <div>
                    <h4 class="mb-1">{{ $modelName }} {{ __('Studio') }}</h4>
                    <p class="text-muted mb-0">{{ __('Track thumbnails, spotlight settings, and go-live status in one spot.') }}</p>
                </div>
                <div class="management-toolbar text-md-right">
                    <a href="{{ route('admin.' . $modelName . '.create') }}" class="btn btn-primary shadow-sm">
                        <i class="fas fa-plus mr-50"></i> {{ __('Add') }} {{ $modelName }}
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body pt-1">
            <div class="table-responsive">
                <table id="blogs-table" class="table table-hover table-striped management-table w-100">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 60px;">#</th>
                            <th>{{ __('Cover') }}</th>
                            <th>{{ __('Title') }}</th>
                            <th>{{ __('Options') }}</th>
                            <th>{{ __('Created') }}</th>
                            <th class="text-center" style="width: 160px;">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if ($item->image_path)
                                        <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->translations->first()->title ?? __('Post cover') }}" class="media-thumb">
                                    @else
                                        <span class="text-muted">{{ __('N/A') }}</span>
                                    @endif
                                </td>
                                <td class="text-bold-600">
                                    {{ $item->translations->first()->title ?? __('N/A') }}
                                </td>
                                <td>
                                    <div class="toggle-stack">
                                        <div>
                                            <div class="toggle-label">{{ __('Show in Home') }}</div>
                                            <label class="switch mb-0">
                                                <input type="checkbox"
                                                       class="toggle-status"
                                                       data-model="{{ $modelName }}"
                                                       data-attribute="show_in_home"
                                                       data-id="{{ $item->id }}"
                                                       {{ $item->show_in_home ? 'checked' : '' }}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div>
                                            <div class="toggle-label">{{ __('Active') }}</div>
                                            <label class="switch mb-0">
                                                <input type="checkbox"
                                                       class="toggle-status"
                                                       data-model="{{ $modelName }}"
                                                       data-attribute="is_active"
                                                       data-id="{{ $item->id }}"
                                                       {{ $item->is_active ? 'checked' : '' }}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <span class="status-pill mt-50 {{ $item->is_active ? 'active' : 'inactive' }}">
                                            <i class="fas {{ $item->is_active ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                            {{ $item->is_active ? __('Live') : __('Hidden') }}
                                        </span>
                                    </div>
                                </td>
                                <td>{{ $item->created_at ? $item->created_at->format('d M, Y') : __('N/A') }}</td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.' . $modelName . '.edit', $item->id) }}"
                                           class="btn btn-outline-info">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button"
                                                class="btn btn-outline-danger delete-btn"
                                                data-id="{{ $item->id }}"
                                                data-model="{{ $modelName }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="empty-row">
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fas fa-info-circle mr-1"></i> {{ __('No blog posts found yet.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if (method_exists($items, 'links'))
            <div class="card-footer bg-light">
                <div class="d-flex justify-content-end">
                    {{ $items->links() }}
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        $(function () {
            const $table = $('#blogs-table');
            if (!$table.length || !$table.find('tbody tr').not('.empty-row').length) {
                return;
            }

            $table.DataTable({
                order: [[2, 'asc']],
                autoWidth: false,
                pageLength: 10,
                columnDefs: [
                    { orderable: false, targets: [0, 1, 3, 5] }
                ],
                language: {
                    search: "",
                    searchPlaceholder: "{{ __('Search posts') }}",
                    lengthMenu: "{{ __('Show _MENU_ entries') }}",
                    info: "{{ __('Showing _START_ to _END_ of _TOTAL_ entries') }}",
                    infoEmpty: "{{ __('Showing 0 to 0 of 0 entries') }}",
                    zeroRecords: "{{ __('No matching posts found') }}",
                    paginate: {
                        first: "{{ __('First') }}",
                        previous: "{{ __('Previous') }}",
                        next: "{{ __('Next') }}",
                        last: "{{ __('Last') }}"
                    }
                },
                dom:
                    "<'row align-items-center mb-2'<'col-sm-6'l><'col-sm-6 text-sm-right'f>>" +
                    "t" +
                    "<'row align-items-center mt-2'<'col-sm-5'i><'col-sm-7'p>>"
            });
        });
    </script>
@endpush
