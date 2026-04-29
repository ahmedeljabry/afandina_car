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
                                                       aria-label="{{ __('Toggle show in home for this post') }}"
                                                       @checked($item->show_in_home)>
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
                                                       aria-label="{{ __('Toggle active status for this post') }}"
                                                       @checked($item->is_active)>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <span class="status-pill mt-50 {{ $item->is_active ? 'active' : 'inactive' }}" data-blog-status-pill>
                                            <i class="fas {{ $item->is_active ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                            <span data-blog-status-label>{{ $item->is_active ? __('Live') : __('Hidden') }}</span>
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
                                                data-model="{{ $modelName }}"
                                                data-delete-url="{{ route('admin.' . $modelName . '.destroy', $item->id) }}">
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

            const dataTable = $table.DataTable({
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

            function syncStatusPill($checkbox) {
                if ($checkbox.data('attribute') !== 'is_active') {
                    return;
                }

                const isActive = $checkbox.is(':checked');
                const $pill = $checkbox.closest('.toggle-stack').find('[data-blog-status-pill]');
                const $icon = $pill.find('i');

                $pill
                    .toggleClass('active', isActive)
                    .toggleClass('inactive', !isActive);

                $icon
                    .toggleClass('fa-check-circle', isActive)
                    .toggleClass('fa-times-circle', !isActive);

                $pill.find('[data-blog-status-label]').text(isActive ? "{{ __('Live') }}" : "{{ __('Hidden') }}");
            }

            $table.on('change', '.toggle-status', function () {
                const $checkbox = $(this);
                const previousValue = !$checkbox.is(':checked');

                function revertToggle() {
                    $checkbox.prop('checked', previousValue);
                    syncStatusPill($checkbox);
                }

                $checkbox.prop('disabled', true);
                syncStatusPill($checkbox);

                $.ajax({
                    url: "{{ route('admin.toggleStatus') }}",
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        model: $checkbox.data('model'),
                        id: $checkbox.data('id'),
                        value: $checkbox.is(':checked') ? 1 : 0,
                        attribute: $checkbox.data('attribute')
                    }
                }).done(function (response) {
                    if (!response || response.success !== true) {
                        revertToggle();

                        if (window.AdminPanel) {
                            window.AdminPanel.notify('error', response && response.message ? response.message : "{{ __('The blog option could not be updated.') }}", {
                                title: "{{ __('Update failed') }}"
                            });
                        }

                        return;
                    }

                    if (Object.prototype.hasOwnProperty.call(response, 'value')) {
                        $checkbox.prop('checked', !!response.value);
                    }

                    syncStatusPill($checkbox);

                    if (window.AdminPanel) {
                        window.AdminPanel.toast('success', "{{ __('Blog option updated.') }}");
                    }
                }).fail(function () {
                    revertToggle();

                    if (window.AdminPanel) {
                        window.AdminPanel.notify('error', "{{ __('The blog option could not be updated.') }}", {
                            title: "{{ __('Update failed') }}"
                        });
                    }
                }).always(function () {
                    $checkbox.prop('disabled', false);
                    dataTable.rows().invalidate('dom');
                });
            });

            $table.on('click', '.delete-btn', function () {
                const $button = $(this);
                const deleteUrl = $button.data('delete-url');

                if (!deleteUrl) {
                    return;
                }

                const deletePost = function () {
                    $button.prop('disabled', true);

                    $.ajax({
                        url: deleteUrl,
                        method: 'POST',
                        dataType: 'html',
                        data: {
                            _method: 'DELETE'
                        }
                    }).done(function () {
                        window.location.reload();
                    }).fail(function () {
                        $button.prop('disabled', false);

                        if (window.AdminPanel) {
                            window.AdminPanel.notify('error', "{{ __('The blog post could not be deleted.') }}", {
                                title: "{{ __('Delete failed') }}"
                            });
                        }
                    });
                };

                const confirmation = window.AdminPanel && window.Swal
                    ? window.AdminPanel.confirm({
                        title: "{{ __('Delete this blog post?') }}",
                        text: "{{ __('This action cannot be undone.') }}",
                        icon: 'warning',
                        confirmButtonText: "{{ __('Delete') }}",
                        cancelButtonText: "{{ __('Cancel') }}",
                        confirmButtonColor: '#ea5455'
                    })
                    : Promise.resolve({ isConfirmed: window.confirm("{{ __('Delete this blog post?') }}") });

                confirmation.then(function (result) {
                    if (result && result.isConfirmed) {
                        deletePost();
                    }
                });
            });
        });
    </script>
@endpush
