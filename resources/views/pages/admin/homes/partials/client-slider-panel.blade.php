<div class="home-editor-section">
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start gap-3 mb-4">
        <div>
            <span class="home-section-kicker">{{ __('Homepage Slider') }}</span>
            <h4 class="mb-1">{{ __('Client Slider Logos') }}</h4>
            <p class="text-muted mb-0">{{ __('Control the logos shown in the homepage client-slider carousel below the testimonial cards.') }}</p>
        </div>
        <span class="home-badge">{{ count($clientSliderItems) }} {{ __('Slots') }}</span>
    </div>

    <div class="home-client-slider-grid">
        @foreach ($clientSliderItems as $index => $clientItem)
            @php
                $fieldPrefix = 'client_slider_items.' . $index;
                $path = old($fieldPrefix . '.path', $clientItem['path'] ?? '');
                $alt = old($fieldPrefix . '.alt', $clientItem['alt'] ?? '');
                $url = old($fieldPrefix . '.url', $clientItem['url'] ?? '');
                $isActive = old($fieldPrefix . '.is_active', ($clientItem['is_active'] ?? true) ? '1' : null);
                $previewId = 'client_slider_logo_preview_' . $index;
                $emptyId = 'client_slider_logo_empty_' . $index;
                $filenameId = 'client_slider_logo_filename_' . $index;
                $previewUrl = null;

                if (filled($path)) {
                    $previewUrl = \Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])
                        ? $path
                        : (\Illuminate\Support\Str::startsWith($path, ['website/', 'admin/'])
                            ? asset($path)
                            : asset('storage/' . ltrim($path, '/')));
                }
            @endphp

            <div class="home-client-slot">
                <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
                    <h5 class="mb-0">{{ __('Logo Slot :number', ['number' => $index + 1]) }}</h5>
                    <div class="form-check form-switch mb-0">
                        <input type="checkbox"
                            name="client_slider_items[{{ $index }}][is_active]"
                            id="client_slider_active_{{ $index }}"
                            class="form-check-input"
                            value="1"
                            @checked((bool) $isActive)>
                        <label class="form-check-label" for="client_slider_active_{{ $index }}">{{ __('Active') }}</label>
                    </div>
                </div>

                @if ($previewUrl)
                    <img id="{{ $previewId }}" src="{{ $previewUrl }}" alt="{{ $alt ?: __('Client logo preview') }}" class="home-client-logo-preview">
                    <div id="{{ $emptyId }}" class="home-client-logo-empty d-none">{{ __('No logo selected') }}</div>
                @else
                    <img id="{{ $previewId }}" src="" alt="{{ __('Client logo preview') }}" class="home-client-logo-preview d-none">
                    <div id="{{ $emptyId }}" class="home-client-logo-empty">{{ __('No logo selected') }}</div>
                @endif

                <input type="hidden" name="client_slider_items[{{ $index }}][path]" value="{{ $path }}">

                <label class="home-upload-dropzone" for="client_slider_image_{{ $index }}">
                    <strong>{{ __('Choose Logo') }}</strong>
                    <small>{{ __('SVG, JPG, PNG, WEBP up to 10 MB') }}</small>
                </label>
                <input type="file"
                    name="client_slider_images[{{ $index }}]"
                    id="client_slider_image_{{ $index }}"
                    class="d-none home-upload-input @error('client_slider_images.' . $index) is-invalid @enderror"
                    accept="image/*"
                    data-preview-target="{{ $previewId }}"
                    data-empty-target="{{ $emptyId }}"
                    data-file-label="{{ $filenameId }}">
                <div class="home-upload-filename" id="{{ $filenameId }}">{{ __('No new logo selected') }}</div>
                @error('client_slider_images.' . $index)
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror

                <div class="row g-3 mt-1">
                    <div class="col-lg-6">
                        <label for="client_slider_alt_{{ $index }}" class="font-weight-bold mb-2">{{ __('Alt Text') }}</label>
                        <input type="text"
                            name="client_slider_items[{{ $index }}][alt]"
                            id="client_slider_alt_{{ $index }}"
                            class="form-control home-field-input @error('client_slider_items.' . $index . '.alt') is-invalid @enderror"
                            value="{{ $alt }}"
                            placeholder="{{ __('Client logo') }}">
                        @error('client_slider_items.' . $index . '.alt')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-lg-6">
                        <label for="client_slider_url_{{ $index }}" class="font-weight-bold mb-2">{{ __('Optional Link') }}</label>
                        <input type="text"
                            name="client_slider_items[{{ $index }}][url]"
                            id="client_slider_url_{{ $index }}"
                            class="form-control home-field-input @error('client_slider_items.' . $index . '.url') is-invalid @enderror"
                            value="{{ $url }}"
                            placeholder="https://example.com">
                        @error('client_slider_items.' . $index . '.url')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-check mt-3">
                    <input type="checkbox"
                        name="client_slider_items[{{ $index }}][remove_image]"
                        id="client_slider_remove_{{ $index }}"
                        class="form-check-input"
                        value="1">
                    <label class="form-check-label text-muted" for="client_slider_remove_{{ $index }}">{{ __('Clear this logo on save') }}</label>
                </div>
            </div>
        @endforeach
    </div>
</div>
