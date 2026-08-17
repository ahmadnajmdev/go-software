<!-- ===== PROJECTS ===== -->
<section id="projects" style="background: #f5f8f8; padding: 94px 0;" x-data="{ cat: 'all' }">
  <div style="max-width: 1240px; margin: 0 auto; padding: 0 24px;">
    <div style="display: flex; align-items: flex-end; justify-content: space-between; gap: 24px; margin-bottom: 26px; flex-wrap: wrap;">
      <div style="max-width: 560px;">
        <div style="display: flex; align-items: center; gap: 11px; margin-bottom: 14px;"><span style="width: 30px; height: 2px; background: var(--gs-accent, #2CA69C);"></span><span style="color: var(--gs-accent, #2CA69C); font-family: 'Space Grotesk'; font-weight: 600; letter-spacing: .16em; font-size: 13px;"><x-t k="projTag"/></span></div>
        <h2 style="font-family: 'Space Grotesk'; font-weight: 700; font-size: clamp(30px, 3.6vw, 45px); line-height: 1.14; color: #0d1826; letter-spacing: -.02em;"><x-t k="projTitle"/></h2>
      </div>
      <a href="{{ gs_route('projects') }}" class="hov-accent-solid" style="background: #0d1826; color: #fff; font-family: 'Space Grotesk'; font-weight: 600; padding: 14px 28px; border-radius: var(--gs-r-btn, 10px); display: inline-flex; align-items: center; gap: 9px; transition: .25s;"><x-t k="allProjects"/> <svg class="gs-flip" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
    </div>

    @include('partials.category-chips', ['categories' => $categories])

    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;" class="gs-4col">
      @foreach ($projects as $project)
        @include('partials.project-tile', ['project' => $project])
      @endforeach
      @auth
        <button type="button" class="gs-add-tile" data-edit-add="projects" style="min-height: 220px; border: 2px dashed #b9c6c9; border-radius: var(--gs-r-card, 16px); background: transparent; color: #6a7a8a; font-family: 'Space Grotesk'; font-weight: 600; font-size: 15px; cursor: pointer; align-items: center; justify-content: center; gap: 8px;">＋ Add project</button>
      @endauth
    </div>
  </div>
</section>
