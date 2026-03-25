@extends('layouts.admin_layout')

@section('title', 'Category Details')

@section('page-title')
    {{ __('Category Details') }}
@endsection

@push('styles')
    <style>
        .category-show-hero {
            padding: 1.5rem;
            border-radius: 26px;
            background: linear-gradient(135deg, #0f172a, #1d4ed8 58%, #38bdf8);
            color: #fff;
            box-shadow: 0 24px 60px rgba(30, 64, 175, 0.24);
            margin-bottom: 1.5rem;
        }

        .category-show-grid {
            display: grid;
            grid-template-columns: 360px minmax(0, 1fr);
            gap: 1.5rem;
        }

        .category-show-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 24px;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.06);
            padding: 1.5rem;
        }

        .category-show-card img {
            width: 100%;
            height: 280px;
            object-fit: cover;
            border-radius: 20px;
            background: #eff6ff;
        }

        .category-show-stats {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: .9rem;
            margin-top: 1rem;
        }

        .category-show-stats div {
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            padding: 0.95rem;
            background: #f8fafc;
        }

        .category-show-stats span {
            display: block;
            color: #64748b;
            font-size: .82rem;
            font-weight: 600;
        }

        .category-show-stats strong {
            display: block;
            margin-top: .2rem;
            color: #0f172a;
            font-size: 1.05rem;
        }

        .category-show-tabs .nav-link {
            border-radius: 999px;
            border: 1px solid #dbe2f0;
            color: #334155;
            font-weight: 700;
            margin-right: .65rem;
            margin-bottom: .65rem;
        }

        .category-show-tabs .nav-link.active {
            background: linear-gradient(135deg, #2563eb, #0ea5e9);
            color: #fff;
            border-color: transparent;
        }

        .category-show-section {
            border: 1px solid #e2e8f0;
            border-radius: 22px;
            padding: 1.2rem;
            background: #fff;
        }

        .category-show-section + .category-show-section {
            margin-top: 1rem;
        }

        .category-show-kicker {
            color: #2563eb;
            text-transform: uppercase;
            letter-spacing: .08em;
            font-size: .78rem;
            font-weight: 800;
            margin-bottom: .75rem;
        }

        .keyword-pill {
            display: inline-flex;
            align-items: center;
            padding: .35rem .75rem;
            border-radius: 999px;
            background: #eff6ff;
            color: #1d4ed8;
            font-weight: 600;
            margin: 0 .45rem .45rem 0;
        }

        @media (max-width: 991.98px) {
            .category-show-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $placeholderImage = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='720' height='420' viewBox='0 0 720 420'%3E%3Crect width='100%25' height='100%25' fill='%23eff6ff'/%3E%3Ctext x='50%25' y='50%25' fill='%2364748b' font-size='26' text-anchor='middle' dy='.3em'%3ENo Category Cover%3C/text%3E%3C/svg%3E";
        $image = filled($item->image_path) ? asset('storage/' . $item->image_path) : $placeholderImage;
    @endphp

    <div class="category-show-hero">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start gap-3">
            <div>
                <h2 class="mb-2">{{ __('Category Overview') }}</h2>
                <p class="mb-0">{{ __('Review the publishing status, storefront content, and SEO setup for this category.') }}</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-light rounded-pill px-4">{{ __('Back') }}</a>
                <a href="{{ route('admin.categories.edit', $item->id) }}" class="btn btn-warning text-dark rounded-pill px-4">{{ __('Edit Category') }}</a>
            </div>
        </div>
    </div>

    <div class="category-show-grid">
        <div class="category-show-card">
            <img src="{{ $image }}" alt="{{ __('Category cover') }}">
            <div class="category-show-stats">
                <div>
                    <span>{{ __('Status') }}</span>
                    <strong>{{ $item->is_active ? __('Active') : __('Inactive') }}</strong>
                </div>
                <div>
                    <span>{{ __('Slug') }}</span>
                    <strong>{{ $item->slug ?: __('Auto-generated') }}</strong>
                </div>
                <div>
                    <span>{{ __('Assigned Cars') }}</span>
                    <strong>{{ (int) ($item->cars_count ?? 0) }}</strong>
                </div>
                <div>
                    <span>{{ __('Active Cars') }}</span>
                    <strong>{{ (int) ($item->active_cars_count ?? 0) }}</strong>
                </div>
                <div>
                    <span>{{ __('Created') }}</span>
                    <strong>{{ $item->created_at?->format('d M Y') ?? __('N/A') }}</strong>
                </div>
                <div>
                    <span>{{ __('Updated') }}</span>
                    <strong>{{ $item->updated_at?->diffForHumans() ?? __('N/A') }}</strong>
                </div>
            </div>
        </div>

        <div class="category-show-card">
            <ul class="nav nav-pills category-show-tabs mb-3">
                @foreach ($activeLanguages as $lang)
                    <li class="nav-item">
                        <a class="nav-link {{ $loop->first ? 'active' : '' }}" data-toggle="pill" href="#category-show-{{ $lang->code }}">
                            {{ $lang->name }}
                        </a>
                    </li>
                @endforeach
            </ul>

            <div class="tab-content">
                @foreach ($activeLanguages as $lang)
                    @php
                        $translation = $item->translations->firstWhere('locale', $lang->code);
                        $metaKeywords = json_decode($translation?->meta_keywords ?? '[]', true);
                        $questions = $item->seoQuestions->where('locale', $lang->code);
                    @endphp
                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="category-show-{{ $lang->code }}">
                        <div class="category-show-section">
                            <div class="category-show-kicker">{{ __('Core Copy') }}</div>
                            <h4 class="mb-2">{{ $translation?->name ?? __('No name') }}</h4>
                            <p class="text-muted mb-3">{{ $translation?->title ?? __('No section title') }}</p>
                            <div>{{ $translation?->description ?? __('No description available.') }}</div>
                            @if (filled($translation?->article))
                                <hr>
                                <div>{!! $translation?->article !!}</div>
                            @endif
                        </div>

                        <div class="category-show-section">
                            <div class="category-show-kicker">{{ __('SEO Metadata') }}</div>
                            <div class="mb-3">
                                <strong>{{ __('Meta Title') }}:</strong>
                                <div class="text-muted">{{ $translation?->meta_title ?? __('N/A') }}</div>
                            </div>
                            <div class="mb-3">
                                <strong>{{ __('Meta Description') }}:</strong>
                                <div class="text-muted">{{ $translation?->meta_description ?? __('N/A') }}</div>
                            </div>
                            <div>
                                <strong>{{ __('Meta Keywords') }}:</strong>
                                <div class="mt-2">
                                    @forelse ($metaKeywords as $keyword)
                                        <span class="keyword-pill">{{ is_array($keyword) ? ($keyword['value'] ?? '') : '' }}</span>
                                    @empty
                                        <span class="text-muted">{{ __('No keywords added') }}</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div class="category-show-section">
                            <div class="category-show-kicker">{{ __('SEO Questions & Answers') }}</div>
                            @forelse ($questions as $question)
                                <div class="mb-3">
                                    <strong>{{ $question->question_text }}</strong>
                                    <div class="text-muted mt-1">{{ $question->answer_text }}</div>
                                </div>
                            @empty
                                <div class="text-muted">{{ __('No SEO questions added for this locale.') }}</div>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
