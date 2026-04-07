@include('pages.admin.cars.partials.workspace-styles')

@php
    $isEdit = ($mode ?? 'create') === 'edit' && isset($item);
    $formId = $isEdit ? 'editCarForm' : 'createCarForm';
    $formAction = $isEdit
        ? route('admin.' . $modelName . '.update', $item->id)
        : route('admin.' . $modelName . '.store');
    $heroTitle = $isEdit ? __('Redesign This Car Listing') : __('Create A New Car Listing');
    $heroSubtitle = $isEdit
        ? __('Update the catalog details, homepage placement, translations, and SEO in one clean workspace. The structure is new, but all existing save flows stay intact.')
        : __('Build the listing from scratch with catalog setup, price matrix, homepage flags, media uploads, translations, and SEO all in one place.');
    $currentValues = $currentValues ?? [];
    $selectedCategoryIds = old('category_ids', $isEdit ? [$item->category_id] : []);
    if (!is_array($selectedCategoryIds)) {
        $selectedCategoryIds = [$selectedCategoryIds];
    }
    $selectedCategoryIds = collect($selectedCategoryIds)
        ->filter(fn ($value) => $value !== null && $value !== '')
        ->map(fn ($value) => (int) $value)
        ->unique()
        ->values()
        ->all();
    $selectedFeatureIds = old('features', $isEdit ? \App\Models\CarFeature::where('car_id', $item->id)->pluck('feature_id')->all() : []);
    if (!is_array($selectedFeatureIds)) {
        $selectedFeatureIds = [$selectedFeatureIds];
    }
    $selectedFeatureIds = collect($selectedFeatureIds)->filter()->map(fn ($value) => (int) $value)->unique()->values()->all();
    $currentStatus = old('status', $isEdit ? $item->status : 'available');
    $isActive = (bool) old('is_active', $isEdit ? $item->is_active : true);
    $displayName = $isEdit
        ? ($item->translations->where('locale', app()->getLocale())->first()?->name
            ?? $item->translations->first()?->name
            ?? __('Untitled Car'))
        : __('New Draft');
    $heroTags = array_values(array_filter([
        __('Catalog setup'),
        __('Pricing matrix'),
        $isEdit ? __('Manage live data') : __('Upload media'),
        ($activeLanguages->count() ?? 0) ? __(':count locales', ['count' => $activeLanguages->count()]) : null,
        __('SEO ready'),
    ]));
    $defaultImageUrl = $isEdit && filled($item->default_image_path)
        ? asset('storage/' . $item->default_image_path)
        : "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='900' height='520' viewBox='0 0 900 520'%3E%3Crect width='100%25' height='100%25' fill='%23e2e8f0'/%3E%3Ctext x='50%25' y='50%25' fill='%2364758b' font-size='34' text-anchor='middle' dy='.3em'%3ENo Preview Yet%3C/text%3E%3C/svg%3E";
    $featureToggleCount = collect([
        old('insurance_included', $isEdit ? $item->insurance_included : false),
        old('free_delivery', $isEdit ? $item->free_delivery : false),
        old('crypto_payment_accepted', $isEdit ? $item->crypto_payment_accepted : false),
        old('is_featured', $isEdit ? $item->is_featured : false),
        old('is_flash_sale', $isEdit ? $item->is_flash_sale : false),
        old('only_on_afandina', $isEdit ? $item->only_on_afandina : false),
    ])->filter()->count();
    $formatMetaKeywords = function ($translation, string $locale) use ($isEdit) {
        if (!$isEdit) {
            return old('meta_keywords.' . $locale, '');
        }

        $metaKeywordsJson = $translation?->meta_keywords ?? '[]';
        $metaKeywords = json_decode($metaKeywordsJson, true);
        $keywordString = '';

        if (is_array($metaKeywords) && !empty($metaKeywords)) {
            if (isset($metaKeywords[0]) && is_array($metaKeywords[0]) && isset($metaKeywords[0]['value'])) {
                $keywordString = implode(',', array_column($metaKeywords, 'value'));
            } else {
                $keywordString = implode(',', $metaKeywords);
            }
        } elseif (is_string($metaKeywordsJson) && $metaKeywordsJson !== '[]') {
            $keywordString = $metaKeywordsJson;
        }

        return old('meta_keywords.' . $locale, $keywordString);
    };
@endphp

<div class="car-studio">
    <section class="car-studio__hero">
        <div class="car-studio__hero-copy">
            <span class="car-studio__eyebrow">{{ $isEdit ? __('Live Listing Studio') : __('New Listing Studio') }}</span>
            <h2 class="car-studio__title">{{ $heroTitle }}</h2>
            <p class="car-studio__subtitle">{{ $heroSubtitle }}</p>
            <div class="car-studio__hero-tags">
                @foreach ($heroTags as $tag)
                    <span class="car-studio__hero-tag">{{ $tag }}</span>
                @endforeach
            </div>
        </div>
        <div class="car-studio__hero-metrics">
            <div class="car-studio__metric">
                <span class="car-studio__metric-label">{{ __('Listing') }}</span>
                <strong class="car-studio__metric-value">{{ $displayName }}</strong>
                <span class="car-studio__metric-note">{{ $isEdit ? __('Current record in the catalog') : __('Draft record before first save') }}</span>
            </div>
            <div class="car-studio__metric">
                <span class="car-studio__metric-label">{{ __('Categories') }}</span>
                <strong class="car-studio__metric-value">{{ count($selectedCategoryIds) ?: __('Not set') }}</strong>
                <span class="car-studio__metric-note">{{ __('Primary taxonomy placement') }}</span>
            </div>
            <div class="car-studio__metric">
                <span class="car-studio__metric-label">{{ __('Homepage Flags') }}</span>
                <strong class="car-studio__metric-value">{{ $featureToggleCount }}</strong>
                <span class="car-studio__metric-note">{{ __('Insurance, delivery, featured, flash, and more') }}</span>
            </div>
            <div class="car-studio__metric">
                <span class="car-studio__metric-label">{{ __('Visibility') }}</span>
                <strong class="car-studio__metric-value">{{ $isActive ? __('Active') : __('Inactive') }}</strong>
                <span class="car-studio__metric-note">{{ $currentStatus === 'available' ? __('Available for booking') : __('Hidden from booking flow') }}</span>
            </div>
        </div>
    </section>

    @if ($errors->any())
        <div class="studio-alert">
            <h4>{{ __('Please review the form errors') }}</h4>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="car-studio__main">
            <div class="studio-main-loader" id="loader-overlay">
                <div class="studio-main-loader__spinner"></div>
                <div class="studio-main-loader__text">{{ __('Saving changes and preparing the response...') }}</div>
            </div>

            <div class="studio-frame">
                <div class="studio-frame__header">
                    <div class="studio-frame__meta">
                        <div class="studio-frame__meta-copy">
                            <span class="studio-frame__eyebrow">{{ __('Publishing Workflow') }}</span>
                            <h3>{{ $isEdit ? __('Refresh the listing') : __('Compose the listing') }}</h3>
                            <p>{{ __('Move through the tabs in order or jump to the section you need. Existing backend field names are preserved.') }}</p>
                        </div>
                    </div>

                    <ul class="nav studio-tab-nav" id="custom-tabs-three-tab" role="tablist">
                        <li class="nav-item"><a class="nav-link active" id="custom-tabs-general-tab" data-bs-toggle="pill" href="#custom-tabs-general" role="tab"><i class="ti ti-car"></i>{{ __('General Data') }}</a></li>
                        <li class="nav-item"><a class="nav-link" id="custom-tabs-translated-tab" data-bs-toggle="pill" href="#custom-tabs-translated" role="tab"><i class="ti ti-language"></i>{{ __('Translated Data') }}</a></li>
                        <li class="nav-item"><a class="nav-link" id="custom-tabs-seo-tab" data-bs-toggle="pill" href="#custom-tabs-seo" role="tab"><i class="ti ti-search"></i>{{ __('SEO Data') }}</a></li>
                    </ul>
                </div>

                <form id="{{ $formId }}" action="{{ $formAction }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if ($isEdit)
                        @method('PUT')
                    @endif

                    <div class="tab-content" id="custom-tabs-three-tabContent">
                        <div class="tab-pane fade show active studio-tab-pane" id="custom-tabs-general" role="tabpanel">
                            <div class="studio-panel">
                                <div class="studio-panel__header">
                                    <div>
                                        <span class="studio-panel__eyebrow">{{ __('Catalog') }}</span>
                                        <h3 class="studio-panel__title">{{ __('Identity & Classification') }}</h3>
                                        <p class="studio-panel__description">{{ __('Set the brand, model, categories, transmission, color, year, and booking status first.') }}</p>
                                    </div>
                                </div>
                                <div class="studio-panel__body">
                                    <div class="studio-grid">
                                        <div class="studio-field studio-span-6">
                                            <label for="brand_id" class="studio-label">{{ __('Brand') }}</label>
                                            <select name="brand_id" id="brand_id" class="form-control select2 studio-select @error('brand_id') is-invalid @enderror">
                                                <option value="">{{ __('-- Select Brand --') }}</option>
                                                @foreach ($brands as $brand)
                                                    <option value="{{ $brand->id }}" {{ old('brand_id', $isEdit ? $item->brand_id : null) == $brand->id ? 'selected' : '' }}>
                                                        {{ $brand->translations()->first()->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('brand_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="studio-field studio-span-6">
                                            <label for="car_model_id" class="studio-label">{{ __('Model') }}</label>
                                            <select name="car_model_id" id="car_model_id" data-selected="{{ old('car_model_id', $isEdit ? $item->car_model_id : null) }}" class="form-control select2 studio-select @error('car_model_id') is-invalid @enderror">
                                                <option value="">{{ __('-- Select Model --') }}</option>
                                            </select>
                                            @error('car_model_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="studio-field studio-span-6">
                                            <label for="category_id" class="studio-label">{{ __('Categories') }}</label>
                                            <select name="category_ids[]" id="category_id" class="form-control select2 studio-select @error('category_ids') is-invalid @enderror @error('category_ids.*') is-invalid @enderror" multiple="multiple">
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}" {{ in_array($category->id, $selectedCategoryIds, true) ? 'selected' : '' }}>
                                                        {{ $category->translations()->first()->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category_ids') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                            @error('category_ids.*') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="studio-field studio-span-3">
                                            <label for="gear_type_id" class="studio-label">{{ __('Gear Type') }}</label>
                                            <select name="gear_type_id" id="gear_type_id" class="form-control select2 studio-select @error('gear_type_id') is-invalid @enderror">
                                                <option value="">{{ __('-- Select Gear Type --') }}</option>
                                                @foreach ($gearTypes as $gearType)
                                                    <option value="{{ $gearType->id }}" {{ old('gear_type_id', $isEdit ? $item->gear_type_id : null) == $gearType->id ? 'selected' : '' }}>
                                                        {{ $gearType->translations()->first()->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('gear_type_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="studio-field studio-span-3">
                                            <label for="color_id" class="studio-label">{{ __('Color') }}</label>
                                            <select name="color_id" id="color_id" class="form-control select2 studio-select @error('color_id') is-invalid @enderror">
                                                <option value="">{{ __('-- Select Color --') }}</option>
                                                @foreach ($colors as $color)
                                                    <option value="{{ $color->id }}" {{ old('color_id', $isEdit ? $item->color_id : null) == $color->id ? 'selected' : '' }}>
                                                        {{ $color->translations()->first()->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('color_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="studio-field studio-span-3">
                                            <label for="year_id" class="studio-label">{{ __('Year') }}</label>
                                            <select name="year_id" id="year_id" class="form-control select2 studio-select @error('year_id') is-invalid @enderror">
                                                <option value="">{{ __('-- Select Year --') }}</option>
                                                @foreach ($years as $year)
                                                    <option value="{{ $year->id }}" {{ (string) old('year_id', $isEdit ? $item->year_id : null) === (string) $year->id ? 'selected' : '' }}>
                                                        {{ $year->year }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('year_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="studio-field studio-span-3">
                                            <label for="status" class="studio-label">{{ __('Car Status') }}</label>
                                            <select name="status" id="status" class="form-control studio-select @error('status') is-invalid @enderror">
                                                <option value="available" {{ $currentStatus === 'available' ? 'selected' : '' }}>{{ __('Available') }}</option>
                                                <option value="not_available" {{ $currentStatus === 'not_available' ? 'selected' : '' }}>{{ __('Not Available') }}</option>
                                            </select>
                                            @error('status') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="studio-panel">
                                <div class="studio-panel__header">
                                    <div>
                                        <span class="studio-panel__eyebrow">{{ __('Specs') }}</span>
                                        <h3 class="studio-panel__title">{{ __('Passenger & Capacity Details') }}</h3>
                                        <p class="studio-panel__description">{{ __('Capture the physical details that shoppers compare first on listing cards and details pages.') }}</p>
                                    </div>
                                </div>
                                <div class="studio-panel__body">
                                    <div class="studio-grid">
                                        <div class="studio-field studio-span-4">
                                            <label for="door_count" class="studio-label">{{ __('Number of Doors') }}</label>
                                            <input type="number" name="door_count" id="door_count" class="form-control @error('door_count') is-invalid @enderror" min="1" max="6" value="{{ old('door_count', $isEdit ? $item->door_count : null) }}">
                                            @error('door_count') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="studio-field studio-span-4">
                                            <label for="passenger_capacity" class="studio-label">{{ __('Passengers') }}</label>
                                            <input type="number" name="passenger_capacity" id="passenger_capacity" class="form-control @error('passenger_capacity') is-invalid @enderror" min="1" max="20" value="{{ old('passenger_capacity', $isEdit ? $item->passenger_capacity : null) }}">
                                            @error('passenger_capacity') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="studio-field studio-span-4">
                                            <label for="luggage_capacity" class="studio-label">{{ __('Luggage Capacity') }}</label>
                                            <input type="number" name="luggage_capacity" id="luggage_capacity" class="form-control @error('luggage_capacity') is-invalid @enderror" min="0" max="20" value="{{ old('luggage_capacity', $isEdit ? $item->luggage_capacity : null) }}">
                                            @error('luggage_capacity') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="studio-panel">
                                <div class="studio-panel__header">
                                    <div>
                                        <span class="studio-panel__eyebrow">{{ __('Pricing') }}</span>
                                        <h3 class="studio-panel__title">{{ __('Rental Price Matrix') }}</h3>
                                        <p class="studio-panel__description">{{ __('Keep daily, weekly, and monthly prices aligned. Each tier includes main price, discount price, and mileage.') }}</p>
                                    </div>
                                </div>
                                <div class="studio-panel__body">
                                    <div class="studio-price-grid">
                                        @foreach (['daily' => __('Daily'), 'weekly' => __('Weekly'), 'monthly' => __('Monthly')] as $priceKey => $priceLabel)
                                            <div class="studio-price-card">
                                                <span class="studio-price-card__label">{{ $priceLabel }}</span>
                                                <div class="studio-price-card__body">
                                                    <div class="studio-field">
                                                        <label for="{{ $priceKey }}_main_price" class="studio-label">{{ __('Main Price') }}</label>
                                                        <input type="number" step="any" name="{{ $priceKey }}_main_price" id="{{ $priceKey }}_main_price" class="form-control @error($priceKey . '_main_price') is-invalid @enderror" value="{{ old($priceKey . '_main_price', $isEdit ? data_get($item, $priceKey . '_main_price') : null) }}">
                                                        @error($priceKey . '_main_price') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                                    </div>
                                                    <div class="studio-field">
                                                        <label for="{{ $priceKey }}_discount_price" class="studio-label">{{ __('Discount Price') }}</label>
                                                        <input type="number" step="any" name="{{ $priceKey }}_discount_price" id="{{ $priceKey }}_discount_price" class="form-control @error($priceKey . '_discount_price') is-invalid @enderror" value="{{ old($priceKey . '_discount_price', $isEdit ? data_get($item, $priceKey . '_discount_price') : null) }}">
                                                        @error($priceKey . '_discount_price') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                                    </div>
                                                    <div class="studio-field">
                                                        <label for="{{ $priceKey }}_mileage_included" class="studio-label">{{ __('Mileage Included') }}</label>
                                                        <input type="number" step="any" name="{{ $priceKey }}_mileage_included" id="{{ $priceKey }}_mileage_included" class="form-control @error($priceKey . '_mileage_included') is-invalid @enderror" value="{{ old($priceKey . '_mileage_included', $isEdit ? data_get($item, $priceKey . '_mileage_included') : null) }}">
                                                        @error($priceKey . '_mileage_included') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="studio-panel">
                                <div class="studio-panel__header">
                                    <div>
                                        <span class="studio-panel__eyebrow">{{ __('Homepage') }}</span>
                                        <h3 class="studio-panel__title">{{ __('Feature Flags & Publish Settings') }}</h3>
                                        <p class="studio-panel__description">{{ __('Choose which promotional states this car should appear in across the home page and product detail experience.') }}</p>
                                    </div>
                                </div>
                                <div class="studio-panel__body">
                                    <div class="studio-switch-grid">
                                        @foreach ([
                                            'insurance_included' => [__('Insurance Included'), __('Mark the car as having insurance bundled in the offer.')],
                                            'free_delivery' => [__('Free Delivery'), __('Show the delivery benefit in cards and detail highlights.')],
                                            'crypto_payment_accepted' => [__('Crypto Payment'), __('Indicate that alternative payment methods are accepted.')],
                                            'is_featured' => [__('Featured On Home'), __('Allow the car to appear in the home featured cars section.')],
                                            'is_flash_sale' => [__('Flash Sale'), __('Highlight the car as part of limited-time promotions.')],
                                            'only_on_afandina' => [__('Only On Home Slider'), __('Use the car in the "car only" slider section on the home page.')],
                                            'is_active' => [__('Listing Active'), __('Control whether this car is publicly visible on the website.')],
                                        ] as $toggleName => [$toggleTitle, $toggleCopy])
                                            <div class="studio-switch">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" name="{{ $toggleName }}" class="custom-control-input" id="{{ $toggleName }}" {{ old($toggleName, $isEdit ? data_get($item, $toggleName) : ($toggleName === 'is_active')) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="{{ $toggleName }}">
                                                        <span class="studio-switch__title">{{ $toggleTitle }}</span>
                                                        <span class="studio-switch__copy">{{ $toggleCopy }}</span>
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="studio-grid mt-4">
                                        <div class="studio-field studio-span-12">
                                            <label for="features" class="studio-label">{{ __('Feature Tags') }}</label>
                                            <select id="features" class="form-control feature-select" name="features[]" multiple="multiple">
                                                @foreach ($features as $feature)
                                                    <option value="{{ $feature->id }}" data-icon="{{ $feature->icon->icon_class }}" {{ in_array($feature->id, $selectedFeatureIds, true) ? 'selected' : '' }}>
                                                        {{ $feature->translations->first()->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('features') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                            @error('features.*') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="studio-panel">
                                <div class="studio-panel__header">
                                    <div>
                                        <span class="studio-panel__eyebrow">{{ __('Media') }}</span>
                                        <h3 class="studio-panel__title">{{ $isEdit ? __('Image Management') : __('Default Cover & Gallery Uploads') }}</h3>
                                        <p class="studio-panel__description">
                                            {{ $isEdit
                                                ? __('Editing now uses a dedicated media manager. Review the current cover here and jump directly to the gallery screen when needed.')
                                                : __('Upload the default cover and a full gallery of images or videos before publishing the car.') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="studio-panel__body">
                                    @if ($isEdit)
                                        <div class="studio-media-grid">
                                            <div class="studio-media-card">
                                                <h4>{{ __('Current Default Image') }}</h4>
                                                <p>{{ __('This is the image used on cards, listings, and as the first website slide.') }}</p>
                                                <div class="studio-preview-shell">
                                                    <img src="{{ $defaultImageUrl }}" alt="{{ __('Default image preview') }}" class="image-rectangle-preview">
                                                </div>
                                            </div>
                                            <div class="studio-media-card">
                                                <h4>{{ __('Manage Gallery') }}</h4>
                                                <p>{{ __('Use the dedicated gallery manager for uploads, default image changes, ordering, and cleanup.') }}</p>
                                                <a href="{{ route('admin.cars.edit_images', $item->id) }}" class="studio-secondary-btn mt-4">
                                                    <i class="ti ti-photo-edit"></i>{{ __('Open Image Manager') }}
                                                </a>
                                            </div>
                                        </div>
                                    @else
                                        <div class="studio-media-grid">
                                            <div class="studio-media-card">
                                                <h4>{{ __('Default Cover') }}</h4>
                                                <p>{{ __('Upload the primary image used across cards, listing grids, and the product hero.') }}</p>
                                                <label class="studio-dropzone" for="default_image_path" data-upload-target="default_image_path">
                                                    <i class="ti ti-photo-up"></i>
                                                    <strong>{{ __('Choose Default Image') }}</strong>
                                                    <span>{{ __('JPG, PNG, GIF, SVG, and WebP files up to 10 MB.') }}</span>
                                                </label>
                                                <input type="file" name="default_image_path" id="default_image_path" class="d-none image-upload" accept="image/*">
                                                <div class="studio-upload-meta" id="default-image-name">{{ __('No image selected yet') }}</div>
                                                <div class="studio-preview-shell">
                                                    <img id="imagePreviewLogo" src="{{ $defaultImageUrl }}" alt="{{ __('Car cover preview') }}" class="image-rectangle-preview">
                                                </div>
                                                @error('default_image_path') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="studio-media-card">
                                                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                                    <div>
                                                        <h4>{{ __('Gallery Media') }}</h4>
                                                        <p>{{ __('Add listing images and videos. Files can be selected more than once and removed before saving.') }}</p>
                                                    </div>
                                                    <span class="studio-media-count" id="media-count-badge">{{ __('0 files') }}</span>
                                                </div>
                                                <label class="studio-dropzone" for="media-files" data-upload-target="media-files">
                                                    <i class="ti ti-cloud-upload"></i>
                                                    <strong>{{ __('Choose Images & Videos') }}</strong>
                                                    <span>{{ __('Images: JPG, PNG, GIF, SVG, WebP. Videos: MP4, WebM, OGG. Up to 100 MB each.') }}</span>
                                                </label>
                                                <input type="file" class="d-none" name="media[]" id="media-files" multiple accept="image/*,video/mp4,video/webm,video/ogg">
                                                <div class="studio-upload-meta" id="media-files-name">{{ __('No media selected yet') }}</div>
                                                @error('media') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                                @error('media.*') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                                <div class="studio-field mt-3">
                                                    <label for="alt" class="studio-label">{{ __('Shared Alt Text') }}</label>
                                                    <input type="text" class="form-control" id="alt" name="alt" value="{{ old('alt') }}" placeholder="{{ __('Optional alt text applied to uploaded images and videos') }}">
                                                </div>
                                                <div class="studio-preview-shell">
                                                    <div id="preview" class="preview-grid"></div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade studio-tab-pane" id="custom-tabs-translated" role="tabpanel">
                            <div class="studio-ai-banner">
                                <div class="studio-ai-banner__copy">
                                    <h4>{{ __('AI Content Assistant') }}</h4>
                                    <p>{{ __('Generate and refine translated car content without leaving the editor. Existing text in the selected language will be replaced.') }}</p>
                                </div>
                                <button type="button" class="studio-ai-btn generate-ai-all"><i class="ti ti-wand"></i>{{ __('Generate All Content') }}</button>
                            </div>

                            <ul class="nav studio-language-nav" id="pills-tab" role="tablist">
                                @foreach ($activeLanguages as $lang)
                                    <li class="nav-item">
                                        <a class="nav-link @if($loop->first) active @endif" id="pills-{{ $lang->code }}-tab" data-bs-toggle="pill" href="#pills-{{ $lang->code }}" role="tab">{{ $lang->name }}</a>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="tab-content" id="pills-tabContent">
                                @foreach ($activeLanguages as $lang)
                                    @php
                                        $translation = $isEdit ? $item->translations->where('locale', $lang->code)->first() : null;
                                        $longDescriptionName = $isEdit ? "translations[{$lang->code}][long_description]" : "long_description[{$lang->code}]";
                                        $longDescriptionValue = $isEdit
                                            ? old('translations.' . $lang->code . '.long_description', $translation->long_description ?? '')
                                            : old('long_description.' . $lang->code);
                                    @endphp
                                    <div class="tab-pane fade @if($loop->first) show active @endif" id="pills-{{ $lang->code }}" role="tabpanel">
                                        <div class="studio-language-pane">
                                            <div class="studio-language-head">
                                                <div>
                                                    <h4>{{ __('Content For :language', ['language' => $lang->name]) }}</h4>
                                                    <p>{{ __('Use a concise card name for listings, then expand into the short and long descriptions.') }}</p>
                                                </div>
                                                <button type="button" class="studio-outline-btn generate-ai-content" data-lang="{{ $lang->code }}">
                                                    <i class="ti ti-sparkles"></i>{{ __('Generate :language', ['language' => strtoupper($lang->code)]) }}
                                                </button>
                                            </div>
                                            <div class="studio-grid">
                                                <div class="studio-field studio-span-6">
                                                    <label for="name_{{ $lang->code }}" class="studio-label">{{ __('Name') }} ({{ $lang->name }})</label>
                                                    <input type="text" name="name[{{ $lang->code }}]" id="name_{{ $lang->code }}" class="form-control @error('name.' . $lang->code) is-invalid @enderror" value="{{ old('name.' . $lang->code, $translation->name ?? '') }}">
                                                    @error('name.' . $lang->code) <span class="invalid-feedback">{{ $message }}</span> @enderror
                                                </div>
                                                <div class="studio-field studio-span-6">
                                                    <label for="card_name_{{ $lang->code }}" class="studio-label">{{ __('Card Name') }} ({{ $lang->name }})</label>
                                                    <input type="text" name="card_name[{{ $lang->code }}]" id="card_name_{{ $lang->code }}" class="form-control @error('card_name.' . $lang->code) is-invalid @enderror" value="{{ old('card_name.' . $lang->code, $translation->card_name ?? '') }}" placeholder="{{ __('Optional short name for cards') }}">
                                                    @error('card_name.' . $lang->code) <span class="invalid-feedback">{{ $message }}</span> @enderror
                                                </div>
                                                <div class="studio-field studio-span-12">
                                                    <label for="description_{{ $lang->code }}" class="studio-label">{{ __('Description') }} ({{ $lang->name }})</label>
                                                    <textarea name="description[{{ $lang->code }}]" id="description_{{ $lang->code }}" class="form-control @error('description.' . $lang->code) is-invalid @enderror" rows="4">{{ old('description.' . $lang->code, $translation->description ?? '') }}</textarea>
                                                    @error('description.' . $lang->code) <span class="invalid-feedback">{{ $message }}</span> @enderror
                                                </div>
                                                <div class="studio-field studio-span-12">
                                                    <label for="long_description_{{ $lang->code }}" class="studio-label">{{ __('Long Description') }} ({{ $lang->name }})</label>
                                                    <textarea name="{{ $longDescriptionName }}" id="long_description_{{ $lang->code }}" class="form-control tinymce @error('long_description.' . $lang->code) is-invalid @enderror @error('translations.' . $lang->code . '.long_description') is-invalid @enderror" rows="7">{{ $longDescriptionValue }}</textarea>
                                                    @error('long_description.' . $lang->code) <span class="invalid-feedback">{{ $message }}</span> @enderror
                                                    @error('translations.' . $lang->code . '.long_description') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="tab-pane fade studio-tab-pane" id="custom-tabs-seo" role="tabpanel">
                            <ul class="nav studio-language-nav" id="pills-seo-tab" role="tablist">
                                @foreach ($activeLanguages as $lang)
                                    <li class="nav-item">
                                        <a class="nav-link @if($loop->first) active @endif" id="pills-seo-{{ $lang->code }}-tab" data-bs-toggle="pill" href="#pills-seo-{{ $lang->code }}" role="tab">{{ $lang->name }}</a>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="tab-content" id="pills-seo-tabContent">
                                @foreach ($activeLanguages as $lang)
                                    @php
                                        $translation = $isEdit ? $item->translations->where('locale', $lang->code)->first() : null;
                                        $seoQuestions = old('seo_questions.' . $lang->code, $isEdit
                                            ? $item->seoQuestions->where('locale', $lang->code)->map(fn ($seoQuestion) => [
                                                'question' => $seoQuestion->question_text,
                                                'answer' => $seoQuestion->answer_text,
                                            ])->values()->all()
                                            : [['question' => '', 'answer' => '']]);
                                        if (empty($seoQuestions)) {
                                            $seoQuestions = [['question' => '', 'answer' => '']];
                                        }
                                    @endphp
                                    <div class="tab-pane fade @if($loop->first) show active @endif" id="pills-seo-{{ $lang->code }}" role="tabpanel">
                                        <div class="studio-seo-pane">
                                            <div class="studio-grid">
                                                <div class="studio-field studio-span-12">
                                                    <label for="meta_title_{{ $lang->code }}" class="studio-label">{{ __('Meta Title') }} ({{ $lang->name }})</label>
                                                    <input type="text" name="meta_title[{{ $lang->code }}]" id="meta_title_{{ $lang->code }}" class="form-control @error('meta_title.' . $lang->code) is-invalid @enderror" value="{{ old('meta_title.' . $lang->code, $translation->meta_title ?? '') }}">
                                                    @error('meta_title.' . $lang->code) <span class="invalid-feedback">{{ $message }}</span> @enderror
                                                </div>
                                                <div class="studio-field studio-span-12">
                                                    <label for="meta_description_{{ $lang->code }}" class="studio-label">{{ __('Meta Description') }} ({{ $lang->name }})</label>
                                                    <textarea name="meta_description[{{ $lang->code }}]" id="meta_description_{{ $lang->code }}" class="form-control @error('meta_description.' . $lang->code) is-invalid @enderror" rows="3">{{ old('meta_description.' . $lang->code, $translation->meta_description ?? '') }}</textarea>
                                                    @error('meta_description.' . $lang->code) <span class="invalid-feedback">{{ $message }}</span> @enderror
                                                </div>
                                                <div class="studio-field studio-span-12">
                                                    <label for="meta_keywords_{{ $lang->code }}" class="studio-label">{{ __('Meta Keywords') }} ({{ $lang->name }})</label>
                                                    <input type="text" name="meta_keywords[{{ $lang->code }}]" id="meta_keywords_{{ $lang->code }}" class="form-control @error('meta_keywords.' . $lang->code) is-invalid @enderror" value="{{ $formatMetaKeywords($translation, $lang->code) }}" placeholder="{{ __('Enter meta keywords') }}">
                                                    @error('meta_keywords.' . $lang->code) <span class="invalid-feedback">{{ $message }}</span> @enderror
                                                </div>
                                            </div>

                                            <div class="studio-robots">
                                                <div class="studio-switch">
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" name="robots_index[{{ $lang->code }}]" class="custom-control-input" id="robots_index_{{ $lang->code }}" value="index" {{ old('robots_index.' . $lang->code, $translation->robots_index ?? data_get($currentValues, 'robots_index.' . $lang->code)) === 'index' ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="robots_index_{{ $lang->code }}">
                                                            <span class="studio-switch__title">{{ __('Robot Index') }}</span>
                                                            <span class="studio-switch__copy">{{ __('Allow search engines to index the :language version.', ['language' => $lang->name]) }}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="studio-switch">
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" name="robots_follow[{{ $lang->code }}]" class="custom-control-input" id="robots_follow_{{ $lang->code }}" value="follow" {{ old('robots_follow.' . $lang->code, $translation->robots_follow ?? data_get($currentValues, 'robots_follow.' . $lang->code)) === 'follow' ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="robots_follow_{{ $lang->code }}">
                                                            <span class="studio-switch__title">{{ __('Robot Follow') }}</span>
                                                            <span class="studio-switch__copy">{{ __('Allow search engines to follow links from the :language version.', ['language' => $lang->name]) }}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="studio-faq-stack">
                                                <label class="studio-label">{{ __('SEO Questions & Answers') }} ({{ $lang->name }})</label>
                                                <div class="seo-questions-container" id="seo-questions-{{ $lang->code }}">
                                                    @foreach ($seoQuestions as $index => $seoQuestion)
                                                        <div class="seo-question-group mb-3">
                                                            <div class="studio-field mb-3">
                                                                <label class="studio-label">{{ __('Question') }}</label>
                                                                <input type="text" name="seo_questions[{{ $lang->code }}][{{ $index }}][question]" class="form-control" value="{{ is_array($seoQuestion) ? ($seoQuestion['question'] ?? '') : '' }}" placeholder="{{ __('Enter Question') }}">
                                                            </div>
                                                            <div class="studio-field">
                                                                <label class="studio-label">{{ __('Answer') }}</label>
                                                                <textarea name="seo_questions[{{ $lang->code }}][{{ $index }}][answer]" class="form-control" rows="3" placeholder="{{ __('Enter Answer') }}">{{ is_array($seoQuestion) ? ($seoQuestion['answer'] ?? '') : '' }}</textarea>
                                                            </div>
                                                            <button type="button" class="btn btn-sm btn-danger mt-3 remove-question">{{ __('Remove') }}</button>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <button type="button" class="studio-outline-btn add-question mt-2" data-lang="{{ $lang->code }}">
                                                    <i class="ti ti-plus"></i>{{ __('Add Question') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="studio-submitbar">
                        <p class="studio-submitbar__copy">
                            {{ $isEdit
                                ? __('Saving updates keeps the existing AJAX flow. On success, you will be redirected back to the car index.')
                                : __('Creating the listing will upload the selected media, store translations, and return you to the cars index on success.') }}
                        </p>
                        <button type="submit" class="studio-primary-btn">
                            <i class="ti ti-device-floppy"></i>{{ $isEdit ? __('Save Changes') : __('Create Car') }}
                        </button>
                    </div>
                </form>
            </div>
    </div>
</div>

@include('pages.admin.cars.partials.workspace-scripts', ['mode' => $mode])
