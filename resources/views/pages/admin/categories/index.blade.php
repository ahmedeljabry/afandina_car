@extends('layouts.admin_layout')

@section('title', 'Categories')

@section('page-title')
    {{ __('Categories') }}
@endsection

@push('styles')
    <style>
        .category-hero {
            padding: 1.75rem 2rem;
            border-radius: 26px;
            background: linear-gradient(135deg, #0f172a, #1d4ed8 58%, #38bdf8);
            color: #fff;
            box-shadow: 0 24px 60px rgba(30, 64, 175, 0.24);
            margin-bottom: 1.5rem;
        }

        .category-hero p {
            color: rgba(255, 255, 255, 0.78);
            max-width: 760px;
        }

        .category-stat-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .category-stat-card,
        .category-toolbar,
        .category-list-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 24px;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.06);
        }

        .category-stat-card {
            padding: 1.25rem;
        }

        .category-stat-card span {
            display: block;
            color: #64748b;
            font-size: 0.86rem;
            font-weight: 600;
        }

        .category-stat-card strong {
            display: block;
            margin-top: 0.4rem;
            color: #0f172a;
            font-size: 1.7rem;
            line-height: 1;
        }

        .category-toolbar {
            padding: 1rem 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .category-toolbar .form-control {
            border-radius: 999px;
            min-width: 280px;
        }

        .category-list-card {
            padding: 1.25rem;
        }

        .category-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1.25rem;
        }

        .category-item {
            border: 1px solid #e2e8f0;
            border-radius: 24px;
            overflow: hidden;
            background: linear-gradient(180deg, #ffffff, #f8fafc);
            box-shadow: 0 16px 35px rgba(15, 23, 42, 0.06);
        }

        .category-item-cover {
            height: 210px;
            background: linear-gradient(135deg, #dbeafe, #eff6ff);
            position: relative;
        }

        .category-item-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .category-badge {
            position: absolute;
            top: 1rem;
            left: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.45rem 0.8rem;
            border-radius: 999px;
            font-weight: 700;
            font-size: 0.78rem;
            backdrop-filter: blur(6px);
        }

        .category-badge.active {
            background: rgba(16, 185, 129, 0.16);
            color: #065f46;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .category-badge.inactive {
            background: rgba(248, 113, 113, 0.16);
            color: #991b1b;
            border: 1px solid rgba(248, 113, 113, 0.2);
        }

        .category-item-body {
            padding: 1.2rem;
        }

        .category-item-body h5 {
            color: #0f172a;
            margin-bottom: 0.35rem;
            font-weight: 700;
        }

        .category-item-body p {
            color: #64748b;
            margin-bottom: 0.9rem;
            min-height: 3rem;
        }

        .category-meta {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .category-meta div {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 0.85rem;
        }

        .category-meta span {
            display: block;
            color: #64748b;
            font-size: 0.78rem;
            font-weight: 600;
        }

        .category-meta strong {
            display: block;
            color: #0f172a;
            font-size: 1rem;
            margin-top: 0.15rem;
        }

        .category-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.55rem;
            align-items: center;
        }

        .category-actions .btn {
            border-radius: 999px;
        }

        .category-toggle {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            padding: 0.5rem 0.9rem;
            border-radius: 999px;
            background: #f8fafc;
            border: 1px solid #dbe2f0;
        }

        .category-toggle input {
            width: 2.4rem;
            height: 1.25rem;
        }

        .category-empty {
            padding: 3rem 1.5rem;
            text-align: center;
            color: #64748b;
        }

        @media (max-width: 1199.98px) {
            .category-stat-grid,
            .category-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 767.98px) {
            .category-stat-grid,
            .category-grid {
                grid-template-columns: 1fr;
            }

            .category-toolbar {
                flex-direction: column;
                align-items: stretch;
            }

            .category-toolbar .form-control {
                min-width: 0;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $placeholderImage = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='720' height='420' viewBox='0 0 720 420'%3E%3Crect width='100%25' height='100%25' fill='%23eff6ff'/%3E%3Ctext x='50%25' y='50%25' fill='%2364748b' font-size='26' text-anchor='middle' dy='.3em'%3ENo Category Cover%3C/text%3E%3C/svg%3E";
    @endphp

    <div class="category-hero">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start gap-3">
            <div>
                <h2 class="mb-2">{{ __('Category Management') }}</h2>
                <p class="mb-0">{{ __('Create, edit, and disable vehicle categories from one clean workspace. Each card gives you a quick read on content readiness and assigned inventory.') }}</p>
            </div>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-warning text-dark fw-semibold rounded-pill px-4">
                <i class="fas fa-plus me-1"></i>{{ __('Add Category') }}
            </a>
        </div>
    </div>

    <div class="category-stat-grid">
        <div class="category-stat-card">
            <span>{{ __('Total Categories') }}</span>
            <strong>{{ (int) ($stats['total'] ?? 0) }}</strong>
        </div>
        <div class="category-stat-card">
            <span>{{ __('Active') }}</span>
            <strong>{{ (int) ($stats['active'] ?? 0) }}</strong>
        </div>
        <div class="category-stat-card">
            <span>{{ __('Inactive') }}</span>
            <strong>{{ (int) ($stats['inactive'] ?? 0) }}</strong>
        </div>
        <div class="category-stat-card">
            <span>{{ __('With Cover Image') }}</span>
            <strong>{{ (int) ($stats['with_image'] ?? 0) }}</strong>
        </div>
    </div>

    <div class="category-toolbar">
        <input type="search" class="form-control" id="category-search" placeholder="{{ __('Search categories by name or slug') }}">
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary rounded-pill px-4">{{ __('Create New') }}</a>
        </div>
    </div>

    <div class="category-list-card">
        @if ($items->count())
            <div class="category-grid" id="category-grid">
                @foreach ($items as $item)
                    @php
                        $translation = $item->translations->firstWhere('locale', $defaultLocale ?? 'en') ?? $item->translations->first();
                        $name = $translation?->name ?? __('Untitled Category');
                        $description = $translation?->description ?? __('No description added yet.');
                        $image = filled($item->image_path) ? asset('storage/' . $item->image_path) : $placeholderImage;
                    @endphp
                    <article class="category-item" data-category-card data-search="{{ strtolower(trim($name . ' ' . ($item->slug ?? ''))) }}">
                        <div class="category-item-cover">
                            <img src="{{ $image }}" alt="{{ $name }}">
                            <span class="category-badge {{ $item->is_active ? 'active' : 'inactive' }}">
                                <i class="fas {{ $item->is_active ? 'fa-check-circle' : 'fa-pause-circle' }}"></i>
                                {{ $item->is_active ? __('Active') : __('Inactive') }}
                            </span>
                        </div>
                        <div class="category-item-body">
                            <h5>{{ $name }}</h5>
                            <p>{{ \Illuminate\Support\Str::limit(strip_tags($description), 120) }}</p>

                            <div class="category-meta">
                                <div>
                                    <span>{{ __('Slug') }}</span>
                                    <strong>{{ $item->slug ?: __('Auto-generated') }}</strong>
                                </div>
                                <div>
                                    <span>{{ __('Cars') }}</span>
                                    <strong>{{ (int) ($item->cars_count ?? 0) }}</strong>
                                </div>
                                <div>
                                    <span>{{ __('Active Cars') }}</span>
                                    <strong>{{ (int) ($item->active_cars_count ?? 0) }}</strong>
                                </div>
                                <div>
                                    <span>{{ __('Updated') }}</span>
                                    <strong>{{ $item->updated_at?->diffForHumans() ?? __('N/A') }}</strong>
                                </div>
                            </div>

                            <div class="category-actions">
                                <a href="{{ route('admin.categories.edit', $item->id) }}" class="btn btn-outline-primary btn-sm">{{ __('Edit') }}</a>
                                <a href="{{ route('admin.categories.show', $item->id) }}" class="btn btn-outline-dark btn-sm">{{ __('View') }}</a>

                                <label class="category-toggle mb-0">
                                    <input type="checkbox" class="toggle-status" data-model="categories" data-attribute="is_active" data-id="{{ $item->id }}" {{ $item->is_active ? 'checked' : '' }}>
                                    <span>{{ __('Enabled') }}</span>
                                </label>

                                <form action="{{ route('admin.categories.destroy', $item->id) }}" method="POST" onsubmit="return confirm('{{ __('Delete this category?') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">{{ __('Delete') }}</button>
                                </form>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="d-flex justify-content-end mt-4">
                {{ $items->links() }}
            </div>
        @else
            <div class="category-empty">
                <h5 class="mb-2">{{ __('No categories yet') }}</h5>
                <p class="mb-3">{{ __('Create your first category to organize the catalog properly.') }}</p>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary rounded-pill px-4">{{ __('Create Category') }}</a>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        $(function () {
            $('#category-search').on('input', function () {
                const term = ($(this).val() || '').toLowerCase().trim();
                $('[data-category-card]').each(function () {
                    const haystack = ($(this).data('search') || '').toString();
                    $(this).toggle(!term || haystack.includes(term));
                });
            });

            $(document).on('change', '.toggle-status', function () {
                const checkbox = $(this);
                $.ajax({
                    url: "{{ route('admin.toggleStatus') }}",
                    method: 'POST',
                    data: {
                        model: checkbox.data('model'),
                        id: checkbox.data('id'),
                        value: checkbox.is(':checked') ? 1 : 0,
                        attribute: checkbox.data('attribute')
                    }
                }).fail(function () {
                    checkbox.prop('checked', !checkbox.is(':checked'));
                    if (window.Swal) {
                        Swal.fire({
                            icon: 'error',
                            title: "{{ __('Update failed') }}",
                            text: "{{ __('The category status could not be updated.') }}"
                        });
                    }
                }).done(function () {
                    window.location.reload();
                });
            });
        });
    </script>
@endpush
