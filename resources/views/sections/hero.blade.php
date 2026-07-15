<!-- ===== HERO ===== -->
<section id="home" x-data="heroCarousel()" style="position: relative; background: var(--gs-deep-bg, #0D1826); color: var(--gs-deep-fg, #FFFFFF); overflow: hidden;">
  <div style="position: absolute; inset: 0; background-image: linear-gradient(var(--gs-deep-grid, rgba(255,255,255,.03)) 1px, transparent 1px), linear-gradient(90deg, var(--gs-deep-grid, rgba(255,255,255,.03)) 1px, transparent 1px); background-size: 54px 54px; mask-image: radial-gradient(ellipse 80% 70% at 30% 40%, #000 40%, transparent 100%);"></div>
  <div style="max-width: 1240px; margin: 0 auto; padding: 80px 24px 92px; display: grid; grid-template-columns: 1.05fr .95fr; gap: 56px; align-items: center; position: relative;" class="gs-hero-grid">
    <!-- slides track -->
    <div style="overflow: hidden;">
      <div style="display: flex; width: 200%; transform: translateX(0); transition: transform .6s cubic-bezier(.65,0,.35,1);" :style="{ transform: trackTransform() }">
        <!-- slide 1 -->
        <div style="width: 50%; padding-right: 40px;">
          <div style="display: flex; align-items: center; gap: 11px; margin-bottom: 22px;"><span style="width: 30px; height: 2px; background: var(--gs-accent, #2CA69C);"></span><span style="color: var(--gs-accent-lite, #6FDED3); font-family: 'Space Grotesk'; font-weight: 600; letter-spacing: .16em; font-size: 13px;"><x-t k="h1Tag"/></span></div>
          <h1 style="font-family: 'Space Grotesk'; font-weight: 700; font-size: clamp(38px, 5vw, 64px); line-height: 1.05; margin-bottom: 20px; color: var(--gs-deep-fg, #FFFFFF); letter-spacing: -.02em;"><x-t k="h1TitleA"/> <span style="color: var(--gs-accent, #2CA69C);"><x-t k="h1TitleB"/></span></h1>
          <p style="font-size: 17px; line-height: 1.7; color: var(--gs-deep-muted, #A9B6C3); max-width: 500px;"><x-t k="h1Body"/></p>
          <div style="display: flex; align-items: center; gap: 28px; margin-top: 34px; flex-wrap: wrap;">
            <a href="{{ route('home') }}#contact" class="hov-white" style="background: var(--gs-accent, #2CA69C); color: #fff; font-family: 'Space Grotesk'; font-weight: 600; padding: 15px 30px; border-radius: var(--gs-r-btn, 10px); display: inline-flex; align-items: center; gap: 9px; transition: .25s;"><x-t k="h1Cta1"/> <svg class="gs-flip" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
            <a href="{{ route('home') }}#services" class="hov-accentlite-text" style="color: var(--gs-deep-fg, #FFFFFF); font-family: 'Space Grotesk'; font-weight: 600; display: inline-flex; align-items: center; gap: 12px;"><span style="width: 46px; height: 46px; border-radius: 50%; border: 1px solid var(--gs-deep-line, rgba(255,255,255,.22)); display: grid; place-items: center;"><svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M6 4l14 8-14 8V4z"/></svg></span> <x-t k="h1Cta2"/></a>
          </div>
        </div>
        <!-- slide 2 -->
        <div style="width: 50%; padding-right: 40px;">
          <div style="display: flex; align-items: center; gap: 11px; margin-bottom: 22px;"><span style="width: 30px; height: 2px; background: var(--gs-accent, #2CA69C);"></span><span style="color: var(--gs-accent-lite, #6FDED3); font-family: 'Space Grotesk'; font-weight: 600; letter-spacing: .16em; font-size: 13px;"><x-t k="h2Tag"/></span></div>
          <h1 style="font-family: 'Space Grotesk'; font-weight: 700; font-size: clamp(38px, 5vw, 64px); line-height: 1.05; margin-bottom: 20px; color: var(--gs-deep-fg, #FFFFFF); letter-spacing: -.02em;"><x-t k="h2TitleA"/> <span style="color: var(--gs-accent, #2CA69C);"><x-t k="h2TitleB"/></span> <x-t k="h2TitleC"/></h1>
          <p style="font-size: 17px; line-height: 1.7; color: var(--gs-deep-muted, #A9B6C3); max-width: 500px;"><x-t k="h2Body"/></p>
          <div style="display: flex; align-items: center; gap: 28px; margin-top: 34px; flex-wrap: wrap;">
            <a href="{{ route('home') }}#contact" class="hov-white" style="background: var(--gs-accent, #2CA69C); color: #fff; font-family: 'Space Grotesk'; font-weight: 600; padding: 15px 30px; border-radius: var(--gs-r-btn, 10px); display: inline-flex; align-items: center; gap: 9px; transition: .25s;"><x-t k="h2Cta1"/> <svg class="gs-flip" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
            <a href="{{ route('home') }}#projects" class="hov-accentlite-text" style="color: var(--gs-deep-fg, #FFFFFF); font-family: 'Space Grotesk'; font-weight: 600; display: inline-flex; align-items: center; gap: 12px;"><span style="width: 46px; height: 46px; border-radius: 50%; border: 1px solid var(--gs-deep-line, rgba(255,255,255,.22)); display: grid; place-items: center;"><svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M6 4l14 8-14 8V4z"/></svg></span> <x-t k="h2Cta2"/></a>
          </div>
        </div>
      </div>
      <!-- controls -->
      <div style="display: flex; align-items: center; gap: 16px; margin-top: 40px;">
        <button @click="toggle()" class="hov-accent-border" style="width: 48px; height: 48px; border-radius: var(--gs-r-btn, 10px); background: transparent; border: 1px solid var(--gs-deep-line, rgba(255,255,255,.18)); color: var(--gs-deep-fg, #FFFFFF); cursor: pointer; transition: .2s; display: grid; place-items: center;"><svg class="gs-flip" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M11 6l-6 6 6 6"/></svg></button>
        <button @click="toggle()" class="hov-accent-border" style="width: 48px; height: 48px; border-radius: var(--gs-r-btn, 10px); background: transparent; border: 1px solid var(--gs-deep-line, rgba(255,255,255,.18)); color: var(--gs-deep-fg, #FFFFFF); cursor: pointer; transition: .2s; display: grid; place-items: center;"><svg class="gs-flip" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></button>
        <span style="font-family: 'Space Grotesk'; font-weight: 600; color: var(--gs-accent-lite, #6FDED3); margin-left: 6px;"><span x-text="label()">01 / 02</span></span>
      </div>
    </div>
    <!-- hero image -->
    <div style="position: relative;">
      <div style="border-radius: var(--gs-r-card, 20px); overflow: hidden; box-shadow: 0 40px 80px rgba(0,0,0,.4); border: 1px solid rgba(255,255,255,.08);">
        <img src="{{ media_url(gs_setting('images.hero')) }}" alt="GoSoftware team" @auth data-edit-image data-image-setting="images.hero" @endauth style="width: 100%; height: 500px; object-fit: cover; display: block;">
      </div>
      <div style="position: absolute; bottom: 24px; left: -26px; background: #fff; color: #0d1826; border-radius: var(--gs-r-tile, 14px); padding: 16px 20px; box-shadow: 0 24px 50px rgba(0,0,0,.28); display: flex; align-items: center; gap: 14px; animation: gsFloat 5s ease-in-out infinite;">
        <div style="display: flex;">
          @foreach (gs_setting('images.hero_avatars') as $avatar)
            <img src="{{ media_url($avatar) }}" style="width: 38px; height: 38px; border-radius: 50%; border: 2px solid #fff; object-fit: cover;{{ $loop->first ? '' : ' margin-left: -12px;' }}">
          @endforeach
        </div>
        <div>
          <div style="font-family: 'Space Grotesk'; font-weight: 700; font-size: 19px;">300+</div>
          <div style="font-size: 12.5px; color: #6a7a8a;"><x-t k="projDelivered"/></div>
        </div>
      </div>
      <div style="position: absolute; top: -20px; right: -12px; background: var(--gs-accent, #2CA69C); color: #fff; border-radius: var(--gs-r-tile, 14px); padding: 15px 19px; box-shadow: 0 20px 40px color-mix(in srgb, var(--gs-accent, #2CA69C) 40%, transparent); animation: gsFloat 6s ease-in-out infinite;">
        <div style="font-family: 'Space Grotesk'; font-weight: 700; font-size: 21px;"><x-t k="yrsBadge"/></div>
        <div style="font-size: 12px; opacity: .9;"><x-t k="ofEng"/></div>
      </div>
    </div>
  </div>
</section>
