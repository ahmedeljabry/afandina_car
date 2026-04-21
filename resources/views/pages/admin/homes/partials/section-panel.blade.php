<div class="home-editor-section">
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start gap-3 mb-4">
        <div>
            <span class="home-section-kicker">{{ __('Content Block') }}</span>
            <h4 class="mb-1">{{ $section['title'] }}</h4>
            <p class="text-muted mb-0">{{ $section['description'] }}</p>
        </div>
        <span class="home-badge">{{ count($homeLocales) }} {{ __('Locales') }}</span>
    </div>

    <ul class="nav nav-pills home-locale-nav mb-4" role="tablist">
        @foreach ($homeLocales as $locale)
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                    type="button"
                    data-bs-toggle="pill"
                    data-bs-target="#{{ $section['anchor'] }}-{{ $locale['code'] }}"
                    role="tab"
                    aria-controls="{{ $section['anchor'] }}-{{ $locale['code'] }}"
                    aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                    {{ $locale['name'] }}
                </button>
            </li>
        @endforeach
    </ul>

    @if (!empty($section['media_fields']))
        <div class="row g-3 mb-4">
            @foreach ($section['media_fields'] as $mediaField)
                @php
                    $fieldName = $mediaField['name'];
                    $currentPath = data_get($item, $fieldName);
                    $previewUrl = filled($currentPath)
                        ? asset('storage/' . ltrim($currentPath, '/'))
                        : asset($mediaField['fallback_asset']);
                    $previewId = $fieldName . '_preview';
                    $filenameId = $fieldName . '_filename';
                @endphp

                <div class="col-lg-6">
                    <div class="home-upload-card h-100">
                        <h5>{{ __($mediaField['label']) }}</h5>
                        <p>{{ __($mediaField['description']) }}</p>
                        <img id="{{ $previewId }}" src="{{ $previewUrl }}" alt="{{ __($mediaField['label']) }}" class="home-media-preview">
                        <label class="home-upload-dropzone" for="{{ $fieldName }}">
                            <strong>{{ __('Choose Image') }}</strong>
                            <small>{{ __('JPG, PNG, SVG, WEBP up to 10 MB') }}</small>
                        </label>
                        <input type="file"
                            name="{{ $fieldName }}"
                            id="{{ $fieldName }}"
                            class="d-none home-upload-input @error($fieldName) is-invalid @enderror"
                            accept="image/*"
                            data-preview-target="{{ $previewId }}"
                            data-file-label="{{ $filenameId }}">
                        <div class="home-upload-filename" id="{{ $filenameId }}">{{ __('No new image selected') }}</div>
                        @error($fieldName)
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="tab-content">
        @foreach ($homeLocales as $locale)
            @php
                $localeCode = $locale['code'];
                $translation = $translationsByLocale[$localeCode] ?? null;
            @endphp

            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                id="{{ $section['anchor'] }}-{{ $localeCode }}"
                role="tabpanel">
                <div class="row g-3">
                    @foreach ($section['fields'] as $field)
                        @php
                            $fieldName = $field['name'];
                            $fieldType = $field['type'] ?? 'text';
                            $fieldRows = $field['rows'] ?? 3;
                            $fieldWidth = $field['width'] ?? 'col-xl-6';
                            $fieldValue = old($fieldName . '.' . $localeCode, data_get($translation, $fieldName));

                            if (!filled($fieldValue)) {
                                $fieldValue = $prefillValues[$fieldName][$localeCode] ?? '';
                            }
                        @endphp

                        <div class="{{ $fieldWidth }}">
                            <div class="home-field-card h-100">
                                <label for="{{ $fieldName }}_{{ $localeCode }}" class="font-weight-bold mb-2">
                                    {{ $field['label'] }}
                                </label>

                                @if ($fieldType === 'textarea')
                                    <textarea
                                        name="{{ $fieldName }}[{{ $localeCode }}]"
                                        id="{{ $fieldName }}_{{ $localeCode }}"
                                        class="form-control home-field-input @error($fieldName . '.' . $localeCode) is-invalid @enderror"
                                        rows="{{ $fieldRows }}"
                                    >{{ $fieldValue }}</textarea>
                                @else
                                    <input
                                        type="text"
                                        name="{{ $fieldName }}[{{ $localeCode }}]"
                                        id="{{ $fieldName }}_{{ $localeCode }}"
                                        class="form-control home-field-input @error($fieldName . '.' . $localeCode) is-invalid @enderror"
                                        value="{{ $fieldValue }}"
                                    >
                                @endif

                                @error($fieldName . '.' . $localeCode)
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
