@php
    $title = $title ?? '';
    $description = $description ?? '';
    $stats = $stats ?? [];
@endphp

<div class="form-hero">
    <div class="flex-grow-1">
        <h2 class="mb-1">{{ $title }}</h2>
        @if($description)
            <p class="mb-0">{{ $description }}</p>
        @endif
    </div>
    @if(!empty($stats))
        <div class="d-flex flex-wrap mt-3 mt-md-0 justify-content-start justify-content-md-end">
            @foreach($stats as $stat)
                @continue(empty($stat['label']))
                <span class="hero-pill">
                    @if(!empty($stat['icon']))
                        <i class="{{ $stat['icon'] }}"></i>
                    @endif
                    {{ $stat['label'] }}
                </span>
            @endforeach
        </div>
    @endif
</div>
