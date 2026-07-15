@php($home = route('home'))
<!-- ===== HEADER + MOBILE MENU ===== -->
{{-- display:contents so this Alpine wrapper doesn't become the sticky header's containing block --}}
<div x-data="{ open: false }" style="display: contents;">
  <header style="position: sticky; top: 0; z-index: 50; background: rgba(255,255,255,.9); backdrop-filter: blur(12px); border-bottom: 1px solid #eef1f2;">
    <div style="max-width: 1240px; margin: 0 auto; padding: 15px 24px; display: flex; align-items: center; justify-content: space-between; gap: 24px;">
      <a href="{{ $home }}#home" style="display: flex; align-items: center;"><img src="{{ media_url(gs_setting('logo.dark', 'images/logo-dark.png')) }}" alt="GoSoftware" style="height: 38px; width: auto; display: block;"></a>
      <nav style="display: flex; align-items: center; gap: 32px;" class="gs-desktop-nav">
        @foreach (['home' => 'navHome', 'about' => 'navAbout', 'services' => 'navServices', 'projects' => 'navProjects', 'contact' => 'navContact'] as $anchor => $key)
          @if ($anchor === 'home' || gs_section_visible($anchor) || auth()->check())
            <a href="{{ $home }}#{{ $anchor }}" class="hov-accent-text" style="font-family: 'Space Grotesk'; font-weight: 500; font-size: 15px; color: #0d1826;"><x-t :k="$key"/></a>
          @endif
        @endforeach
      </nav>
      <div style="display: flex; align-items: center; gap: 14px;">
        <a href="{{ $home }}#contact" class="gs-header-cta hov-accent-solid" style="background: #0d1826; color: #fff; font-family: 'Space Grotesk'; font-weight: 600; font-size: 15px; padding: 12px 24px; border-radius: var(--gs-r-btn, 10px); display: inline-flex; align-items: center; gap: 9px; transition: .25s;"><x-t k="getQuote"/> <svg class="gs-flip" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
        <button @click="open = true" class="gs-burger" style="display: none; background: #0d1826; color: #fff; border: none; width: 46px; height: 46px; border-radius: var(--gs-r-btn, 10px); cursor: pointer; align-items: center; justify-content: center;"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg></button>
      </div>
    </div>
  </header>

  <div class="gs-menu-overlay" :class="{ open }">
    <div @click="open = false" style="position: absolute; inset: 0; background: rgba(13,24,38,.55);"></div>
    <div class="gs-drawer">
      <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
        <img src="{{ media_url(gs_setting('logo.dark', 'images/logo-dark.png')) }}" alt="GoSoftware" style="height: 32px;">
        <button @click="open = false" style="background: #f2f5f5; border: none; width: 40px; height: 40px; border-radius: var(--gs-r-btn, 10px); cursor: pointer; color: #0d1826; display: grid; place-items: center;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg></button>
      </div>
      @foreach (['home' => 'navHome', 'about' => 'navAbout', 'services' => 'navServices', 'projects' => 'navProjects', 'contact' => 'navContact'] as $anchor => $key)
        @if ($anchor === 'home' || gs_section_visible($anchor) || auth()->check())
          <a href="{{ $home }}#{{ $anchor }}" @click="open = false" style="padding: 13px 6px; border-bottom: 1px solid #eef1f2; font-family: 'Space Grotesk'; font-weight: 500; color: #0d1826;"><x-t :k="$key"/></a>
        @endif
      @endforeach
      <a href="{{ $home }}#contact" @click="open = false" style="margin-top: 16px; background: var(--gs-accent, #2CA69C); color: #fff; text-align: center; font-family: 'Space Grotesk'; font-weight: 600; padding: 14px; border-radius: var(--gs-r-btn, 10px);"><x-t k="getQuote"/></a>
    </div>
  </div>
</div>
