@php
    use Illuminate\Support\Str;

    $normalizedLocale = Str::of((string) app()->getLocale())->replace('_', '-')->lower()->value();
    $isRtlLocale = Str::startsWith($normalizedLocale, 'ar');

    $scriptAsset = static function (string $ltrPath, ?string $rtlPath = null) use ($isRtlLocale): string {
        $preferredPath = $isRtlLocale ? ($rtlPath ?? $ltrPath) : $ltrPath;

        if (file_exists(public_path($preferredPath))) {
            return asset($preferredPath);
        }

        if ($preferredPath !== $ltrPath && file_exists(public_path($ltrPath))) {
            return asset($ltrPath);
        }

        return asset($preferredPath);
    };
@endphp

<!-- scrollToTop start -->
<div class="progress-wrap active-progress">
    <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
    <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" style="transition: stroke-dashoffset 10ms linear 0s; stroke-dasharray: 307.919px, 307.919px; stroke-dashoffset: 228.265px;"></path>
    </svg>
</div>
<!-- scrollToTop end -->

<!-- jQuery -->
<script src="{{ $scriptAsset('website/assets/js/jquery-3.7.1.min.js', 'website/rtl/assets/js/jquery-3.7.1.min.js') }}"></script>

<!-- Bootstrap Core JS -->
<script src="{{ $scriptAsset('website/assets/js/bootstrap.bundle.min.js', 'website/rtl/assets/js/bootstrap.bundle.min.js') }}"></script>

<!-- counterup JS -->
<script src="{{ $scriptAsset('website/assets/js/jquery.waypoints.js', 'website/rtl/assets/js/jquery.waypoints.js') }}"></script>
<script src="{{ $scriptAsset('website/assets/js/jquery.counterup.min.js', 'website/rtl/assets/js/jquery.counterup.min.js') }}"></script>

<!-- Select2 JS -->
<script src="{{ $scriptAsset('website/assets/plugins/select2/js/select2.min.js', 'website/rtl/assets/plugins/select2/js/select2.min.js') }}"></script>

<!-- Aos -->
<script src="{{ $scriptAsset('website/assets/plugins/aos/aos.js', 'website/rtl/assets/plugins/aos/aos.js') }}"></script>

<!-- Top JS -->
<script src="{{ $scriptAsset('website/assets/js/backToTop.js', 'website/rtl/assets/js/backToTop.js') }}"></script>

<!-- Owl Carousel JS -->
<script src="{{ $scriptAsset('website/assets/js/owl.carousel.min.js', 'website/rtl/assets/js/owl.carousel.min.js') }}"></script>

<!-- Slick JS -->
<script src="{{ $scriptAsset('website/assets/plugins/slick/slick.js', 'website/rtl/assets/plugins/slick/slick.js') }}"></script>

<!-- Flatpickr JS -->
<script src="{{ $scriptAsset('website/assets/plugins/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ $scriptAsset('website/assets/plugins/flatpickr/forms-pickers.js') }}"></script>

<!-- Datepicker Core JS -->
<script src="{{ $scriptAsset('website/assets/plugins/moment/moment.min.js', 'website/rtl/assets/plugins/moment/moment.min.js') }}"></script>
<script src="{{ $scriptAsset('website/assets/js/bootstrap-datetimepicker.min.js', 'website/rtl/assets/js/bootstrap-datetimepicker.min.js') }}"></script>

<!-- Fancybox JS -->
<script src="{{ $scriptAsset('website/assets/plugins/fancybox/fancybox.umd.js', 'website/rtl/assets/plugins/fancybox/fancybox.umd.js') }}"></script>

<!-- Theia Sticky Sidebar JS -->
<script src="{{ $scriptAsset('website/assets/plugins/theia-sticky-sidebar/theia-sticky-sidebar.min.js', 'website/rtl/assets/plugins/theia-sticky-sidebar/theia-sticky-sidebar.min.js') }}"></script>

<!-- Custom JS -->
<script src="{{ $scriptAsset('website/assets/js/script.js?v=0.4', 'website/rtl/assets/js/script.js?v=0.4') }}"></script>
@stack('js')
