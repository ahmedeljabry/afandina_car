@once('admin-rich-text-editor')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('admin/assets/plugins/quill/quill.snow.css') }}">
        <style>
            .admin-rich-editor-source.is-enhanced {
                display: none;
            }

            .admin-rich-editor {
                background: #fff;
                border-radius: 8px;
            }

            .admin-rich-editor .ql-toolbar.ql-snow {
                border-color: #ced4da;
                border-radius: 8px 8px 0 0;
                background: #f8f9fa;
            }

            .admin-rich-editor .ql-container.ql-snow {
                min-height: 220px;
                border-color: #ced4da;
                border-radius: 0 0 8px 8px;
                font-size: 15px;
            }

            .admin-rich-editor .ql-editor {
                min-height: 220px;
                line-height: 1.7;
            }

            .admin-rich-editor .ql-editor ul,
            .admin-rich-editor .ql-editor ol {
                padding-inline-start: 1.5rem;
            }

            .admin-rich-editor .ql-editor li {
                margin-bottom: 0.35rem;
            }

            .admin-rich-editor .ql-editor table {
                width: 100%;
                margin: 0.75rem 0;
                border-collapse: collapse;
            }

            .admin-rich-editor .ql-editor table td,
            .admin-rich-editor .ql-editor table th {
                min-width: 90px;
                padding: 0.5rem;
                border: 1px solid #d8dee8;
                vertical-align: top;
            }

            .admin-rich-editor .ql-editor.ql-blank::before {
                color: #8a94a6;
                font-style: normal;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="{{ asset('admin/assets/plugins/quill/quill.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                if (typeof Quill === 'undefined') {
                    return;
                }

                var api = window.AdminRichTextEditor || {};
                api.instances = api.instances || {};

                var toolbarOptions = [
                    [{ header: [1, 2, 3, 4, false] }],
                    ['bold', 'italic', 'underline'],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                    [{ align: [] }, { direction: 'rtl' }],
                    ['link', 'blockquote', 'table'],
                    ['clean']
                ];

                var syncEditors = [];
                var tableButtonLabel = @json(__('Insert table'));

                document.querySelectorAll('textarea.tinymce, textarea.js-rich-editor-source').forEach(function (textarea, index) {
                    if (textarea.dataset.richEditorInitialized === '1') {
                        return;
                    }

                    var editor = document.createElement('div');
                    var editorId = textarea.id ? textarea.id + '_editor' : 'admin_rich_editor_' + index;
                    var languageMatch = textarea.name.match(/\[([a-zA-Z_-]+)\]/);
                    var languageCode = languageMatch ? languageMatch[1].toLowerCase() : '';
                    var direction = textarea.dataset.editorDir || (['ar', 'fa', 'he', 'ur'].indexOf(languageCode) !== -1 ? 'rtl' : 'ltr');

                    editor.id = editorId;
                    editor.className = 'admin-rich-editor';
                    textarea.insertAdjacentElement('afterend', editor);
                    textarea.classList.add('admin-rich-editor-source', 'is-enhanced');
                    textarea.dataset.richEditorInitialized = '1';

                    var quill = new Quill(editor, {
                        modules: {
                            toolbar: {
                                container: toolbarOptions,
                                handlers: {
                                    table: function () {
                                        this.quill.focus();

                                        if (!this.quill.getSelection()) {
                                            this.quill.setSelection(Math.max(this.quill.getLength() - 1, 0), 0);
                                        }

                                        var tableModule = this.quill.getModule('table');

                                        if (tableModule && typeof tableModule.insertTable === 'function') {
                                            tableModule.insertTable(3, 3);
                                        }
                                    }
                                }
                            },
                            table: true
                        },
                        placeholder: textarea.getAttribute('placeholder') || '',
                        theme: 'snow'
                    });

                    quill.root.setAttribute('dir', direction);

                    var toolbar = quill.getModule('toolbar');
                    if (toolbar && toolbar.container) {
                        toolbar.container.querySelectorAll('button.ql-table').forEach(function (button) {
                            button.setAttribute('type', 'button');
                            button.setAttribute('title', tableButtonLabel);
                            button.setAttribute('aria-label', tableButtonLabel);
                        });
                    }

                    var initialValue = textarea.value.trim();

                    if (initialValue !== '') {
                        if (/<[a-z][\s\S]*>/i.test(initialValue)) {
                            quill.clipboard.dangerouslyPasteHTML(initialValue);
                        } else {
                            quill.setText(initialValue);
                        }
                    }

                    var syncInput = function () {
                        textarea.value = quill.getText().trim().length ? quill.root.innerHTML : '';
                    };

                    quill.on('text-change', syncInput);
                    syncEditors.push(syncInput);

                    var editorKey = textarea.id || editorId;
                    api.instances[editorKey] = {
                        textarea: textarea,
                        quill: quill,
                        sync: syncInput
                    };
                });

                api.syncAll = function () {
                    Object.values(api.instances).forEach(function (instance) {
                        instance.sync();
                    });
                };

                api.setContent = function (editorKey, content) {
                    var instance = api.instances[editorKey];

                    if (!instance) {
                        var textarea = document.getElementById(editorKey);
                        if (textarea) {
                            textarea.value = content || '';
                            return true;
                        }

                        return false;
                    }

                    instance.quill.setContents([]);

                    if (content) {
                        if (/<[a-z][\s\S]*>/i.test(content)) {
                            instance.quill.clipboard.dangerouslyPasteHTML(content);
                        } else {
                            instance.quill.setText(content);
                        }
                    }

                    instance.sync();

                    return true;
                };

                api.getContent = function (editorKey) {
                    var instance = api.instances[editorKey];

                    if (!instance) {
                        var textarea = document.getElementById(editorKey);
                        return textarea ? textarea.value : '';
                    }

                    instance.sync();

                    return instance.textarea.value;
                };

                window.AdminRichTextEditor = api;

                document.querySelectorAll('form').forEach(function (form) {
                    form.addEventListener('submit', function () {
                        api.syncAll();
                    });
                });
            });
        </script>
    @endpush
@endonce
