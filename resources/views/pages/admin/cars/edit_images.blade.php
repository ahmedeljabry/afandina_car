@extends('layouts.admin_layout')

@section('title', __('Manage Car Media'))
@section('page-title', __('Media Studio'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.cars.index') }}">{{ __('Cars') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.cars.edit', $item->id) }}">{{ __('Edit Car') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Media Studio') }}</li>
@endsection

@section('page-actions')
    <a href="{{ route('admin.cars.edit', $item->id) }}" class="btn btn-outline-secondary me-2 mb-2">
        <i class="ti ti-arrow-left me-1"></i>{{ __('Back To Car') }}
    </a>
    <a href="{{ route('admin.cars.index') }}" class="btn btn-primary mb-2">
        <i class="ti ti-layout-grid me-1"></i>{{ __('All Cars') }}
    </a>
@endsection

@php
    $carTranslation = $item->translations->firstWhere('locale', 'en') ?? $item->translations->first();
    $carName = $carTranslation?->name ?: __('Car') . ' #' . $item->id;
    $mediaCollection = $item->images->sortByDesc('id')->values();
    $imageCount = $mediaCollection->where('type', 'image')->count();
    $videoCount = $mediaCollection->where('type', 'video')->count();

    $mediaUrl = function (?string $path): ?string {
        if (blank($path)) return null;
        if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://', '//'])) return $path;
        if (\Illuminate\Support\Str::startsWith($path, 'storage/')) return asset($path);
        return asset('storage/' . ltrim($path, '/'));
    };
@endphp

@push('styles')
    <style>
        .studio { display:grid; gap:1.5rem; }
        .hero { border-radius:28px; padding:2rem; color:#fff; background:linear-gradient(135deg,#0b1c39,#0b67d0 58%,#1fc1ff); box-shadow:0 18px 45px rgba(12,31,63,.18); }
        .hero p { max-width:700px; color:rgba(255,255,255,.82); }
        .chips, .actions, .card-actions { display:flex; flex-wrap:wrap; gap:.75rem; }
        .chip { padding:.6rem .95rem; border-radius:999px; background:rgba(255,255,255,.12); }
        .stats { display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:1rem; }
        .stat, .panel, .media-card { background:#fff; border:1px solid rgba(15,23,42,.08); border-radius:24px; box-shadow:0 10px 28px rgba(15,23,42,.08); }
        .stat { padding:1.25rem 1.4rem; }
        .stat span { display:block; font-size:.82rem; color:#64748b; text-transform:uppercase; letter-spacing:.08em; margin-bottom:.45rem; }
        .stat strong { font-size:2rem; color:#0f172a; }
        .layout { display:grid; grid-template-columns:1.05fr .95fr; gap:1.5rem; }
        .panel-head { display:flex; justify-content:space-between; gap:1rem; padding:1.25rem 1.4rem 1rem; border-bottom:1px solid rgba(15,23,42,.08); }
        .panel-body { padding:1.4rem; }
        .panel-head h3 { margin:0; font-size:1.08rem; color:#0f172a; }
        .panel-head p { margin:.35rem 0 0; color:#64748b; }
        .preview-box { min-height:280px; border-radius:22px; overflow:hidden; display:flex; align-items:center; justify-content:center; background:linear-gradient(180deg,#e8f2ff,#d9ebff); }
        .preview-box img { width:100%; height:100%; object-fit:cover; }
        .empty { text-align:center; color:#64748b; padding:2rem; }
        .drop { border:1.5px dashed rgba(20,120,255,.28); border-radius:22px; padding:1rem; background:#f8fbff; }
        .hint { color:#64748b; font-size:.9rem; margin:.45rem 0 0; }
        .upload-preview { display:grid; grid-template-columns:repeat(auto-fill,minmax(160px,1fr)); gap:1rem; margin-top:1rem; }
        .upload-item { border:1px solid rgba(15,23,42,.08); border-radius:18px; background:#f8fafc; padding:.75rem; }
        .upload-item img, .upload-item video { width:100%; height:140px; border-radius:14px; object-fit:cover; }
        .library-head { display:flex; justify-content:space-between; gap:1rem; align-items:center; flex-wrap:wrap; }
        .media-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(300px,1fr)); gap:1.2rem; }
        .media-cover { position:relative; aspect-ratio:4/3; overflow:hidden; border-radius:24px 24px 0 0; background:linear-gradient(180deg,#e8f0ff,#d4e5ff); }
        .media-cover img, .media-cover video { width:100%; height:100%; object-fit:cover; }
        .media-tags { position:absolute; top:.9rem; left:.9rem; display:flex; gap:.45rem; flex-wrap:wrap; z-index:1; }
        .tag { padding:.35rem .65rem; border-radius:999px; color:#fff; background:rgba(15,23,42,.74); font-size:.78rem; font-weight:700; }
        .tag.ok { background:rgba(20,184,106,.88); }
        .selector { position:absolute; top:.9rem; right:.9rem; z-index:1; width:20px; height:20px; }
        .media-body { padding:1rem; display:grid; gap:.85rem; }
        .meta { display:flex; justify-content:space-between; gap:1rem; color:#64748b; font-size:.84rem; }
        .replace-note { font-size:.82rem; color:#64748b; margin-top:.35rem; display:block; }
        .blank-library { border:1px dashed rgba(15,23,42,.16); border-radius:24px; padding:2rem 1rem; text-align:center; color:#64748b; background:#fbfdff; }
        .overlay { position:fixed; inset:0; display:none; align-items:center; justify-content:center; background:rgba(255,255,255,.76); backdrop-filter:blur(3px); z-index:9999; }
        .overlay-card { min-width:260px; text-align:center; padding:1.4rem; border-radius:22px; background:#fff; box-shadow:0 18px 45px rgba(15,23,42,.18); }
        .spin { width:46px; height:46px; margin:0 auto .8rem; border-radius:50%; border:4px solid rgba(20,120,255,.16); border-top-color:#1478ff; animation:spin .9s linear infinite; }
        .studio-alert { display:none; border:none; border-radius:16px; }
        @keyframes spin { from { transform:rotate(0deg); } to { transform:rotate(360deg); } }
        @media (max-width: 1100px) { .layout { grid-template-columns:1fr; } }
        @media (max-width: 767px) { .hero { padding:1.35rem; } .media-grid { grid-template-columns:1fr; } }
    </style>
@endpush

@section('content')
    <div class="overlay" id="studio-overlay">
        <div class="overlay-card">
            <div class="spin"></div>
            <h5 class="mb-1" id="overlay-title">{{ __('Saving changes') }}</h5>
            <p class="mb-0 text-muted" id="overlay-text">{{ __('Please wait while the media is updated.') }}</p>
        </div>
    </div>

    <div class="alert studio-alert" id="studio-alert"></div>

    <div class="studio">
        <section class="hero">
            <h2 class="mb-2">{{ $carName }}</h2>
            <p>{{ __('Manage the default image, upload new media, replace existing files, edit alt text, and save changes immediately from this page.') }}</p>
            <div class="chips">
                <span class="chip"><i class="ti ti-hash me-1"></i>{{ __('Car ID') }}: {{ $item->id }}</span>
                <span class="chip"><i class="ti ti-link me-1"></i>{{ $item->slug ?: __('No slug yet') }}</span>
                <span class="chip"><i class="ti ti-bolt me-1"></i>{{ ucfirst($item->status ?? 'available') }}</span>
            </div>
        </section>

        <section class="stats">
            <div class="stat"><span>{{ __('Total Media') }}</span><strong>{{ $mediaCollection->count() }}</strong></div>
            <div class="stat"><span>{{ __('Images') }}</span><strong>{{ $imageCount }}</strong></div>
            <div class="stat"><span>{{ __('Videos') }}</span><strong>{{ $videoCount }}</strong></div>
            <div class="stat"><span>{{ __('Default Image') }}</span><strong>{{ filled($item->default_image_path) ? __('Ready') : __('Missing') }}</strong></div>
        </section>

        <section class="layout">
            <article class="panel">
                <div class="panel-head">
                    <div>
                        <h3>{{ __('Default Image') }}</h3>
                        <p>{{ __('Upload a new primary image or keep the current one.') }}</p>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="preview-box mb-3" id="default-preview">
                        @if ($item->default_image_path)
                            <img src="{{ $mediaUrl($item->default_image_path) }}" alt="{{ $carName }}">
                        @else
                            <div class="empty">
                                <i class="ti ti-photo fs-1 d-block mb-2"></i>
                                <strong>{{ __('No default image yet') }}</strong>
                            </div>
                        @endif
                    </div>
                    <form id="default-form">
                        <div class="drop">
                            <label class="form-label">{{ __('Choose default image') }}</label>
                            <input type="file" class="form-control" id="default-input" name="image" accept="image/*" required>
                            <p class="hint">{{ __('Supported: JPG, PNG, GIF, SVG, WEBP.') }}</p>
                        </div>
                        <div class="actions mt-3">
                            <button type="submit" class="btn btn-success">
                                <i class="ti ti-device-floppy me-1"></i>{{ __('Save Default Image') }}
                            </button>
                        </div>
                    </form>
                </div>
            </article>

            <article class="panel">
                <div class="panel-head">
                    <div>
                        <h3>{{ __('Upload New Media') }}</h3>
                        <p>{{ __('Add images or videos and save them directly.') }}</p>
                    </div>
                </div>
                <div class="panel-body">
                    <form id="upload-form">
                        <div class="drop">
                            <label class="form-label">{{ __('Choose files') }}</label>
                            <input type="file" class="form-control" id="upload-input" name="files[]" accept="image/*,video/*" multiple required>
                            <p class="hint">{{ __('Supported: JPG, PNG, GIF, SVG, WEBP, MP4, WEBM, OGG.') }}</p>
                        </div>
                        <div class="mt-3">
                            <label class="form-label">{{ __('Alt text for this batch') }}</label>
                            <input type="text" class="form-control" id="upload-alt" name="alt" placeholder="{{ __('Example: Front exterior view') }}">
                        </div>
                        <div id="upload-preview" class="upload-preview"></div>
                        <div class="actions mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-upload me-1"></i>{{ __('Upload Media') }}
                            </button>
                            <button type="button" class="btn btn-outline-secondary" id="clear-upload">{{ __('Clear Selection') }}</button>
                        </div>
                    </form>
                </div>
            </article>
        </section>

        <section class="panel">
            <div class="panel-head library-head">
                <div>
                    <h3>{{ __('Media Library') }}</h3>
                    <p>{{ __('Save alt text changes, replace a file, set an image as default, or delete media.') }}</p>
                </div>
                <div class="actions">
                    <button type="button" class="btn btn-outline-danger" id="delete-selected" disabled>{{ __('Delete Selected') }}</button>
                    <button type="button" class="btn btn-danger" id="delete-all" @disabled($mediaCollection->isEmpty())>{{ __('Delete All') }}</button>
                </div>
            </div>
            <div class="panel-body">
                @if ($mediaCollection->isEmpty())
                    <div class="blank-library">
                        <i class="ti ti-photo-search fs-1 d-block mb-2 text-primary"></i>
                        <strong>{{ __('No media uploaded yet') }}</strong>
                    </div>
                @else
                    <div class="media-grid">
                        @foreach ($mediaCollection as $media)
                            @php $isDefault = $item->default_image_path === $media->file_path && $media->type === 'image'; @endphp
                            <article class="media-card">
                                <div class="media-cover">
                                    <div class="media-tags">
                                        <span class="tag">{{ ucfirst($media->type) }}</span>
                                        @if ($isDefault)
                                            <span class="tag ok">{{ __('Default') }}</span>
                                        @endif
                                    </div>
                                    <input type="checkbox" class="selector media-checkbox" value="{{ $media->id }}">
                                    @if ($media->type === 'video')
                                        <video controls preload="metadata"><source src="{{ $mediaUrl($media->file_path) }}"></video>
                                    @else
                                        <img src="{{ $mediaUrl($media->file_path) }}" alt="{{ $media->alt ?: $carName }}">
                                    @endif
                                </div>
                                <div class="media-body">
                                    <div class="meta">
                                        <span>#{{ $media->id }}</span>
                                        <span>{{ $media->created_at?->format('d M Y') }}</span>
                                    </div>
                                    <form class="media-form" data-id="{{ $media->id }}">
                                        <div>
                                            <label class="form-label">{{ __('Alt text') }}</label>
                                            <input type="text" class="form-control" name="alt" value="{{ $media->alt }}" placeholder="{{ $carName }}">
                                        </div>
                                        <div>
                                            <label class="form-label">{{ __('Replace file') }}</label>
                                            <input type="file" class="form-control replace-input" name="file" accept="image/*,video/*">
                                            <span class="replace-note">{{ __('Leave empty to keep the current file.') }}</span>
                                        </div>
                                        <div class="card-actions">
                                            <button type="submit" class="btn btn-primary btn-sm">{{ __('Save Changes') }}</button>
                                            @if ($media->type === 'image')
                                                <button type="button" class="btn btn-outline-success btn-sm make-default" data-id="{{ $media->id }}">{{ __('Make Default') }}</button>
                                            @endif
                                            <button type="button" class="btn btn-outline-danger btn-sm delete-one" data-id="{{ $media->id }}">{{ __('Delete') }}</button>
                                        </div>
                                    </form>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const routes = {
                upload: @json(url('/admin/cars/' . $item->id . '/upload-image')),
                uploadDefault: @json(url('/admin/cars/' . $item->id . '/upload-default-image')),
                deleteSelected: @json(url('/admin/cars/delete-selected-images')),
                deleteAll: @json(url('/admin/cars/delete-all-images/' . $item->id)),
                deleteOne: @json(url('/admin/cars/delete-image/__ID__')),
                updateOne: @json(url('/admin/cars/images/__ID__/update')),
                makeDefault: @json(url('/admin/cars/images/__ID__/make-default')),
            };

            const overlay = document.getElementById('studio-overlay');
            const alertBox = document.getElementById('studio-alert');
            const uploadInput = document.getElementById('upload-input');
            const uploadPreview = document.getElementById('upload-preview');
            const deleteSelectedBtn = document.getElementById('delete-selected');
            const mediaCheckboxes = [...document.querySelectorAll('.media-checkbox')];

            const routeFor = (template, id) => template.replace('__ID__', id);
            const setLoading = (state, title = 'Saving changes', text = 'Please wait while the media is updated.') => {
                document.getElementById('overlay-title').textContent = title;
                document.getElementById('overlay-text').textContent = text;
                overlay.style.display = state ? 'flex' : 'none';
            };
            const notify = (message, type = 'success') => {
                alertBox.textContent = message;
                alertBox.className = `alert studio-alert alert-${type}`;
                alertBox.style.display = 'block';
                clearTimeout(notify.timer);
                notify.timer = setTimeout(() => { alertBox.style.display = 'none'; }, 4500);
            };
            const refreshSoon = () => setTimeout(() => window.location.reload(), 700);
            const refreshBulkButton = () => { deleteSelectedBtn.disabled = !mediaCheckboxes.some((box) => box.checked); };

            mediaCheckboxes.forEach((box) => box.addEventListener('change', refreshBulkButton));

            uploadInput?.addEventListener('change', () => {
                uploadPreview.innerHTML = '';
                [...uploadInput.files].forEach((file) => {
                    const item = document.createElement('div');
                    item.className = 'upload-item';
                    const label = document.createElement('div');
                    label.className = 'mt-2 small text-muted';
                    label.textContent = file.name;
                    if (file.type.startsWith('video/')) {
                        const media = document.createElement('video');
                        media.controls = true;
                        media.src = URL.createObjectURL(file);
                        item.appendChild(media);
                    } else {
                        const media = document.createElement('img');
                        media.src = URL.createObjectURL(file);
                        media.alt = file.name;
                        item.appendChild(media);
                    }
                    item.appendChild(label);
                    uploadPreview.appendChild(item);
                });
            });

            document.getElementById('clear-upload')?.addEventListener('click', () => {
                uploadInput.value = '';
                uploadPreview.innerHTML = '';
            });

            document.getElementById('default-input')?.addEventListener('change', (event) => {
                const file = event.target.files?.[0];
                if (!file) return;
                const preview = document.getElementById('default-preview');
                preview.innerHTML = `<img src="${URL.createObjectURL(file)}" alt="${file.name}">`;
            });

            document.getElementById('upload-form')?.addEventListener('submit', async (event) => {
                event.preventDefault();
                if (!uploadInput.files.length) return notify('Please choose at least one file.', 'danger');
                const data = new FormData();
                [...uploadInput.files].forEach((file) => data.append('files[]', file));
                data.append('alt', document.getElementById('upload-alt').value);
                try {
                    setLoading(true, 'Uploading media', 'The selected files are being saved now.');
                    const response = await axios.post(routes.upload, data, { headers: { 'Content-Type': 'multipart/form-data' } });
                    notify(response.data.message || 'Media uploaded successfully.');
                    refreshSoon();
                } catch (error) {
                    notify(error.response?.data?.message || 'Unable to upload media.', 'danger');
                } finally {
                    setLoading(false);
                }
            });

            document.getElementById('default-form')?.addEventListener('submit', async (event) => {
                event.preventDefault();
                const input = document.getElementById('default-input');
                if (!input.files.length) return notify('Please choose a default image first.', 'danger');
                const data = new FormData(event.currentTarget);
                try {
                    setLoading(true, 'Saving default image', 'The default image is being updated.');
                    const response = await axios.post(routes.uploadDefault, data, { headers: { 'Content-Type': 'multipart/form-data' } });
                    notify(response.data.message || 'Default image updated successfully.');
                    refreshSoon();
                } catch (error) {
                    notify(error.response?.data?.message || 'Unable to update the default image.', 'danger');
                } finally {
                    setLoading(false);
                }
            });

            document.getElementById('delete-selected')?.addEventListener('click', async () => {
                const ids = mediaCheckboxes.filter((box) => box.checked).map((box) => box.value);
                if (!ids.length) return notify('Select at least one media item first.', 'danger');
                if (!confirm('Delete the selected media items?')) return;
                try {
                    setLoading(true, 'Deleting selected media', 'The selected items are being removed.');
                    const response = await axios.post(routes.deleteSelected, { mediaIds: ids });
                    notify(response.data.message || 'Selected media deleted successfully.');
                    refreshSoon();
                } catch (error) {
                    notify(error.response?.data?.message || 'Unable to delete the selected media.', 'danger');
                } finally {
                    setLoading(false);
                }
            });

            document.getElementById('delete-all')?.addEventListener('click', async () => {
                if (!confirm('Delete all media for this car?')) return;
                try {
                    setLoading(true, 'Deleting all media', 'All media for this car is being removed.');
                    const response = await axios.post(routes.deleteAll);
                    notify(response.data.message || 'All media deleted successfully.');
                    refreshSoon();
                } catch (error) {
                    notify(error.response?.data?.message || 'Unable to delete all media.', 'danger');
                } finally {
                    setLoading(false);
                }
            });

            document.querySelectorAll('.replace-input').forEach((input) => {
                input.addEventListener('change', () => {
                    const note = input.parentElement.querySelector('.replace-note');
                    note.textContent = input.files.length ? `Selected: ${input.files[0].name}` : 'Leave empty to keep the current file.';
                });
            });

            document.querySelectorAll('.media-form').forEach((form) => {
                form.addEventListener('submit', async (event) => {
                    event.preventDefault();
                    const id = form.dataset.id;
                    const data = new FormData();
                    const altInput = form.querySelector('[name="alt"]');
                    const fileInput = form.querySelector('[name="file"]');
                    data.append('alt', altInput.value);
                    if (fileInput.files.length) {
                        data.append('file', fileInput.files[0]);
                    }
                    try {
                        setLoading(true, 'Saving media changes', 'The selected media item is being updated.');
                        const response = await axios.post(routeFor(routes.updateOne, id), data, { headers: { 'Content-Type': 'multipart/form-data' } });
                        notify(response.data.message || 'Media updated successfully.');
                        refreshSoon();
                    } catch (error) {
                        notify(error.response?.data?.message || 'Unable to save media changes.', 'danger');
                    } finally {
                        setLoading(false);
                    }
                });
            });

            document.querySelectorAll('.make-default').forEach((button) => {
                button.addEventListener('click', async () => {
                    const id = button.dataset.id;
                    try {
                        setLoading(true, 'Updating default image', 'The selected image is becoming the default.');
                        const response = await axios.post(routeFor(routes.makeDefault, id));
                        notify(response.data.message || 'Default image updated successfully.');
                        refreshSoon();
                    } catch (error) {
                        notify(error.response?.data?.message || 'Unable to update the default image.', 'danger');
                    } finally {
                        setLoading(false);
                    }
                });
            });

            document.querySelectorAll('.delete-one').forEach((button) => {
                button.addEventListener('click', async () => {
                    const id = button.dataset.id;
                    if (!confirm('Delete this media item?')) return;
                    try {
                        setLoading(true, 'Deleting media', 'The selected media item is being removed.');
                        const response = await axios.delete(routeFor(routes.deleteOne, id));
                        notify(response.data.message || 'Media deleted successfully.');
                        refreshSoon();
                    } catch (error) {
                        notify(error.response?.data?.message || 'Unable to delete this media item.', 'danger');
                    } finally {
                        setLoading(false);
                    }
                });
            });

            refreshBulkButton();
        });
    </script>
@endpush
