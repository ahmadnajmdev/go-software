<!-- ===== PROJECTS ===== -->
<section id="projects" style="background: #f5f8f8; padding: 94px 0;">
  <div style="max-width: 1240px; margin: 0 auto; padding: 0 24px;">
    <div style="display: flex; align-items: flex-end; justify-content: space-between; gap: 24px; margin-bottom: 48px; flex-wrap: wrap;">
      <div style="max-width: 560px;">
        <div style="display: flex; align-items: center; gap: 11px; margin-bottom: 14px;"><span style="width: 30px; height: 2px; background: var(--gs-accent, #2CA69C);"></span><span style="color: var(--gs-accent, #2CA69C); font-family: 'Space Grotesk'; font-weight: 600; letter-spacing: .16em; font-size: 13px;"><x-t k="projTag"/></span></div>
        <h2 style="font-family: 'Space Grotesk'; font-weight: 700; font-size: clamp(30px, 3.6vw, 45px); line-height: 1.14; color: #0d1826; letter-spacing: -.02em;"><x-t k="projTitle"/></h2>
      </div>
      <a href="{{ route('home') }}#contact" class="hov-accent-solid" style="background: #0d1826; color: #fff; font-family: 'Space Grotesk'; font-weight: 600; padding: 14px 28px; border-radius: var(--gs-r-btn, 10px); display: inline-flex; align-items: center; gap: 9px; transition: .25s;"><x-t k="allProjects"/> <svg class="gs-flip" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
    </div>
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;" class="gs-4col">
      @foreach ($projects as $i => $project)
        <div class="gs-proj" data-item-id="{{ $project->id }}" data-item-model="projects" style="position: relative; border-radius: var(--gs-r-card, 16px); overflow: hidden; height: 380px;">
          <div style="width: 100%; height: 100%; background: #dfe7e7 center/cover no-repeat; background-image: url('{{ media_url($project->image) }}');" @auth data-edit-image data-image-field="image" @endauth></div>
          @auth<button type="button" class="gs-edit-x" data-edit-delete title="Delete project">✕</button>@endauth
          <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(13,24,38,.92) 8%, transparent 58%);"></div>
          <div style="position: absolute; bottom: 0; left: 0; right: 0; padding: 24px;">
            <span style="color: var(--gs-accent-lite, #6FDED3); font-family: 'Space Grotesk'; font-weight: 600; font-size: 12px; letter-spacing: .12em;"><span class="gs-edit" @auth data-edit-model="projects" data-edit-id="{{ $project->id }}" data-edit-field="category" @endauth>{{ $project->tr('category') }}</span></span>
            <h3 style="font-family: 'Space Grotesk'; font-weight: 600; font-size: 19px; color: #fff; margin-top: 6px;"><span class="gs-edit" @auth data-edit-model="projects" data-edit-id="{{ $project->id }}" data-edit-field="title" @endauth>{{ $project->tr('title') }}</span></h3>
          </div>
        </div>
      @endforeach
      @auth
        <button type="button" class="gs-add-tile" data-edit-add="projects" style="min-height: 220px; border: 2px dashed #b9c6c9; border-radius: var(--gs-r-card, 16px); background: transparent; color: #6a7a8a; font-family: 'Space Grotesk'; font-weight: 600; font-size: 15px; cursor: pointer; align-items: center; justify-content: center; gap: 8px;">＋ Add project</button>
      @endauth
    </div>
  </div>
</section>
