@php
    $home = route('home');
    $navLinks = ['home' => 'navHome', 'about' => 'navAbout', 'services' => 'navServices', 'projects' => 'navProjects', 'contact' => 'navContact'];
@endphp
<!-- ===== HEADER ===== -->
{{-- display:contents so this Alpine wrapper doesn't become the sticky header's
     containing block --}}
<div x-data="{ open: false }" @keydown.escape.window="open = false" style="display: contents;">
  <header style="position: sticky; top: 0; z-index: 50; background: rgba(255,255,255,.9); backdrop-filter: blur(12px); border-bottom: 1px solid #eef1f2;">
    <div style="max-width: 1240px; margin: 0 auto; padding: 15px 24px; display: flex; align-items: center; justify-content: space-between; gap: 24px;">

      {{-- One logo. It used to render twice — once here and once inside the
           mobile drawer. --}}
      <a href="{{ $home }}#home" style="display: flex; align-items: center;">
        {{-- 4000×1000 → 152×38. Both dimensions declared so the header
             reserves the space and does not shift when the logo lands. --}}
        <img src="{{ media_url(gs_setting('logo.dark', 'images/logo-dark.png')) }}" alt="GoSoftware"
             width="152" height="38" fetchpriority="high" decoding="async"
             style="height: 38px; width: auto; display: block;">
      </a>

      {{-- One nav. The same element is a horizontal bar on desktop and the
           off-canvas drawer on phones — the links used to be written out twice
           and shipped to every visitor twice. --}}
      {{-- The shell is `display: contents` on desktop, so the nav sits in the
           header flex as if it were not there. On phones it becomes a fixed,
           viewport-sized clipping box, which is what stops the off-canvas
           drawer adding its own width to the document's scrollable area. --}}
      <div class="gs-nav-shell">
      <nav id="gs-nav" class="gs-nav" :class="{ 'is-open': open }" aria-label="Main">
        <button type="button" class="gs-nav-close" @click="open = false" aria-label="Close menu">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"/></svg>
        </button>

        @foreach ($navLinks as $anchor => $key)
          @if ($anchor === 'home' || gs_section_visible($anchor) || auth()->check())
            <a href="{{ $home }}#{{ $anchor }}" class="gs-nav-link hov-accent-text" @click="open = false"><x-t :k="$key"/></a>
          @endif
        @endforeach

        <x-cta-primary location="nav" tone="dark" size="sm" class="gs-nav-cta" @click="open = false"/>
      </nav>
      </div>

      <button type="button" @click="open = true" class="gs-burger" aria-label="Open menu"
              :aria-expanded="open" aria-controls="gs-nav"
              style="display: none; background: #0d1826; color: #fff; border: none; width: 46px; height: 46px; border-radius: var(--gs-r-btn, 10px); cursor: pointer; align-items: center; justify-content: center;">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
      </button>
    </div>
  </header>

  {{-- Backdrop for the drawer state. --}}
  <div class="gs-nav-scrim" :class="{ 'is-open': open }" @click="open = false" aria-hidden="true"></div>
</div>
