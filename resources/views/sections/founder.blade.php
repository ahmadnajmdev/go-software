<!-- ===== FOUNDER ===== -->
<section id="founder" style="background: #fff; padding: 94px 0;">
  <div style="max-width: 1240px; margin: 0 auto; padding: 0 24px; display: grid; grid-template-columns: .85fr 1.15fr; gap: 64px; align-items: center;" class="gs-2col">
    <div style="position: relative;">
      <div style="border-radius: var(--gs-r-card, 18px); overflow: hidden;">
        <img src="{{ media_url(gs_setting('images.founder', gs_setting('images.ceo'))) }}" alt="{{ gs_setting('about.ceo_name') }}" @auth data-edit-image data-image-setting="images.founder" @endauth style="width: 100%; height: 480px; object-fit: cover; display: block;">
      </div>
      <div style="position: absolute; bottom: 22px; inset-inline-start: 22px; background: #0d1826; color: #fff; border-radius: var(--gs-r-tile, 12px); padding: 14px 20px;">
        <div style="font-family: 'Space Grotesk'; font-weight: 700; font-size: 17px;">{{ gs_setting('about.ceo_name') }}</div>
        <div style="font-size: 13px; margin-top: 3px; color: var(--gs-accent-lite, #6FDED3);"><x-t k="ceoRole"/></div>
      </div>
    </div>
    <div>
      <div style="display: flex; align-items: center; gap: 11px; margin-bottom: 16px;"><span style="width: 30px; height: 2px; background: var(--gs-accent, #2CA69C);"></span><span style="color: var(--gs-accent, #2CA69C); font-family: 'Space Grotesk'; font-weight: 600; letter-spacing: .16em; font-size: 13px;"><x-t k="founderTag"/></span></div>
      <h2 style="font-family: 'Space Grotesk'; font-weight: 700; font-size: clamp(30px, 3.6vw, 45px); line-height: 1.12; color: #0d1826; margin-bottom: 20px; letter-spacing: -.02em;"><x-t k="founderTitle"/></h2>
      <p style="font-size: 16px; line-height: 1.75; color: #5a6a7a; margin-bottom: 18px;"><x-t k="founderBio1"/></p>
      <p style="font-size: 16px; line-height: 1.75; color: #5a6a7a; margin-bottom: 28px;"><x-t k="founderBio2"/></p>
      <blockquote style="border-inline-start: 3px solid var(--gs-accent, #2CA69C); padding: 4px 0 4px; padding-inline-start: 20px; margin-bottom: 32px; font-family: 'Space Grotesk'; font-weight: 500; font-size: 18px; line-height: 1.55; color: #0d1826;"><x-t k="founderQuote"/></blockquote>
      <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
        <a href="{{ route('home') }}#contact" class="hov-accent-solid" style="background: #0d1826; color: #fff; font-family: 'Space Grotesk'; font-weight: 600; padding: 14px 28px; border-radius: var(--gs-r-btn, 10px); display: inline-flex; align-items: center; gap: 9px; transition: .25s;"><x-t k="founderCta"/> <svg class="gs-flip" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
        <div style="display: flex; gap: 10px;">
          <a href="{{ gs_setting('social.linkedin', '#') }}" class="hov-accent-bg" style="width: 44px; height: 44px; border-radius: var(--gs-r-btn, 10px); background: #f2f5f5; color: #0d1826; display: grid; place-items: center; transition: .2s;"><svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M6.9 8.5H4V20h2.9V8.5zM5.4 4a1.7 1.7 0 100 3.4 1.7 1.7 0 000-3.4zM20 20v-6.3c0-3.1-1.7-4.6-3.9-4.6-1.8 0-2.6 1-3 1.7V8.5H10V20h2.9v-6c0-1.4.8-2.1 1.8-2.1s1.7.7 1.7 2.1V20H20z"/></svg></a>
          <a href="{{ gs_setting('social.x', '#') }}" class="hov-accent-bg" style="width: 44px; height: 44px; border-radius: var(--gs-r-btn, 10px); background: #f2f5f5; color: #0d1826; display: grid; place-items: center; transition: .2s;"><svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M17.5 3h3l-6.6 7.5L21.7 21h-6l-4.7-6.1L5.6 21H2.5l7-8L2 3h6.1l4.3 5.6L17.5 3z"/></svg></a>
        </div>
      </div>
    </div>
  </div>
</section>
