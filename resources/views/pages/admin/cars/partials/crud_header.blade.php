@php
    $activeStep = $activeStep ?? 'details';
    $steps = [
        ['key' => 'list', 'icon' => 'icon-list', 'label' => __('Browse Cars')],
        ['key' => 'details', 'icon' => 'icon-info', 'label' => __('General Details')],
        ['key' => 'media', 'icon' => 'icon-picture', 'label' => __('Media & Gallery')],
        ['key' => 'seo', 'icon' => 'icon-magnifier', 'label' => __('SEO & Content')],
        ['key' => 'publish', 'icon' => 'icon-check', 'label' => __('Publish')],
    ];
@endphp

<div class="row crud-overview mb-2">
    <div class="col-lg-12 col-md-12 mb-2">
        <div class="card crud-wizard h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <div>
                        <h5 class="mb-0">{{ __('Car Management Flow') }}</h5>
                        <small class="text-muted">{{ __('Follow the steps to complete the operation') }}</small>
                    </div>
                    <span class="badge badge-light-primary text-uppercase">{{ __('Cars CRUD') }}</span>
                </div>
                <div class="crud-steps mt-1">
                    @foreach($steps as $step)
                        <div class="crud-step {{ $activeStep === $step['key'] ? 'active' : '' }}">
                            <span class="step-icon"><i class="{{ $step['icon'] }}"></i></span>
                            <span class="step-label">{{ $step['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
