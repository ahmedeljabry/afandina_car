<!-- jQuery -->
<script src="{{ asset('admin/assets/js/jquery-3.7.1.min.js') }}"></script>

<!-- Feather Icon JS -->
<script src="{{ asset('admin/assets/js/feather.min.js') }}"></script>

<!-- Bootstrap Core JS -->
<script src="{{ asset('admin/assets/js/bootstrap.bundle.min.js') }}"></script>

<!-- Slimscroll JS -->
<script src="{{ asset('admin/assets/js/jquery.slimscroll.min.js') }}"></script>

<!-- Daterangepikcer JS -->
<script src="{{ asset('admin/assets/js/moment.min.js') }}"></script>
<script src="{{ asset('admin/assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('admin/assets/js/bootstrap-datetimepicker.min.js') }}"></script>

<!-- Bootstrap Tagsinput JS -->
<script src="{{ asset('admin/assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js') }}"></script>

<!-- Select2 JS -->
<script src="{{ asset('admin/assets/plugins/select2/js/select2.min.js') }}"></script>

<!-- ApexChart JS -->
<script src="{{ asset('admin/assets/plugins/apexchart/apexcharts.min.js') }}"></script>
<script src="{{ asset('admin/assets/plugins/apexchart/chart-data.js') }}"></script>

<!-- Peity Chart -->
<script src="{{ asset('admin/assets/plugins/peity/jquery.peity.min.js') }}"></script>
<script src="{{ asset('admin/assets/plugins/peity/chart-data.js') }}"></script>

<!-- Custom JS -->
<script src="{{ asset('admin/assets/js/script.js') }}"></script>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        if (window.jQuery) {
            var getCsrfToken = function () {
                return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            };

            $.ajaxSetup({
                beforeSend: function (xhr) {
                    var token = getCsrfToken();
                    if (token) {
                        xhr.setRequestHeader('X-CSRF-TOKEN', token);
                    }
                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                }
            });

            // Keep admin session alive while user is filling forms / generating AI content.
            setInterval(function () {
                $.ajax({
                    url: "{{ route('admin.keep-alive') }}",
                    method: 'GET',
                    dataType: 'json',
                    global: false
                }).done(function (response) {
                    if (response && response.csrf_token) {
                        $('meta[name="csrf-token"]').attr('content', response.csrf_token);
                    }
                });
            }, 5 * 60 * 1000);

            var retrying419 = false;
            $(document).ajaxError(function (_event, jqXHR, ajaxSettings) {
                if (jqXHR.status !== 419 || retrying419) {
                    return;
                }

                retrying419 = true;

                $.ajax({
                    url: "{{ route('admin.keep-alive') }}",
                    method: 'GET',
                    dataType: 'json',
                    global: false
                }).done(function (response) {
                    if (response && response.csrf_token) {
                        $('meta[name="csrf-token"]').attr('content', response.csrf_token);
                    }

                    if (ajaxSettings && !ajaxSettings.__retriedAfter419) {
                        ajaxSettings.__retriedAfter419 = true;
                        $.ajax(ajaxSettings);
                    } else {
                        window.location.reload();
                    }
                }).fail(function () {
                    window.location.href = "{{ route('admin.login') }}";
                }).always(function () {
                    retrying419 = false;
                });
            });
        }

        @foreach (['success' => 'success', 'error' => 'error', 'warning' => 'warning', 'info' => 'info'] as $type => $icon)
            @if (session($type))
                Swal.fire({
                    icon: '{{ $icon }}',
                    title: "{{ ucfirst($icon) }}",
                    text: @json(session($type)),
                    timer: 3500,
                    showConfirmButton: false
                });
            @endif
        @endforeach
    });
</script>
@stack('scripts')
