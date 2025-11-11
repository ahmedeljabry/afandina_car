@extends('layouts.admin_layout')

@section('title', __('Manage Static Translations'))

@include('includes.admin.datatable_theme')

@section('page-title')
    {{ __('Static Translations') }}
@endsection

@php
    $translationMap = collect($staticTranslations)->groupBy('key');
    $uniqueKeys = $translationMap->count();
    $languageCount = $activeLanguages->count();
@endphp

@section('content')

    <div class="management-hero">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <div>
                <h2 class="mb-1">{{ __('Translation Management') }}</h2>
                <p class="mb-0">{{ __('Edit multi-locale strings inline and keep your copy in sync.') }}</p>
            </div>
            <div class="mt-3 mt-md-0">
                <span class="stat-pill">
                    <i class="fas fa-key"></i> {{ $uniqueKeys }} {{ __('keys') }}
                </span>
                <span class="stat-pill">
                    <i class="fas fa-language"></i> {{ $languageCount }} {{ __('locales') }}
                </span>
            </div>
        </div>
    </div>

    <div class="card management-card">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <div>
                <h4 class="mb-1">{{ __('Translation Workspace') }}</h4>
                <p class="text-muted mb-0">{{ __('Use the grid below to add or edit static translation keys per locale.') }}</p>
            </div>
            <div class="btn-toolbar mt-2 mt-md-0">
                <button type="button" class="btn btn-outline-primary mr-1" onclick="translationsTable.addNewRow()">
                    <i class="fas fa-plus mr-50"></i> {{ __('Add Row') }}
                </button>
                <button type="button" class="btn btn-success" onclick="translationsTable.saveTranslations()">
                    <i class="fas fa-save mr-50"></i> {{ __('Save Changes') }}
                </button>
            </div>
        </div>
        <div class="card-body">
            <div id="translation-table"></div>
        </div>
    </div>
@endsection

@push('styles')
    <link href="https://unpkg.com/tabulator-tables@5.4.4/dist/css/tabulator.min.css" rel="stylesheet">
    <style>
        .tabulator {
            border-radius: 16px;
            border: 1px solid #dbe2f0;
        }
        .tabulator-cell {
            padding: 10px !important;
        }
        .tabulator-header {
            background-color: #f4f6fb;
            border-bottom: 2px solid #dbe2f0;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://unpkg.com/tabulator-tables@5.4.4/dist/js/tabulator.min.js"></script>
    <script>
        const translationsTable = {
            tabulatorInstance: null,
            activeLanguages: @json($activeLanguages),

            init() {
                const translations = @json($staticTranslations);
                const translationMap = {};

                translations.forEach(entry => {
                    if (!translationMap[entry.key]) {
                        translationMap[entry.key] = {
                            id: entry.id,
                            key: entry.key,
                            section: entry.section,
                            created_at: entry.created_at,
                            updated_at: entry.updated_at
                        };
                    }
                    translationMap[entry.key][entry.locale] = entry.value;
                });

                const tableData = Object.values(translationMap);
                const columns = [
                    { title: "{{ __('Key') }}", field: "key", editor: "input", minWidth: 160 },
                    { title: "{{ __('Section') }}", field: "section", editor: "input", minWidth: 140 },
                ];

                this.activeLanguages.forEach(lang => {
                    columns.push({
                        title: lang.name,
                        field: lang.code,
                        editor: "input",
                        minWidth: 150
                    });
                });

                columns.push(
                    { title: "{{ __('Created At') }}", field: "created_at", sorter: "datetime", minWidth: 150 },
                    { title: "{{ __('Updated At') }}", field: "updated_at", sorter: "datetime", minWidth: 150 }
                );

                this.tabulatorInstance = new Tabulator("#translation-table", {
                    data: tableData,
                    layout: "fitColumns",
                    responsiveLayout: "collapse",
                    columns,
                    height: "520px"
                });
            },

            addNewRow() {
                const newRow = {
                    key: "",
                    section: ""
                };

                this.activeLanguages.forEach(lang => {
                    newRow[lang.code] = "";
                });

                this.tabulatorInstance.addRow(newRow, true);
            },

            saveTranslations() {
                const updatedData = this.tabulatorInstance.getData();
                const transformedData = [];

                updatedData.forEach(row => {
                    if (!row.key || !row.key.trim()) {
                        return;
                    }

                    this.activeLanguages.forEach(lang => {
                        transformedData.push({
                            id: row.id || null,
                            key: row.key.trim(),
                            locale: lang.code,
                            value: row[lang.code] || "",
                            section: row.section || ""
                        });
                    });
                });

                const csrf = $('meta[name="csrf-token"]').attr('content');

                fetch('{{ url("admin/static-translations/save") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                    },
                    body: JSON.stringify({ translations: transformedData }),
                })
                    .then(response => response.json())
                    .then(result => {
                        Swal.fire({
                            icon: result.success ? 'success' : 'error',
                            title: result.success ? '{{ __('Saved!') }}' : '{{ __('Oops!') }}',
                            text: result.message || '{{ __('Translations saved successfully.') }}'
                        }).then(() => {
                            if (result.success) {
                                window.location.reload();
                            }
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: '{{ __('Error') }}',
                            text: '{{ __('Unable to save translations right now.') }}'
                        });
                    });
            }
        };

        document.addEventListener('DOMContentLoaded', () => {
            translationsTable.init();
        });
    </script>
@endpush
