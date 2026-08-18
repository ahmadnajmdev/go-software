{{-- One project card. Shared by the home section and the archive page.
     Becomes a link when the project has a url; otherwise a plain div, so the
     drag-reorder and delete affordances keep working in edit mode. --}}
@php
    $src = media_url($project->image);
    $contain = ($project->fit ?? 'cover') === 'contain';
    $tag = filled($project->url) ? 'a' : 'div';
@endphp
<{{ $tag }} class="gs-proj{{ filled($project->url) ? ' is-linked' : '' }}"
    data-item-id="{{ $project->id }}" data-item-model="projects"
    @if (filled($project->url))
        href="{{ $project->url }}" target="_blank" rel="noopener"
        data-gs-track="project_view" data-gs-project="{{ $project->tr('title', 'en') }}"
    @endif
    x-show="cat === 'all' || cat === '{{ $project->category?->slug ?? '' }}'" x-transition.opacity
    style="position: relative; border-radius: var(--gs-r-card, 16px); overflow: hidden; aspect-ratio: 1 / 1; display: block;">

    @if ($contain)
        {{-- Whole image on white — transparent PNG logos need a clean ground,
             not the artwork blurred behind them. --}}
        <div class="gs-proj-media" @auth data-edit-image data-image-field="image" @endauth>
            <div class="gs-proj-fit {{ $src ? '' : 'gs-photo-empty' }}" @if ($src) style="background-image: url('{{ $src }}');" @endif></div>
        </div>
    @else
        <div class="{{ $src ? '' : 'gs-photo-empty' }}" @auth data-edit-image data-image-field="image" @endauth
             style="width: 100%; height: 100%;@if ($src) background: #dfe7e7 center/cover no-repeat; background-image: url('{{ $src }}');@endif"></div>
    @endif

    @auth<button type="button" class="gs-edit-x" data-edit-delete title="Delete project">✕</button>@endauth

    {{-- A photo carries white text on a dark scrim. A logo sits on white, so
         a scrim would just smudge it grey — the caption takes dark-on-white
         instead and the ground stays clean. --}}
    @unless ($contain)
        <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(13,24,38,.92) 8%, transparent 58%);"></div>
    @endunless

    <div style="position: absolute; bottom: 0; left: 0; right: 0; padding: 22px; {{ $contain ? 'background: #fff;' : '' }}">
        @if ($project->category)
            <span style="color: {{ $contain ? 'var(--gs-accent, #2CA69C)' : 'var(--gs-accent-lite, #6FDED3)' }}; font-family: 'Space Grotesk'; font-weight: 600; font-size: 12px; letter-spacing: .12em;">{{ $project->category->tr('name') }}</span>
        @endif
        <h3 style="font-family: 'Space Grotesk'; font-weight: 600; font-size: 19px; color: {{ $contain ? '#0d1826' : '#fff' }}; margin-top: 6px; display: flex; align-items: center; gap: 8px;">
            <span class="gs-edit" @auth data-edit-model="projects" data-edit-id="{{ $project->id }}" data-edit-field="title" @endauth>{{ $project->tr('title') }}</span>
            @if (filled($project->url))
                <svg class="gs-proj-arrow" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;"><path d="M7 17L17 7M9 7h8v8"/></svg>
            @endif
        </h3>
    </div>
</{{ $tag }}>
