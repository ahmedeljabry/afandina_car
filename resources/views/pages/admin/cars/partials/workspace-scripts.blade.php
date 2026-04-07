@php
    $isEditMode = ($mode ?? 'create') === 'edit';
@endphp

@once
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            const carStudioConfig = {
                isEdit: @json($isEditMode),
                formId: @json($isEditMode ? 'editCarForm' : 'createCarForm'),
                modelsUrlTemplate: @json(route('admin.cars.models', ['brand' => '__brand__'])),
                mediaEnabled: @json(!$isEditMode),
            };
            const tagifyInstances = {};
            const aiLanguages = @json($activeLanguages->pluck('code')->toArray());
            let selectedFiles = [];

            function escapeHtml(value) {
                if (value === null || value === undefined) return '';
                return String(value).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
            }

            function showLoader() { $('#loader-overlay').css('display', 'flex'); }
            function hideLoader() { $('#loader-overlay').hide(); }
            function modelsUrl(brandId) { return carStudioConfig.modelsUrlTemplate.replace('__brand__', brandId); }

            function setInputFiles(input, files) {
                if (!input || typeof DataTransfer === 'undefined') return false;
                const transfer = new DataTransfer();
                files.forEach(file => transfer.items.add(file));
                input.files = transfer.files;
                return true;
            }

            function appendInlineError(fieldKey, message) {
                if (fieldKey === 'default_image_path') {
                    $('#default-image-name').after('<div class="invalid-feedback d-block">' + escapeHtml(message) + '</div>');
                    return;
                }

                if (fieldKey === 'media' || fieldKey.startsWith('media.')) {
                    $('#media-files-name').after('<div class="invalid-feedback d-block">' + escapeHtml(message) + '</div>');
                    return;
                }

                const segments = fieldKey.split('.');
                let bracketName = segments[0];
                for (let index = 1; index < segments.length; index++) {
                    bracketName += /^[0-9]+$/.test(segments[index]) ? '[]' : '[' + segments[index] + ']';
                }

                let selector = '[name="' + fieldKey + '"], [name="' + bracketName + '"]';
                if (segments[0] === 'long_description' && segments.length === 2) {
                    selector += ', [name="translations[' + segments[1] + '][long_description]"]';
                }
                if (fieldKey.includes('.')) selector += ', [name="' + fieldKey.split('.')[0] + '[]"]';
                const input = $(selector).first();
                if (!input.length) return;

                input.addClass('is-invalid');

                if (input.hasClass('select2-hidden-accessible')) {
                    input.next('.select2-container').find('.select2-selection').addClass('is-invalid');
                    input.next('.select2-container').after('<div class="invalid-feedback d-block">' + escapeHtml(message) + '</div>');
                    return;
                }

                if (input.attr('type') === 'checkbox') {
                    input.closest('.custom-control').after('<div class="invalid-feedback d-block">' + escapeHtml(message) + '</div>');
                    return;
                }

                input.after('<div class="invalid-feedback d-block">' + escapeHtml(message) + '</div>');
            }

            function buildSeoQuestionGroup(lang, index, qa = { question: '', answer: '' }) {
                return `
                    <div class="seo-question-group mb-3">
                        <div class="studio-field mb-3">
                            <label class="studio-label">Question</label>
                            <input type="text" name="seo_questions[${lang}][${index}][question]" class="form-control" value="${escapeHtml(qa.question ?? '')}" placeholder="Enter Question">
                        </div>
                        <div class="studio-field">
                            <label class="studio-label">Answer</label>
                            <textarea name="seo_questions[${lang}][${index}][answer]" class="form-control" rows="3" placeholder="Enter Answer">${escapeHtml(qa.answer ?? '')}</textarea>
                        </div>
                        <button type="button" class="btn btn-sm btn-danger mt-3 remove-question">Remove</button>
                    </div>`;
            }

            function collectAiContext(lang) {
                const context = {};
                const modelOption = $('#car_model_id option:selected');
                context.brand = $('#brand_id option:selected').val() ? $('#brand_id option:selected').text().trim() : null;
                context.model = modelOption.val() ? modelOption.text().trim() : null;
                context.year = $('#year_id option:selected').val() ? $('#year_id option:selected').text().trim() : null;
                context.color = $('#color_id option:selected').val() ? $('#color_id option:selected').text().trim() : null;
                context.gear_type = $('#gear_type_id option:selected').val() ? $('#gear_type_id option:selected').text().trim() : null;
                context.categories = $('#category_id option:selected').map(function () { return $(this).val() ? $(this).text().trim() : null; }).get().filter(Boolean);
                context.primary_category = context.categories[0] || null;
                context.features = $('#features option:selected').map(function () { return $(this).text().trim() || null; }).get().filter(Boolean);
                context.target_language = lang;
                context.original_name = ($('#name_en').val() || '').trim() || null;
                ['door_count','luggage_capacity','passenger_capacity','daily_main_price','daily_discount_price','daily_mileage_included','weekly_main_price','weekly_discount_price','weekly_mileage_included','monthly_main_price','monthly_discount_price','monthly_mileage_included'].forEach(function (field) {
                    context[field] = $('#' + field).val();
                });
                context.daily_price = $('#daily_main_price').val();
                context.weekly_price = $('#weekly_main_price').val();
                context.monthly_price = $('#monthly_main_price').val();
                ['insurance_included','free_delivery','is_flash_sale','is_featured','only_on_afandina','crypto_payment_accepted'].forEach(function (field) {
                    context[field] = $('#' + field).is(':checked');
                });
                return context;
            }

            function populateAiContent(lang, data) {
                if (!data) return;
                if (data.name) $('#name_' + lang).val(data.name);
                if (data.description) $('#description_' + lang).val(data.description);
                if (data.long_description) {
                    const editor = typeof tinymce !== 'undefined' ? tinymce.get('long_description_' + lang) : null;
                    if (editor) editor.setContent(data.long_description);
                    else $('#long_description_' + lang).val(data.long_description);
                }
                if (data.meta_title) $('#meta_title_' + lang).val(data.meta_title);
                if (data.meta_description) $('#meta_description_' + lang).val(data.meta_description);
                if (Array.isArray(data.meta_keywords)) {
                    if (tagifyInstances[lang]) {
                        tagifyInstances[lang].removeAllTags();
                        tagifyInstances[lang].addTags(data.meta_keywords);
                    } else {
                        $('#meta_keywords_' + lang).val(data.meta_keywords.join(', '));
                    }
                }
                if (Array.isArray(data.seo_questions)) {
                    const container = $('#seo-questions-' + lang);
                    container.empty();
                    data.seo_questions.forEach(function (qa, index) { container.append(buildSeoQuestionGroup(lang, index, qa)); });
                    if (data.seo_questions.length === 0) container.append(buildSeoQuestionGroup(lang, 0));
                }
            }

            function populateGeneralFields(data) {
                if (!data || typeof data !== 'object') return;
                ['door_count','luggage_capacity','passenger_capacity','daily_main_price','daily_discount_price','daily_mileage_included','weekly_main_price','weekly_discount_price','weekly_mileage_included','monthly_main_price','monthly_discount_price','monthly_mileage_included'].forEach(function (field) {
                    if (data[field] !== undefined && data[field] !== null && data[field] !== '') $('#' + field).val(data[field]);
                });
            }

            function generateContentForLanguage(lang, options = {}) {
                const opts = Object.assign({ button: null, manageLoader: true, silent: false }, options);
                return new Promise((resolve, reject) => {
                    let nameValue = ($('#name_' + lang).val() || '').trim();
                    const englishName = ($('#name_en').val() || '').trim();
                    if (!nameValue && lang !== 'en' && englishName) nameValue = englishName;
                    if (!nameValue) {
                        Swal.fire({ icon: 'warning', title: 'Car name required', text: 'Please enter at least one car name so the AI can generate content.' });
                        $('#name_' + lang).focus();
                        reject(new Error('Car name required'));
                        return;
                    }

                    const payload = { language: lang, name: nameValue, context: collectAiContext(lang) };
                    const button = opts.button && opts.button.length ? opts.button : null;
                    let originalHtml = null;
                    if (button) {
                        originalHtml = button.html();
                        button.data('original-html', originalHtml);
                        button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Generating...');
                    }
                    if (opts.manageLoader) showLoader();

                    $.ajax({
                        url: "{{ route('admin.cars.generate-content') }}",
                        method: 'POST',
                        data: JSON.stringify(payload),
                        contentType: 'application/json',
                        processData: false,
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        success: function (response) {
                            if (response && response.success) {
                                populateAiContent(lang, response.data);
                                const tabTrigger = document.getElementById('pills-' + lang + '-tab');
                                if (tabTrigger && window.bootstrap && window.bootstrap.Tab) window.bootstrap.Tab.getOrCreateInstance(tabTrigger).show();
                                if (!opts.silent) Swal.fire({ icon: 'success', title: 'AI content generated', text: 'Review the generated copy and adjust it as needed.', timer: 2200, showConfirmButton: false });
                                resolve(response.data);
                            } else {
                                reject(new Error(response && response.message ? response.message : 'Unable to generate AI content right now.'));
                            }
                        },
                        error: function (xhr) {
                            reject(new Error(xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Unable to generate AI content right now.'));
                        },
                        complete: function () {
                            if (opts.manageLoader) hideLoader();
                            if (button) button.prop('disabled', false).html(button.data('original-html'));
                        }
                    });
                }).catch(error => {
                    if (!options.silent) Swal.fire({ icon: 'error', title: 'Generation failed', text: error.message || 'Unable to generate AI content right now.' });
                    throw error;
                });
            }
        </script>
        <script>
            function initModelLoader() {
                const brandSelect = $('#brand_id');
                const modelSelect = $('#car_model_id');
                const initialModelId = modelSelect.data('selected');

                function loadModels(brandId, selectedModelId = null) {
                    modelSelect.empty().append('<option value="">-- Select Model --</option>');
                    if (!brandId) return;
                    $.ajax({
                        url: modelsUrl(brandId),
                        type: 'GET',
                        success: function (data) {
                            data.forEach(function (model) {
                                const selected = selectedModelId && String(selectedModelId) === String(model.id) ? 'selected' : '';
                                modelSelect.append('<option value="' + model.id + '" ' + selected + '>' + model.name + '</option>');
                            });
                            if (selectedModelId) modelSelect.val(selectedModelId).trigger('change');
                        }
                    });
                }

                brandSelect.on('change', function () { loadModels($(this).val(), null); });
                if (brandSelect.val()) loadModels(brandSelect.val(), initialModelId);
            }

            function initSeoUi() {
                $(document).on('click', '.add-question', function () {
                    const lang = $(this).data('lang');
                    const container = $('#seo-questions-' + lang);
                    const count = container.find('.seo-question-group').length;
                    container.append(buildSeoQuestionGroup(lang, count));
                });
                $(document).on('click', '.remove-question', function () {
                    $(this).closest('.seo-question-group').remove();
                });
            }

            function initTagifyFields() {
                @foreach ($activeLanguages as $lang)
                    {
                        const input = document.querySelector('#meta_keywords_{{ $lang->code }}');
                        if (input && typeof Tagify !== 'undefined') {
                            tagifyInstances['{{ $lang->code }}'] = new Tagify(input, { placeholder: 'Enter meta keywords' });
                        } else if (input) {
                            input.setAttribute('placeholder', 'Enter meta keywords (comma separated)');
                        }
                    }
                @endforeach
            }

            function initSelect2Fields() {
                if (typeof $.fn.select2 === 'undefined') return;
                $('.studio-select').select2({ theme: 'bootstrap4', width: '100%' });
            }

            function initFeatureSelectTemplate() {
                if (typeof $.fn.select2 === 'undefined') return;
                function formatFeature(feature) {
                    if (!feature.id) return feature.text;
                    return $('<span><i class="' + $(feature.element).data('icon') + '"></i> ' + feature.text + '</span>');
                }
                $('.feature-select').select2({ theme: 'bootstrap4', width: '100%', templateResult: formatFeature, templateSelection: formatFeature, allowClear: true, placeholder: 'Select features' });
            }

            function initMediaUploads() {
                if (!carStudioConfig.mediaEnabled) return;
                const defaultImageInput = document.getElementById('default_image_path');
                const mediaInput = document.getElementById('media-files');
                const preview = document.getElementById('preview');

                function refreshDefaultImagePreview(file) {
                    const label = document.getElementById('default-image-name');
                    if (label) label.textContent = file ? file.name : 'No image selected yet';
                    if (!file) return;
                    const reader = new FileReader();
                    reader.onload = function (event) { $('#imagePreviewLogo').attr('src', event.target.result); };
                    reader.readAsDataURL(file);
                }

                function fileSignature(file) { return [file.name, file.size, file.lastModified, file.type].join('::'); }
                function updateMediaMeta() {
                    $('#media-count-badge').text(selectedFiles.length + ' file' + (selectedFiles.length === 1 ? '' : 's'));
                    $('#media-files-name').text(selectedFiles.length ? selectedFiles.map(file => file.name).join(', ') : 'No media selected yet');
                }
                function renderPreviews() {
                    if (!preview) return;
                    preview.innerHTML = '';
                    if (!selectedFiles.length) {
                        preview.innerHTML = '<div class="preview-empty">Selected media previews will appear here before saving.</div>';
                        updateMediaMeta();
                        return;
                    }
                    selectedFiles.forEach(function (file, index) {
                        const objectUrl = URL.createObjectURL(file);
                        const mediaMarkup = file.type.startsWith('video/') ? '<video src="' + objectUrl + '" controls class="img-fluid"></video>' : '<img src="' + objectUrl + '" class="img-fluid" alt="' + escapeHtml(file.name) + '">';
                        preview.insertAdjacentHTML('beforeend', '<div class="preview-item" data-index="' + index + '">' + mediaMarkup + '<button type="button" class="remove-preview" data-index="' + index + '">x</button><div class="preview-name">' + escapeHtml(file.name) + '</div></div>');
                    });
                    updateMediaMeta();
                }
                function syncMediaInput() {
                    setInputFiles(mediaInput, selectedFiles);
                    updateMediaMeta();
                }
                function mergeFiles(files) {
                    const existing = new Set(selectedFiles.map(fileSignature));
                    Array.from(files || []).forEach(function (file) {
                        const signature = fileSignature(file);
                        if (!existing.has(signature)) {
                            selectedFiles.push(file);
                            existing.add(signature);
                        }
                    });
                    syncMediaInput();
                    renderPreviews();
                }

                if (defaultImageInput) {
                    defaultImageInput.addEventListener('change', function (event) { refreshDefaultImagePreview(event.target.files && event.target.files[0]); });
                }
                if (mediaInput) {
                    mediaInput.addEventListener('change', function (event) { mergeFiles(event.target.files); });
                }
                $(document).on('click', '.remove-preview', function () {
                    const index = parseInt($(this).data('index'), 10);
                    if (!Number.isNaN(index)) {
                        selectedFiles.splice(index, 1);
                        syncMediaInput();
                        renderPreviews();
                    }
                });
                $('[data-upload-target]').on('click', function () {
                    const target = document.getElementById($(this).data('upload-target'));
                    if (target) target.click();
                });
                renderPreviews();
            }

            function initFormSubmission() {
                const form = $('#' + carStudioConfig.formId);
                if (!form.length) return;
                form.on('submit', function (event) {
                    event.preventDefault();
                    if (typeof tinymce !== 'undefined') tinymce.triggerSave();
                    $('.alert-danger, .invalid-feedback.d-block').remove();
                    $('.is-invalid').removeClass('is-invalid');
                    showLoader();
                    const submitButton = form.find('button[type="submit"]');
                    submitButton.prop('disabled', true);

                    $.ajax({
                        url: form.attr('action'),
                        method: 'POST',
                        data: new FormData(this),
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            hideLoader();
                            submitButton.prop('disabled', false);
                            if (response && response.success) {
                                Swal.fire({ icon: 'success', title: 'Success', text: response.message, confirmButtonText: 'OK' }).then(function () {
                                    if (response.redirect) window.location.href = response.redirect;
                                });
                            }
                        },
                        error: function (xhr) {
                            hideLoader();
                            submitButton.prop('disabled', false);
                            const errors = xhr.responseJSON && xhr.responseJSON.errors ? xhr.responseJSON.errors : {};
                            let errorHtml = '<div class="alert alert-danger shadow-sm mt-3" role="alert"><strong>Please correct the following errors:</strong><ul class="mb-0 mt-2">';
                            Object.entries(errors).forEach(function ([field, messages]) {
                                messages.forEach(function (message) {
                                    errorHtml += '<li>' + escapeHtml(message) + '</li>';
                                    appendInlineError(field, message);
                                });
                            });
                            errorHtml += '</ul></div>';
                            if (Object.keys(errors).length) form.before(errorHtml);
                            Swal.fire({ icon: 'error', title: 'Validation error', text: xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Please review the highlighted fields.' });
                        }
                    });
                });
            }

            $(document).ready(function () {
                initSelect2Fields();
                initFeatureSelectTemplate();
                initModelLoader();
                initSeoUi();
                initTagifyFields();
                initMediaUploads();
                initFormSubmission();

                $(document).on('click', '.generate-ai-content', function () {
                    const button = $(this);
                    generateContentForLanguage(button.data('lang'), { button: button }).catch(() => {});
                });

                $(document).on('click', '.generate-ai-all', async function () {
                    const button = $(this);
                    if (!($('#name_en').val() || '').trim()) {
                        Swal.fire({ icon: 'warning', title: 'Add English name', text: 'Enter the car name in English before generating AI content.' });
                        $('#name_en').focus();
                        return;
                    }
                    const originalHtml = button.html();
                    button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Generating...');
                    showLoader();
                    try {
                        for (const lang of Array.from(new Set(['en', ...aiLanguages]))) {
                            const data = await generateContentForLanguage(lang, { manageLoader: false, silent: true });
                            if (lang === 'en') populateGeneralFields(data);
                        }
                        Swal.fire({ icon: 'success', title: 'All content generated', text: 'Review the generated content and adjust it before saving.', timer: 2400, showConfirmButton: false });
                    } catch (error) {
                        Swal.fire({ icon: 'error', title: 'Generation interrupted', text: error.message || 'Unable to generate AI content right now.' });
                    } finally {
                        hideLoader();
                        button.prop('disabled', false).html(originalHtml);
                    }
                });
            });
        </script>
    @endpush
@endonce
