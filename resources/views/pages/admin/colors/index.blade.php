@extends('layouts.admin_layout')

@section('title', 'List of ' . $modelName)


@include('includes.admin.datatable_theme')

@section('page-title')
    {{ $modelName }} List
@endsection

@section('content')

    @php
        $itemsCollection = $items instanceof \Illuminate\Pagination\AbstractPaginator ? collect($items->items()) : collect($items);
        $totalItems = $itemsCollection->count();
        $activeItems = $itemsCollection->where('is_active', true)->count();
        $inactiveItems = max($totalItems - $activeItems, 0);
    @endphp

    <div class="management-hero">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <div>
                <h2 class="mb-1">{{ __('Manage') }} {{ $modelName }}</h2>
                <p class="mb-0">{{ __('Keep this dataset polished and ready for your storefront experience.') }}</p>
            </div>
            <div class="mt-3 mt-md-0">
                <span class="stat-pill">
                    <i class="fas fa-layer-group"></i> {{ $totalItems }} {{ __('entries') }}
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
        <div class="card-header bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title text-dark">{{ $modelName }} List</h3>
                <a href="{{ route('admin.' . $modelName . '.create') }}" class="btn btn-primary shadow-sm">
                    <i class="fas fa-plus"></i> Add {{ $modelName }}
                </a>
            </div>
        </div>

        <div class="card-body pt-1">
            <div class="table-responsive">
                <table id="colors-table" class="table table-hover table-striped table-modern w-100 datatable">
                    <thead class="bg-dark text-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Color Code</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->translations->first()->name ?? 'N/A' }}</td>
                                <td>
                                    <!-- Display Color Box and Code -->
                                    <div style="display: flex; align-items: center;">
                                        <!-- Color Box -->
                                        <span
                                            style="display: inline-block; width: 30px; height: 30px; background-color: {{ $item->color_code}}; border: 1px solid #ddd; margin-right: 10px;"></span>
                                        <!-- Color Code -->
                                        <span>{{ $item->color_code }}</span>
                                    </div>
                                </td>
                                <td>
                                    <!-- Custom Toggle Switch -->
                                    <label class="switch">
                                        <input type="checkbox" class="toggle-status" data-model="{{ $modelName }}"
                                            data-attribute="is_active" data-id="{{ $item->id }}" {{ $item->is_active ? 'checked' : '' }}>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>{{ $item->created_at ? $item->created_at->format('d M, Y') : 'N/A' }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        {{-- <a href="{{ route('admin.' . $modelName . '.show', $item->id) }}"
                                            class="btn btn-primary btn-sm shadow-sm mr-1">--}}
                                            {{-- <i class="fas fa-eye"></i> Show--}}
                                            {{-- </a>--}}
                                        <a href="{{ route('admin.' . $modelName . '.edit', $item->id) }}"
                                            class="btn btn-info btn-sm shadow-sm mr-1">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm shadow-sm delete-btn"
                                            data-id="{{ $item->id }}" data-model="{{ $modelName }}">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>

                                    </div>
                                </td>


                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-light">
            <div class="d-flex justify-content-end">
                {{ $items->links() }}
            </div>
        </div>
</div>@endsection


@push('scripts')
    <script>
        $(function () {
            const $table = $('#colors-table');
            if (!$table.length || !$table.find('tbody tr').length) {
                return;
            }

            $table.DataTable({
                order: [[1, 'asc']],
                autoWidth: false,
                pageLength: 10,
                columnDefs: [
                    { orderable: false, targets: [-1] }
                ],
                language: {
                    search: "",
                    searchPlaceholder: "{{ __('Search :entity', ['entity' => $modelName]) }}",
                    lengthMenu: "{{ __('Show _MENU_ entries') }}",
                    info: "{{ __('Showing _START_ to _END_ of _TOTAL_ entries') }}",
                    infoEmpty: "{{ __('Showing 0 to 0 of 0 entries') }}",
                    zeroRecords: "{{ __('No matching records found') }}",
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
