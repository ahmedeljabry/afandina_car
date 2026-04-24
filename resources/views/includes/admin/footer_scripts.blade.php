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

<!-- SweetAlert2 JS -->
<script src="{{ asset('admin/assets/plugins/sweetalert/sweetalert2.all.min.js') }}"></script>

<!-- Tagify JS -->
<script src="{{ asset('admin/assets/plugins/tagify/tagify.min.js') }}"></script>

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
    (function () {
        const translations = {
            success: @json(__('Success')),
            error: @json(__('Error')),
            warning: @json(__('Warning')),
            info: @json(__('Info')),
            ok: @json(__('OK')),
            cancel: @json(__('Cancel'))
        };
        const syncNotificationFeedUrl = @json(\Illuminate\Support\Facades\Route::has('admin.meta-catalog.notification-feed') ? route('admin.meta-catalog.notification-feed') : null);

        const swalClasses = {
            popup: 'admin-swal-popup',
            title: 'admin-swal-title',
            htmlContainer: 'admin-swal-content',
            confirmButton: 'btn btn-primary admin-swal-confirm',
            cancelButton: 'btn btn-outline-secondary admin-swal-cancel',
            denyButton: 'btn btn-outline-danger admin-swal-deny'
        };

        const defaultSwalOptions = {
            buttonsStyling: false,
            reverseButtons: document.documentElement.dir === 'rtl',
            confirmButtonText: translations.ok,
            cancelButtonText: translations.cancel,
            customClass: swalClasses
        };

        function escapeHtml(value) {
            if (value === null || value === undefined) {
                return '';
            }

            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function mergeSwalOptions(options) {
            const merged = Object.assign({}, defaultSwalOptions, options || {});
            merged.customClass = Object.assign({}, swalClasses, (options && options.customClass) || {});

            return merged;
        }

        function fire(options) {
            if (window.Swal) {
                return window.Swal.fire(mergeSwalOptions(options));
            }

            const fallbackMessage = options && (options.text || options.title || options.html);
            if (fallbackMessage) {
                window.alert(
                    typeof fallbackMessage === 'string'
                        ? fallbackMessage.replace(/<[^>]*>/g, '')
                        : String(fallbackMessage)
                );
            }

            return Promise.resolve();
        }

        const toast = window.Swal
            ? window.Swal.mixin(mergeSwalOptions({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3200,
                timerProgressBar: true,
                customClass: {
                    popup: 'admin-swal-toast'
                }
            }))
            : null;

        const defaultTagifyOptions = {
            delimiters: ',|\n',
            trim: true,
            dropdown: {
                enabled: 0,
                maxItems: 20,
                closeOnSelect: false
            },
            originalInputValueFormat: function (values) {
                return JSON.stringify(values
                    .map(function (tag) {
                        const value = typeof tag === 'string'
                            ? tag
                            : (tag && tag.value) || '';

                        return {
                            value: String(value || '').trim()
                        };
                    })
                    .filter(function (tag) {
                        return tag.value !== '';
                    }));
            }
        };

        function normalizeTagItem(item) {
            if (item === null || item === undefined) {
                return null;
            }

            if (typeof item === 'string') {
                const value = item.trim();

                return value ? { value: value } : null;
            }

            if (typeof item === 'object') {
                const value = String(item.value || item.name || '').trim();

                return value ? Object.assign({}, item, { value: value }) : null;
            }

            return null;
        }

        function parseTagifyValue(rawValue) {
            if (rawValue === null || rawValue === undefined) {
                return [];
            }

            if (Array.isArray(rawValue)) {
                return rawValue
                    .map(normalizeTagItem)
                    .filter(Boolean);
            }

            const stringValue = String(rawValue).trim();
            if (!stringValue || stringValue === '[]') {
                return [];
            }

            try {
                const decoded = JSON.parse(stringValue);
                if (Array.isArray(decoded)) {
                    return decoded
                        .map(normalizeTagItem)
                        .filter(Boolean);
                }
            } catch (_error) {
                // Fall back to comma/newline parsing below.
            }

            return stringValue
                .split(/[\r\n,]+/)
                .map(function (item) {
                    return normalizeTagItem(item);
                })
                .filter(Boolean);
        }

        function mergeTagifyOptions(options) {
            const merged = Object.assign({}, defaultTagifyOptions, options || {});
            merged.dropdown = Object.assign({}, defaultTagifyOptions.dropdown, (options && options.dropdown) || {});

            if (!(options && options.originalInputValueFormat)) {
                merged.originalInputValueFormat = defaultTagifyOptions.originalInputValueFormat;
            }

            return merged;
        }

        function syncTagifyPlaceholder(instance, options) {
            if (!instance || !instance.DOM || !instance.DOM.input) {
                return;
            }

            const placeholder = (options && options.placeholder)
                || instance.DOM.originalInput.getAttribute('placeholder')
                || '';

            instance.DOM.originalInput.setAttribute('placeholder', placeholder);
            instance.DOM.input.setAttribute('placeholder', placeholder);
        }

        function createTagifyInstance(input, options) {
            if (!input || !window.Tagify || !window.Tagify.Native) {
                return null;
            }

            if (input.__afandinaTagify) {
                if (options) {
                    Object.assign(input.__afandinaTagify.settings, mergeTagifyOptions(options));
                    syncTagifyPlaceholder(input.__afandinaTagify, options);
                }

                return input.__afandinaTagify;
            }

            const initialTags = parseTagifyValue(input.value);
            input.value = '';

            const instance = new window.Tagify.Native(input, mergeTagifyOptions(options));
            if (initialTags.length) {
                instance.addTags(initialTags);
            }

            syncTagifyPlaceholder(instance, options);
            input.__afandinaTagify = instance;

            return instance;
        }

        if (window.Tagify && !window.Tagify.__afandinaWrapped) {
            const NativeTagify = window.Tagify;
            const WrappedTagify = function (input, options) {
                return createTagifyInstance(input, options);
            };

            Object.keys(NativeTagify).forEach(function (key) {
                WrappedTagify[key] = NativeTagify[key];
            });

            WrappedTagify.Native = NativeTagify;
            WrappedTagify.prototype = NativeTagify.prototype;
            WrappedTagify.__afandinaWrapped = true;

            window.Tagify = WrappedTagify;
        }

        function initTagifyInputs(root) {
            if (!window.Tagify || !window.Tagify.Native) {
                return [];
            }

            const scope = root || document;
            const inputs = scope.querySelectorAll('input[data-role="tagsinput"], input[data-tagify], input.blog-editor-meta-keywords');

            return Array.from(inputs).map(function (input) {
                return createTagifyInstance(input);
            }).filter(Boolean);
        }

        function clearValidationState(scope) {
            const root = scope || document;

            root.querySelectorAll('.is-invalid').forEach(function (element) {
                element.classList.remove('is-invalid');
            });

            root.querySelectorAll('.invalid-feedback.dynamic-invalid-feedback').forEach(function (element) {
                element.remove();
            });
        }

        function appendValidationMessage(target, message) {
            if (!target || !message) {
                return;
            }

            const feedback = document.createElement('div');
            feedback.className = 'invalid-feedback dynamic-invalid-feedback d-block';
            feedback.textContent = message;
            target.insertAdjacentElement('afterend', feedback);
        }

        function resolveFieldCandidates(fieldName) {
            const candidates = [fieldName];

            if (fieldName && fieldName.includes('.')) {
                const segments = fieldName.split('.');
                const root = segments.shift();

                if (root) {
                    const bracketName = root + segments.map(function (segment) {
                        return '[' + segment + ']';
                    }).join('');

                    candidates.push(bracketName);

                    if (segments.some(function (segment) { return /^\d+$/.test(segment); })) {
                        candidates.push(root + '[]');
                    }
                }
            }

            return Array.from(new Set(candidates.filter(Boolean)));
        }

        function markFieldInvalid(form, fieldName, message) {
            if (!form || !fieldName) {
                return;
            }

            let field = null;
            const candidates = resolveFieldCandidates(fieldName);

            for (let index = 0; index < candidates.length; index += 1) {
                const candidate = candidates[index];
                field = form.querySelector('[name="' + candidate.replace(/"/g, '\\"') + '"]');
                if (field) {
                    break;
                }
            }

            if (!field) {
                return;
            }

            field.classList.add('is-invalid');

            if (field.__afandinaTagify && field.__afandinaTagify.DOM && field.__afandinaTagify.DOM.scope) {
                field.__afandinaTagify.DOM.scope.classList.add('is-invalid');
                appendValidationMessage(field.__afandinaTagify.DOM.scope, message);
                return;
            }

            if (field.classList.contains('select2-hidden-accessible')) {
                const select2Container = field.nextElementSibling;
                if (select2Container) {
                    const selection = select2Container.querySelector('.select2-selection');
                    if (selection) {
                        selection.classList.add('is-invalid');
                    }
                    appendValidationMessage(select2Container, message);
                }
                return;
            }

            if (field.type === 'checkbox' || field.type === 'radio') {
                const container = field.closest('.form-check, .custom-control, .custom-switch') || field.parentElement;
                appendValidationMessage(container, message);
                return;
            }

            appendValidationMessage(field, message);
        }

        function showValidationErrors(errors, options) {
            const settings = Object.assign({
                form: null,
                title: @json(__('Please correct the highlighted fields.')),
                useToast: false
            }, options || {});

            const messages = [];
            clearValidationState(settings.form || document);

            Object.entries(errors || {}).forEach(function (entry) {
                const field = entry[0];
                const fieldMessages = Array.isArray(entry[1]) ? entry[1] : [entry[1]];

                fieldMessages.filter(Boolean).forEach(function (message) {
                    messages.push('<li>' + escapeHtml(message) + '</li>');
                    if (settings.form) {
                        markFieldInvalid(settings.form, field, message);
                    }
                });
            });

            if (!messages.length) {
                return Promise.resolve();
            }

            if (settings.useToast && toast) {
                return toast.fire({
                    icon: 'error',
                    title: settings.title
                });
            }

            return fire({
                icon: 'error',
                title: settings.title,
                html: '<ul class="admin-swal-list">' + messages.join('') + '</ul>'
            });
        }

        const syncNotificationState = {
            initialized: false,
            seen: {},
        };

        function updateNotificationBadge(unreadCount) {
            const badge = document.querySelector('[data-admin-notification-badge]');

            if (!badge) {
                return;
            }

            badge.textContent = unreadCount > 0 ? String(unreadCount) : '';
        }

        function emitSyncNotificationEvent(changedNotifications, notifications) {
            document.dispatchEvent(new CustomEvent('admin:sync-notifications', {
                detail: {
                    changedNotifications: changedNotifications,
                    notifications: notifications,
                }
            }));
        }

        function handleSyncNotifications(notifications, unreadCount) {
            const items = Array.isArray(notifications) ? notifications : [];
            const changedNotifications = [];

            items.forEach(function (item) {
                const key = [item.id, item.status, item.updated_at].filter(Boolean).join(':');

                if (!syncNotificationState.initialized) {
                    syncNotificationState.seen[item.id] = key;
                    return;
                }

                if (syncNotificationState.seen[item.id] === key) {
                    return;
                }

                syncNotificationState.seen[item.id] = key;
                changedNotifications.push(item);

                if (item.type === 'error' || item.type === 'warning') {
                    window.AdminPanel.notify(item.type, item.message, {
                        title: item.title
                    });
                    return;
                }

                window.AdminPanel.toast(item.type, item.title, {
                    text: item.message,
                    timer: item.status === 'running' ? 2600 : 4200,
                });
            });

            syncNotificationState.initialized = true;
            updateNotificationBadge(Number(unreadCount || 0));
            emitSyncNotificationEvent(changedNotifications, items);
        }

        function pollSyncNotifications() {
            if (!syncNotificationFeedUrl || typeof window.fetch !== 'function') {
                return;
            }

            window.fetch(syncNotificationFeedUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            })
                .then(function (response) {
                    if (!response.ok) {
                        throw new Error('Notification feed request failed.');
                    }

                    return response.json();
                })
                .then(function (payload) {
                    handleSyncNotifications(payload.notifications || [], payload.unread_count || 0);
                })
                .catch(function () {
                    // Ignore polling failures quietly to keep the admin UI calm.
                });
        }

        window.AdminPanel = {
            escapeHtml: escapeHtml,
            fire: fire,
            notify: function (type, message, options) {
                return fire(Object.assign({
                    icon: type,
                    title: translations[type] || translations.info,
                    text: message
                }, options || {}));
            },
            toast: function (type, message, options) {
                if (toast) {
                    return toast.fire(Object.assign({
                        icon: type,
                        title: message
                    }, options || {}));
                }

                return fire(Object.assign({
                    icon: type,
                    text: message
                }, options || {}));
            },
            confirm: function (options) {
                return fire(Object.assign({
                    icon: 'question',
                    showCancelButton: true
                }, options || {}));
            },
            parseTagifyValue: parseTagifyValue,
            initTagifyInput: createTagifyInstance,
            initTagifyInputs: initTagifyInputs,
            clearValidationState: clearValidationState,
            showValidationErrors: showValidationErrors,
            pollSyncNotifications: pollSyncNotifications
        };

        document.addEventListener('DOMContentLoaded', function () {
            initTagifyInputs(document);
            pollSyncNotifications();
            window.setInterval(pollSyncNotifications, 15000);
        });

        document.addEventListener('visibilitychange', function () {
            if (!document.hidden) {
                pollSyncNotifications();
            }
        });
    })();

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
                @if (in_array($type, ['success', 'info'], true))
                    window.AdminPanel.toast('{{ $icon }}', @json(session($type)));
                @else
                    window.AdminPanel.notify('{{ $icon }}', @json(session($type)));
                @endif
            @endif
        @endforeach
    });
</script>
@stack('scripts')
