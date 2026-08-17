{{-- One project card. Shared by the home section and the archive page. --}}
@php($src = media_url($project->image))
<div class="gs-proj" data-item-id="{{ $project->id }}" data-item-model="projects"
     x-show="cat === 'all' || cat === '{{ $project->category?->slug ?? '' }}'" x-transition.opacity
     style="position: relative; border-radius: var(--gs-r-card, 16px); overflow: hidden; height: 380px;">
    @if (($project->fit ?? 'cover') === 'contain')
        {{-- Whole image, never cropped, over a blurred copy of itself. --}}
        <div class="gs-proj-media" @auth data-edit-image data-image-field="image" @endauth>
            <div class="gs-proj-blur" style="background-image: url('{{ $src }}');"></div>
            <div class="gs-proj-fit" style="background-image: url('{{ $src }}');"></div>
        </div>
    @else
        <div style="width: 100%; height: 100%; background: #dfe7e7 center/cover no-repeat; background-image: url('{{ $src }}');" @auth data-edit-image data-image-field="image" @endauth></div>
    @endif

    @auth<button type="button" class="gs-edit-x" data-edit-delete title="Delete project">✕</button>@endauth

    <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(13,24,38,.92) 8%, transparent 58%);"></div>

    <div style="position: absolute; bottom: 0; left: 0; right: 0; padding: 24px;">
        @if ($project->category)
            <span style="color: var(--gs-accent-lite, #6FDED3); font-family: 'Space Grotesk'; font-weight: 600; font-size: 12px; letter-spacing: .12em;">{{ $project->category->tr('name') }}</span>
        @endif
        <h3 style="font-family: 'Space Grotesk'; font-weight: 600; font-size: 19px; color: #fff; margin-top: 6px;"><span class="gs-edit" @auth data-edit-model="projects" data-edit-id="{{ $project->id }}" data-edit-field="title" @endauth>{{ $project->tr('title') }}</span></h3>
    </div>
</div>
