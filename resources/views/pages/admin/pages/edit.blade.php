@extends('layouts.admin_layout')

@section('title', 'Edit Page: ' . ucfirst($page->name))

@section('page-title')
    Edit Page: <strong>{{ ucfirst($page->name) }}</strong>
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.pages.index') }}">Pages</a></li>
    <li class="breadcrumb-item active">{{ ucfirst($page->name) }}</li>
@endsection

@section('page-actions')
    <a href="{{ route('admin.pages.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Back to Pages
    </a>
@endsection

@push('styles')
<style>
    .page-edit-card {
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .nav-tabs .nav-link {
        border-radius: 8px 8px 0 0;
        margin-right: 4px;
        transition: all 0.3s;
    }
    .nav-tabs .nav-link:hover {
        background-color: #f8f9fa;
    }
    .nav-tabs .nav-link.active {
        background-color: #007bff;
        color: white;
        border-color: #007bff;
    }
    .language-pill {
        border-radius: 20px;
        padding: 6px 16px;
        font-weight: 500;
        transition: all 0.3s;
    }
    .language-pill.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }
    .form-section {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .form-section-title {
        font-size: 18px;
        font-weight: 600;
        color: #495057;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #dee2e6;
    }
    .btn-save {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        padding: 12px 40px;
        font-weight: 600;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        transition: all 0.3s;
    }
    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
    }
</style>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card page-edit-card">
                <div class="card-header bg-white">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-file-alt text-primary mr-2"></i>
                        Edit Page Content
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.pages.update', $page->slug) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Main Tabs -->
                        <ul class="nav nav-tabs mb-4" id="mainTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab">
                                    <i class="fas fa-cog mr-2"></i>General
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="content-tab" data-toggle="tab" href="#content" role="tab">
                                    <i class="fas fa-language mr-2"></i>Content (Multi-language)
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="seo-tab" data-toggle="tab" href="#seo" role="tab">
                                    <i class="fas fa-search mr-2"></i>SEO Settings
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content" id="mainTabsContent">
                            <!-- General Tab -->
                            <div class="tab-pane fade show active" id="general" role="tabpanel">
                                <div class="form-section">
                                    <h5 class="form-section-title">
                                        <i class="fas fa-info-circle mr-2"></i>Page Information
                                    </h5>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Page Name</label>
                                                <input type="text" class="form-control" value="{{ $page->name }}" disabled>
                                                <small class="form-text text-muted">This is the display name of the page</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Page Slug</label>
                                                <input type="text" class="form-control" value="{{ $page->slug }}" disabled>
                                                <small class="form-text text-muted">URL identifier for this page</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" name="is_active" class="custom-control-input" 
                                                   id="is_active" {{ $page->is_active ? 'checked' : '' }}>
                                            <label class="custom-control-label font-weight-bold" for="is_active">
                                                Active Status
                                            </label>
                                        </div>
                                        <small class="form-text text-muted">Toggle to enable/disable this page</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Content Tab (Multi-language) -->
                            <div class="tab-pane fade" id="content" role="tabpanel">
                                <!-- Language Pills -->
                                <div class="mb-4">
                                    <ul class="nav nav-pills" id="languageTabs" role="tablist">
                                        @foreach($activeLanguages as $lang)
                                            <li class="nav-item mr-2 mb-2">
                                                <a class="nav-link language-pill @if($loop->first) active @endif" 
                                                   id="lang-{{ $lang->code }}-tab" 
                                                   data-toggle="pill" 
                                                   href="#lang-{{ $lang->code }}" 
                                                   role="tab">
                                                    <i class="fas fa-globe mr-1"></i>{{ $lang->name }} ({{ strtoupper($lang->code) }})
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                <!-- Language Content -->
                                <div class="tab-content" id="languageTabsContent">
                                    @foreach($activeLanguages as $lang)
                                        @php
                                            $translation = $page->translations->where('locale', $lang->code)->first();
                                        @endphp
                                        <div class="tab-pane fade @if($loop->first) show active @endif" 
                                             id="lang-{{ $lang->code }}" 
                                             role="tabpanel">
                                            
                                            <div class="form-section">
                                                <h5 class="form-section-title">
                                                    <i class="fas fa-heading mr-2"></i>Page Content ({{ $lang->name }})
                                                </h5>

                                                <div class="form-group">
                                                    <label for="title_{{ $lang->code }}" class="font-weight-bold">
                                                        Title <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" 
                                                           name="title[{{ $lang->code }}]" 
                                                           id="title_{{ $lang->code }}"
                                                           class="form-control form-control-lg @error('title.' . $lang->code) is-invalid @enderror"
                                                           value="{{ old('title.' . $lang->code, $translation->title ?? '') }}"
                                                           placeholder="Enter page title">
                                                    @error('title.' . $lang->code)
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="form-group">
                                                    <label for="description_{{ $lang->code }}" class="font-weight-bold">
                                                        Description
                                                    </label>
                                                    <textarea name="description[{{ $lang->code }}]" 
                                                              id="description_{{ $lang->code }}"
                                                              class="form-control @error('description.' . $lang->code) is-invalid @enderror"
                                                              rows="5"
                                                              placeholder="Enter main description">{{ old('description.' . $lang->code, $translation->description ?? '') }}</textarea>
                                                    @error('description.' . $lang->code)
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="form-group">
                                                    <label for="sub_description_{{ $lang->code }}" class="font-weight-bold">
                                                        Sub Description
                                                    </label>
                                                    <textarea name="sub_description[{{ $lang->code }}]" 
                                                              id="sub_description_{{ $lang->code }}"
                                                              class="form-control @error('sub_description.' . $lang->code) is-invalid @enderror"
                                                              rows="4"
                                                              placeholder="Enter sub description">{{ old('sub_description.' . $lang->code, $translation->sub_description ?? '') }}</textarea>
                                                    @error('sub_description.' . $lang->code)
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- SEO Tab -->
                            <div class="tab-pane fade" id="seo" role="tabpanel">
                                <!-- SEO Language Pills -->
                                <div class="mb-4">
                                    <ul class="nav nav-pills" id="seoTabs" role="tablist">
                                        @foreach($activeLanguages as $lang)
                                            <li class="nav-item mr-2 mb-2">
                                                <a class="nav-link language-pill @if($loop->first) active @endif" 
                                                   id="seo-{{ $lang->code }}-tab" 
                                                   data-toggle="pill" 
                                                   href="#seo-{{ $lang->code }}" 
                                                   role="tab">
                                                    <i class="fas fa-globe mr-1"></i>{{ $lang->name }} ({{ strtoupper($lang->code) }})
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                <!-- SEO Content -->
                                <div class="tab-content" id="seoTabsContent">
                                    @foreach($activeLanguages as $lang)
                                        @php
                                            $translation = $page->translations->where('locale', $lang->code)->first();
                                        @endphp
                                        <div class="tab-pane fade @if($loop->first) show active @endif" 
                                             id="seo-{{ $lang->code }}" 
                                             role="tabpanel">
                                            
                                            <div class="form-section">
                                                <h5 class="form-section-title">
                                                    <i class="fas fa-search mr-2"></i>SEO Settings ({{ $lang->name }})
                                                </h5>

                                                <div class="form-group">
                                                    <label for="meta_title_{{ $lang->code }}" class="font-weight-bold">
                                                        Meta Title
                                                    </label>
                                                    <input type="text" 
                                                           name="meta_title[{{ $lang->code }}]" 
                                                           id="meta_title_{{ $lang->code }}"
                                                           class="form-control"
                                                           value="{{ old('meta_title.' . $lang->code, $translation->meta_title ?? '') }}"
                                                           placeholder="Enter meta title for SEO">
                                                </div>

                                                <div class="form-group">
                                                    <label for="meta_description_{{ $lang->code }}" class="font-weight-bold">
                                                        Meta Description
                                                    </label>
                                                    <textarea name="meta_description[{{ $lang->code }}]" 
                                                              id="meta_description_{{ $lang->code }}"
                                                              class="form-control"
                                                              rows="3"
                                                              placeholder="Enter meta description for SEO">{{ old('meta_description.' . $lang->code, $translation->meta_description ?? '') }}</textarea>
                                                </div>

                                                <div class="form-group">
                                                    <label for="meta_keywords_{{ $lang->code }}" class="font-weight-bold">
                                                        Meta Keywords
                                                    </label>
                                                    <input type="text" 
                                                           name="meta_keywords[{{ $lang->code }}]" 
                                                           id="meta_keywords_{{ $lang->code }}"
                                                           class="form-control"
                                                           value="{{ old('meta_keywords.' . $lang->code, $translation->meta_keywords ?? '') }}"
                                                           placeholder="Enter keywords separated by commas">
                                                    <small class="form-text text-muted">Separate keywords with commas</small>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="text-center mt-4 pt-3 border-top">
                            <button type="submit" class="btn btn-save btn-lg text-white">
                                <i class="fas fa-save mr-2"></i>Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

