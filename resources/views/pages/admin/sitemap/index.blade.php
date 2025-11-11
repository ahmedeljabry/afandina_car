@extends('layouts.admin_layout')

@section('title', __('Manage Sitemap'))

@include('includes.admin.datatable_theme')

@section('page-title')
    {{ __('Sitemap') }}
@endsection

@section('content')

    <div class="management-hero">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <div>
                <h2 class="mb-1">{{ __('Sitemap Automation') }}</h2>
                <p class="mb-0">{{ __('Generate, download, and notify search engines about your latest structure.') }}</p>
            </div>
            <div class="mt-3 mt-md-0">
                <span class="stat-pill">
                    <i class="fas fa-clock"></i> {{ __('Last run') }}:
                    <span class="font-weight-bold">{{ $lastGeneratedAt ?? __('N/A') }}</span>
                </span>
                <span class="stat-pill">
                    <i class="fas fa-globe"></i> {{ __('Live endpoints ready') }}
                </span>
            </div>
        </div>
    </div>

    <div class="card management-card">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <div>
                <h4 class="mb-1">{{ __('Sitemap Utilities') }}</h4>
                <p class="text-muted mb-0">{{ __('Keep crawlers informed with one-click controls.') }}</p>
            </div>
            <a href="{{ route('admin.sitemap.download') }}" class="btn btn-success shadow-sm mt-2 mt-md-0">
                <i class="fas fa-download mr-50"></i> {{ __('Download Sitemap') }}
            </a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-2">
                    <div class="border rounded p-3 h-100 d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="font-weight-bold mb-2">{{ __('Generate Fresh File') }}</h5>
                            <p class="text-muted mb-0">{{ __('Rebuild the XML sitemap based on current published entities.') }}</p>
                        </div>
                        <button id="generateSitemap" class="btn btn-primary btn-block mt-3">
                            <i class="fas fa-sync"></i> {{ __('Generate Sitemap') }}
                        </button>
                    </div>
                </div>
                <div class="col-md-6 mb-2">
                    <div class="border rounded p-3 h-100 d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="font-weight-bold mb-2">{{ __('Notify Search Engines') }}</h5>
                            <p class="text-muted mb-0">{{ __('Ping Google & Bing so they recrawl the latest structure promptly.') }}</p>
                        </div>
                        <button id="notifySearchEngines" class="btn btn-warning btn-block mt-3">
                            <i class="fas fa-bullhorn"></i> {{ __('Notify Google & Bing') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function () {
            const csrf = $('meta[name="csrf-token"]').attr('content');

            function handleAction(buttonSelector, url, successMessage) {
                const $btn = $(buttonSelector);
                const originalHtml = $btn.html();

                $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm mr-50"></span>{{ __('Working...') }}');

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(result => {
                    Swal.fire({
                        icon: result.success ? 'success' : 'error',
                        title: result.success ? '{{ __('Done!') }}' : '{{ __('Something went wrong') }}',
                        text: result.message || successMessage
                    });
                })
                .catch(() => {
                    Swal.fire({
                        icon: 'error',
                        title: '{{ __('Error') }}',
                        text: '{{ __('Unable to complete the request right now.') }}'
                    });
                })
                .finally(() => {
                    $btn.prop('disabled', false).html(originalHtml);
                });
            }

            $('#generateSitemap').on('click', function () {
                handleAction('#generateSitemap', "{{ route('admin.sitemap.generate') }}", "{{ __('Sitemap generated successfully!') }}");
            });

            $('#notifySearchEngines').on('click', function () {
                handleAction('#notifySearchEngines', "{{ route('admin.sitemap.notify') }}", "{{ __('Search engines notified successfully!') }}");
            });
        });
    </script>
@endpush
