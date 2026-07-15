<!-- ===== ABOUT ===== -->
<section id="about" style="background: #fff; padding: 44px 0 100px;">
  <div style="max-width: 1240px; margin: 0 auto; padding: 0 24px; display: grid; grid-template-columns: 1fr 1fr; gap: 64px; align-items: center;" class="gs-2col">
    <div style="position: relative;">
      <div style="border-radius: var(--gs-r-card, 18px); overflow: hidden;">
        <img src="{{ media_url(gs_setting('images.about_main')) }}" alt="GoSoftware office" @auth data-edit-image data-image-setting="images.about_main" @endauth style="width: 100%; height: 460px; object-fit: cover; display: block;">
      </div>
      <div style="position: absolute; bottom: -28px; right: -14px; width: 210px; border-radius: var(--gs-r-tile, 14px); overflow: hidden; border: 6px solid #fff; box-shadow: 0 24px 50px rgba(13,24,38,.18);">
        <img src="{{ media_url(gs_setting('images.about_inset')) }}" alt="Coding" @auth data-edit-image data-image-setting="images.about_inset" @endauth style="width: 100%; height: 150px; object-fit: cover; display: block;">
      </div>
      <div style="position: absolute; top: 22px; left: 22px; background: #0d1826; color: #fff; border-radius: var(--gs-r-tile, 12px); padding: 15px 20px; text-align: center;">
        <div style="font-family: 'Space Grotesk'; font-weight: 700; font-size: 32px; color: var(--gs-accent-lite, #6FDED3); line-height: 1;">15+</div>
        <div style="font-size: 12px; margin-top: 4px; color: #a3b0bd;"><x-t k="yearsIn"/></div>
      </div>
    </div>
    <div>
      <div style="display: flex; align-items: center; gap: 11px; margin-bottom: 16px;"><span style="width: 30px; height: 2px; background: var(--gs-accent, #2CA69C);"></span><span style="color: var(--gs-accent, #2CA69C); font-family: 'Space Grotesk'; font-weight: 600; letter-spacing: .16em; font-size: 13px;"><x-t k="aboutTag"/></span></div>
      <h2 style="font-family: 'Space Grotesk'; font-weight: 700; font-size: clamp(30px, 3.6vw, 45px); line-height: 1.12; color: #0d1826; margin-bottom: 20px; letter-spacing: -.02em;"><x-t k="aboutTitle"/></h2>
      <p style="font-size: 16px; line-height: 1.75; color: #5a6a7a; margin-bottom: 26px;"><x-t k="aboutBody"/></p>
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px 28px; margin-bottom: 32px;">
        <div style="display: flex; gap: 12px; align-items: flex-start;"><svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:1px;color:var(--gs-accent, #2CA69C);"><path d="M20 6L9 17l-5-5"/></svg><div><strong style="color: #0d1826; font-family: 'Space Grotesk';"><x-t k="ab1T"/></strong><div style="font-size: 14px; color: #6a7a8a;"><x-t k="ab1D"/></div></div></div>
        <div style="display: flex; gap: 12px; align-items: flex-start;"><svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:1px;color:var(--gs-accent, #2CA69C);"><path d="M20 6L9 17l-5-5"/></svg><div><strong style="color: #0d1826; font-family: 'Space Grotesk';"><x-t k="ab2T"/></strong><div style="font-size: 14px; color: #6a7a8a;"><x-t k="ab2D"/></div></div></div>
        <div style="display: flex; gap: 12px; align-items: flex-start;"><svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:1px;color:var(--gs-accent, #2CA69C);"><path d="M20 6L9 17l-5-5"/></svg><div><strong style="color: #0d1826; font-family: 'Space Grotesk';"><x-t k="ab3T"/></strong><div style="font-size: 14px; color: #6a7a8a;"><x-t k="ab3D"/></div></div></div>
        <div style="display: flex; gap: 12px; align-items: flex-start;"><svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:1px;color:var(--gs-accent, #2CA69C);"><path d="M20 6L9 17l-5-5"/></svg><div><strong style="color: #0d1826; font-family: 'Space Grotesk';"><x-t k="ab4T"/></strong><div style="font-size: 14px; color: #6a7a8a;"><x-t k="ab4D"/></div></div></div>
      </div>
      <div style="display: flex; align-items: center; gap: 24px; flex-wrap: wrap;">
        <a href="{{ route('home') }}#contact" class="hov-accent-solid" style="background: #0d1826; color: #fff; font-family: 'Space Grotesk'; font-weight: 600; padding: 14px 28px; border-radius: var(--gs-r-btn, 10px); display: inline-flex; align-items: center; gap: 9px; transition: .25s;"><x-t k="aboutCta"/> <svg class="gs-flip" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
        <div style="display: flex; align-items: center; gap: 12px;">
          <img src="{{ media_url(gs_setting('images.ceo')) }}" @auth data-edit-image data-image-setting="images.ceo" @endauth style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
          <div><div style="font-family: 'Space Grotesk'; font-weight: 700; color: #0d1826;">{{ gs_setting('about.ceo_name') }}</div><div style="font-size: 13px; color: #6a7a8a;"><x-t k="ceoRole"/></div></div>
        </div>
      </div>
    </div>
  </div>
</section>
