@extends('layouts.admin_layout')

@section('title', 'View ' . $modelName)

@section('page-title')
    View {{ $modelName }}
@endsection

@section('content')
                @php
                    $translations = $item->translations->keyBy('locale');
                    $seoQuestions = $item->seoQuestions->groupBy('locale');
                    $primaryTranslation = $translations->first();
                @endphp
                <!-- Back, Edit, and Delete Buttons -->
                <div class="mb-3 d-flex justify-content-between">
                    <a href="{{ route('admin.' . $modelName . '.index') }}" class="btn btn-outline-secondary" data-toggle="tooltip" data-placement="top" title="Back to list">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                    <div>
                        <a href="{{ route('admin.' . $modelName . '.edit', $item->id) }}" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Edit item">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.' . $modelName . '.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this item?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Delete item">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Main Card -->
                <div class="card card-primary card-outline shadow-lg">
                    <div class="card-header">
                        <h3 class="card-title">Details for {{ $modelName }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <img src="{{ asset('storage/' . $item->logo_path) }}" alt="Logo" class="img-fluid rounded shadow-sm" />
                            </div>
                            <div class="col-md-8">
                                <h4>{{ $primaryTranslation?->name ?? 'N/A' }}</h4>
                                <span class="badge badge-info">{{ $primaryTranslation?->slug ?? 'N/A' }}</span>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Tabs for Different Languages -->
                        <ul class="nav nav-tabs" id="language-tabs" role="tablist">
                            @foreach($activeLanguages as $lang)
                                <li class="nav-item">
                                    <a class="nav-link @if($loop->first) active @endif" id="tab-{{ $lang->code }}" data-toggle="tab" href="#content-{{ $lang->code }}" role="tab" aria-controls="content-{{ $lang->code }}" aria-selected="true">{{ $lang->name }}</a>
                                </li>
                            @endforeach
                        </ul>

                        <div class="tab-content mt-3" id="language-tabs-content">
                            @foreach($activeLanguages as $lang)
                                @php
                                    $translation = $translations->get($lang->code);
                                    $keywords = json_decode($translation?->meta_keywords ?? '[]', true);
                                    $keywords = is_array($keywords) ? $keywords : [];
                                @endphp
                                <div class="tab-pane fade @if($loop->first) show active @endif" id="content-{{ $lang->code }}" role="tabpanel" aria-labelledby="tab-{{ $lang->code }}">
                                    <div class="mb-3">
                                        <h5>Meta Data</h5>
                                        <table class="table table-bordered table-striped">
                                            <tbody>
                                            <tr>
                                                <th>Meta Title</th>
                                                <td>{{ $translation?->meta_title ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Meta Description</th>
                                                <td>{{ $translation?->meta_description ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Meta Keywords</th>
                                                <td>
                                                    @forelse($keywords as $keyword)
                                                        <span class="badge badge-pill badge-primary">{{ $keyword['value'] }}</span>
                                                    @empty
                                                        <span>N/A</span>
                                                    @endforelse
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div>
                                        <h5>SEO Questions/Answers</h5>
                                        @forelse($seoQuestions->get($lang->code, collect()) as $seoQuestion)
                                            <div class="card mb-3 shadow-sm">
                                                <div class="card-header d-flex justify-content-between align-items-center">
                                                    <h6 class="m-0 font-weight-bold">Question {{ $seoQuestion->id }}</h6>
                                                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse-{{ $seoQuestion->id }}" aria-expanded="false" aria-controls="collapse-{{ $seoQuestion->id }}">
                                                        <i class="fas fa-chevron-down"></i>
                                                    </button>
                                                </div>
                                                <div id="collapse-{{ $seoQuestion->id }}" class="collapse">
                                                    <div class="card-body">
                                                        <p><strong>Question:</strong> {{ $seoQuestion->question_text ?? 'N/A' }}</p>
                                                        <p><strong>Answer:</strong> {{ $seoQuestion->answer_text ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="alert alert-default-light">
                                                No SEO questions available for this language.
                                            </div>
                                        @endforelse
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @push('scripts')
        <!-- Initialize Tooltips -->
        <script>
            $(function () {
                $('[data-toggle="tooltip"]').tooltip()
            })
        </script>
    @endpush
@endsection
