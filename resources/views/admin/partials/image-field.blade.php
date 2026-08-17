{{--
    Image picker for admin forms.

    Usage: @include('admin.partials.image-field', ['value' => $item->image])
    Optional: 'field' (text input name, default "image"), 'label', 'library'
    (Media collection — falls back to the 20 newest uploads), and 'fit' —
    "cover" for photos that fill their frame, "contain" for logos.

    Requires the parent <form> to carry enctype="multipart/form-data": the file
    input posts as "{field}_file" and the controller stores it, overriding the
    text value.
--}}
@php
    $field = $field ?? 'image';
    $label = $label ?? 'Image';
    $fit = $fit ?? 'cover';
    $value = old($field, $value ?? '');
    $library = $library ?? \App\Models\Media::latest()->take(20)->get();
@endphp

<div class="field img-field" data-img-field="{{ $field }}">
    <label>{{ $label }}</label>

    <div class="img-picker">
        <div class="img-preview">
            <img data-img-preview src="{{ $value ? media_url($value) : '' }}" alt=""
                 style="object-fit: {{ $fit }}; {{ $value ? '' : 'display:none' }}">
            <span data-img-empty class="img-empty" style="{{ $value ? 'display:none' : '' }}">No image</span>
        </div>

        <div class="img-controls">
            <div class="img-actions">
                <label class="btn btn-sm btn-ghost" style="margin:0">
                    Upload new
                    <input type="file" name="{{ $field }}_file" accept="image/jpeg,image/png,image/webp"
                           data-img-file hidden>
                </label>
                <button type="button" class="btn btn-sm btn-ghost" data-img-browse>Choose from library</button>
                <button type="button" class="btn btn-sm btn-ghost" data-img-clear
                        style="{{ $value ? '' : 'display:none' }}">Remove</button>
            </div>

            <input type="text" name="{{ $field }}" data-img-input value="{{ $value }}"
                   placeholder="uploads/… path or https:// URL">
            <p class="hint">Upload a file, pick one from the library, or paste a path/URL. Files are added to
                <a href="{{ route('admin.media.index') }}">Media</a> automatically. jpg / png / webp, max 10 MB.</p>
            <p class="hint" data-img-filename style="display:none;color:#1c2833;font-weight:600"></p>
        </div>
    </div>

    <div class="img-library" data-img-library hidden>
        @forelse($library as $media)
            <button type="button" class="img-lib-item" data-img-pick="{{ $media->path }}"
                    title="{{ $media->original_name }}">
                <img src="{{ $media->url() }}" alt="" loading="lazy" style="object-fit: {{ $fit }}">
                <span>{{ $media->original_name }}</span>
            </button>
        @empty
            <p class="hint" style="margin:0">Nothing uploaded yet — use “Upload new” above.</p>
        @endforelse
    </div>
</div>

@once
    @push('scripts')
        <script>
            document.querySelectorAll('[data-img-field]').forEach((root) => {
                const input = root.querySelector('[data-img-input]');
                const file = root.querySelector('[data-img-file]');
                const preview = root.querySelector('[data-img-preview]');
                const empty = root.querySelector('[data-img-empty]');
                const clear = root.querySelector('[data-img-clear]');
                const library = root.querySelector('[data-img-library]');
                const filename = root.querySelector('[data-img-filename]');

                const render = (src) => {
                    preview.src = src || '';
                    preview.style.display = src ? '' : 'none';
                    empty.style.display = src ? 'none' : '';
                    clear.style.display = src ? '' : 'none';
                };

                input.addEventListener('input', () => render(input.value.trim()));

                root.querySelector('[data-img-browse]').addEventListener('click', () => {
                    library.hidden = !library.hidden;
                });

                library.querySelectorAll('[data-img-pick]').forEach((btn) => {
                    btn.addEventListener('click', () => {
                        input.value = btn.dataset.imgPick;
                        file.value = '';
                        filename.style.display = 'none';
                        render(btn.querySelector('img').src);
                        library.hidden = true;
                    });
                });

                file.addEventListener('change', () => {
                    const picked = file.files[0];
                    if (!picked) return;
                    render(URL.createObjectURL(picked));
                    filename.textContent = `Will upload: ${picked.name}`;
                    filename.style.display = '';
                    library.hidden = true;
                });

                clear.addEventListener('click', () => {
                    input.value = '';
                    file.value = '';
                    filename.style.display = 'none';
                    render('');
                });
            });
        </script>
    @endpush
@endonce
