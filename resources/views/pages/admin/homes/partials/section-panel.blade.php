<div class="card card-primary card-outline shadow-sm mb-4" id="{{ $section['anchor'] }}">
    <div class="card-header bg-white">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
            <div>
                <h5 class="mb-1">{{ $section['title'] }}</h5>
                <p class="text-muted mb-0">{{ $section['description'] }}</p>
            </div>
            <span class="badge badge-light border text-dark">{{ count($homeLocales) }} Languages</span>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            @foreach ($homeLocales as $locale)
                @php
                    $localeCode = $locale['code'];
                    $translation = $translationsByLocale[$localeCode] ?? null;
                @endphp

                <div class="col-lg-6 d-flex">
                    <div class="border rounded p-3 mb-3 flex-fill">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="mb-0">{{ $locale['name'] }}</h6>
                            <span class="badge badge-secondary">{{ strtoupper($localeCode) }}</span>
                        </div>

                        <div class="row">
                            @foreach ($section['fields'] as $field)
                                @php
                                    $fieldName = $field['name'];
                                    $fieldType = $field['type'] ?? 'text';
                                    $fieldRows = $field['rows'] ?? 3;
                                    $fieldWidth = $field['width'] ?? 'col-12';
                                    $fieldValue = old($fieldName . '.' . $localeCode, data_get($translation, $fieldName));

                                    if (!filled($fieldValue)) {
                                        $fieldValue = $prefillValues[$fieldName][$localeCode] ?? '';
                                    }
                                @endphp

                                <div class="{{ $fieldWidth }}">
                                    <div class="form-group">
                                        <label for="{{ $fieldName }}_{{ $localeCode }}" class="font-weight-bold">
                                            {{ $field['label'] }}
                                        </label>

                                        @if ($fieldType === 'textarea')
                                            <textarea
                                                name="{{ $fieldName }}[{{ $localeCode }}]"
                                                id="{{ $fieldName }}_{{ $localeCode }}"
                                                class="form-control @error($fieldName . '.' . $localeCode) is-invalid @enderror"
                                                rows="{{ $fieldRows }}"
                                            >{{ $fieldValue }}</textarea>
                                        @else
                                            <input
                                                type="text"
                                                name="{{ $fieldName }}[{{ $localeCode }}]"
                                                id="{{ $fieldName }}_{{ $localeCode }}"
                                                class="form-control @error($fieldName . '.' . $localeCode) is-invalid @enderror"
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
                </div>
            @endforeach
        </div>
    </div>
</div>
