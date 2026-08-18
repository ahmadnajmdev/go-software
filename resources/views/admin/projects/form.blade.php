@extends('admin.layout')

@section('title', $item->exists ? 'Edit project' : 'New project')

@section('content')
    <form method="POST" enctype="multipart/form-data"
          action="{{ $item->exists ? route('admin.projects.update', $item) : route('admin.projects.store') }}">
        @csrf
        @if($item->exists) @method('PUT') @endif

        <div class="card">
            @include('admin.partials.image-field', ['value' => $item->image, 'library' => $media])

            <div class="field">
                <label>Image fit</label>
                @php($fit = old('fit', $item->fit ?? 'cover'))
                <div class="radio-row">
                    <label><input type="radio" name="fit" value="cover" @checked($fit === 'cover')> Fill the tile</label>
                    <label><input type="radio" name="fit" value="contain" @checked($fit === 'contain')> Show the whole image</label>
                </div>
                <p class="hint">Tiles are tall and narrow. <strong>Fill</strong> crops to fit — right for photos.
                    <strong>Show the whole image</strong> never crops, and blurs a copy of the image behind it to
                    fill the tile — use it for logos, wordmarks and screenshots that get cut off.</p>
            </div>
            @include('admin.partials.lang-field', ['field' => 'title', 'label' => 'Title', 'required' => true])

            <div class="field">
                <label for="category_id">Type</label>
                <select id="category_id" name="category_id">
                    <option value="">— Uncategorised —</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            @selected((int) old('category_id', $item->category_id) === $category->id)>
                            {{ $category->tr('name') }}
                        </option>
                    @endforeach
                </select>
                <p class="hint">What we built. The secondary filter on the projects grid.
                    Manage the list in <a href="{{ route('admin.categories.index') }}">Categories</a>.</p>
            </div>

            <div class="field">
                <label for="industry_id">Industry</label>
                <select id="industry_id" name="industry_id">
                    <option value="">— Not set —</option>
                    @foreach($industries as $industry)
                        <option value="{{ $industry->id }}"
                            @selected((int) old('industry_id', $item->industry_id) === $industry->id)>
                            {{ $industry->tr('name') }}
                        </option>
                    @endforeach
                </select>
                <p class="hint">Who the client is. This is the <strong>primary</strong> filter — buyers recognise their own industry long before they recognise "web app".</p>
            </div>

            <div class="field">
                <label for="url">Link <span style="font-weight:400;color:#7b858f">(optional)</span></label>
                <input type="text" id="url" name="url" value="{{ old('url', $item->url) }}" placeholder="https://example.com">
                <p class="hint">The live site or app store listing. Once the project has a story below, this becomes a "Visit live site" button <em>inside</em> the detail page instead of sending people off-site from the tile.</p>
            </div>
        </div>

        <div class="card">
            <h2>Detail page</h2>
            <p class="hint" style="margin:-6px 0 16px">Fill in the problem, what you built, or the result and this project gets its own page at <code>/projects/&lt;slug&gt;</code>. Leave them all empty and the tile behaves as before — no empty page is ever linked. Every field is optional and any section without content is simply not rendered.</p>

            <div class="field">
                <label for="slug">URL slug</label>
                <input type="text" id="slug" name="slug" value="{{ old('slug', $item->slug) }}" placeholder="folivya-academy">
                <p class="hint">Generated from the title if left blank.</p>
            </div>

            <div class="field">
                <label for="client">Client name</label>
                <input type="text" id="client" name="client" value="{{ old('client', $item->client) }}">
            </div>

            @include('admin.partials.lang-field', ['field' => 'outcome', 'label' => 'One-line outcome (shown in the hero)'])
            @include('admin.partials.lang-field', ['field' => 'problem', 'label' => 'The problem', 'type' => 'textarea'])
            @include('admin.partials.lang-field', ['field' => 'solution', 'label' => 'What we built', 'type' => 'textarea'])
            @include('admin.partials.lang-field', ['field' => 'result', 'label' => 'The result (2–3 quantified outcomes)', 'type' => 'textarea'])

            <div class="field">
                <label for="screenshots">Screenshots</label>
                <textarea id="screenshots" name="screenshots" rows="4" placeholder="uploads/2026/08/one.png&#10;uploads/2026/08/two.png">{{ old('screenshots', implode("\n", (array) ($item->screenshots ?? []))) }}</textarea>
                <p class="hint">One path or URL per line, 3–5 of them. Upload in <a href="{{ route('admin.media.index') }}">Media</a> first.</p>
            </div>

            <div class="field">
                <label for="technology">Technology</label>
                <input type="text" id="technology" name="technology" value="{{ old('technology', $item->technology) }}" placeholder="Laravel, Flutter, PostgreSQL">
                <p class="hint">Comma separated; renders as tags.</p>
            </div>

            <div class="grid-2">
                <div class="field">
                    <label for="platforms">Platforms</label>
                    <input type="text" id="platforms" name="platforms" value="{{ old('platforms', $item->platforms) }}" placeholder="iOS, Android, Web">
                </div>
                <div class="field">
                    <label for="timeline">Timeline</label>
                    <input type="text" id="timeline" name="timeline" value="{{ old('timeline', $item->timeline) }}" placeholder="10 weeks">
                </div>
            </div>

            <div class="field">
                <label for="live_since">Live since</label>
                <input type="text" id="live_since" name="live_since" value="{{ old('live_since', $item->live_since) }}" placeholder="March 2025" style="max-width:220px">
            </div>
        </div>

        <div class="card">
            <h2>Client quote</h2>
            <p class="hint" style="margin:-6px 0 16px">Only with the client's permission to publish. Nothing renders unless there is a quote.</p>
            @include('admin.partials.lang-field', ['field' => 'quote', 'label' => 'Quote', 'type' => 'textarea'])
            <div class="grid-2">
                <div class="field">
                    <label for="quote_author">Who said it</label>
                    <input type="text" id="quote_author" name="quote_author" value="{{ old('quote_author', $item->quote_author) }}">
                </div>
                <div class="field">
                    <label for="quote_role">Their role</label>
                    <input type="text" id="quote_role" name="quote_role" value="{{ old('quote_role', $item->quote_role) }}">
                </div>
            </div>
        </div>

        <button type="submit" class="btn">{{ $item->exists ? 'Save changes' : 'Create project' }}</button>
        <a class="btn btn-ghost" href="{{ route('admin.projects.index') }}">Cancel</a>
    </form>
@endsection
