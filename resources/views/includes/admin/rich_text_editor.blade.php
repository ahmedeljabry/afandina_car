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

            .admin-rich-editor .ql-editor.ql-blank::before {
                color: #8a94a6;
                font-style: normal;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="{{ asset('admin/assets/plugins/quill/quill.min.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                if (typeof Quill === 'undefined') {
                    return;
                }

                var toolbarOptions = [
                    [{ header: [1, 2, 3, 4, false] }],
                    ['bold', 'italic', 'underline'],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                    [{ align: [] }, { direction: 'rtl' }],
                    ['link', 'blockquote'],
                    ['clean']
                ];

                var syncEditors = [];

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
                            toolbar: toolbarOptions
                        },
                        placeholder: textarea.getAttribute('placeholder') || '',
                        theme: 'snow'
                    });

                    quill.root.setAttribute('dir', direction);

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
                });

                document.querySelectorAll('form').forEach(function (form) {
                    form.addEventListener('submit', function () {
                        syncEditors.forEach(function (syncInput) {
                            syncInput();
                        });
                    });
                });
            });
        </script>
    @endpush
@endonce
