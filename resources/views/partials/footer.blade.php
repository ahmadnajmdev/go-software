@php($home = route('home'))
<!-- ===== FOOTER ===== -->
<footer style="background: #0a1420; color: #a3b0bd; padding: 70px 0 0;">
  <div style="max-width: 1240px; margin: 0 auto; padding: 0 24px; display: grid; grid-template-columns: 1.4fr 1fr 1fr 1.2fr; gap: 40px;" class="gs-footer-grid">
    <div>
      <img src="{{ media_url(gs_setting('logo.light', 'images/logo-light.png')) }}" alt="GoSoftware" style="height: 38px; margin-bottom: 20px;">
      <p style="font-size: 15px; line-height: 1.7; margin-bottom: 22px; max-width: 300px;"><x-t k="ftBlurb"/></p>
      @php($social = \App\Support\Social::company())
      @if ($social)
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
          <x-social-links :links="$social" :size="40" color="#a3b0bd" :icon-size="16"/>
        </div>
      @endif
    </div>
    <div>
      <h4 style="font-family: 'Space Grotesk'; font-weight: 600; font-size: 17px; color: #fff; margin-bottom: 20px;"><x-t k="ftCompany"/></h4>
      <div style="display: flex; flex-direction: column; gap: 13px; font-size: 15px;">
        @if (gs_section_visible('about') || auth()->check())
          <a href="{{ $home }}#about" class="hov-accent-text" style="color: #a3b0bd;"><x-t k="ftAboutUs"/></a>
        @endif
        @if (gs_section_visible('founder') || auth()->check())
          <a href="{{ $home }}#founder" class="hov-accent-text" style="color: #a3b0bd;"><x-t k="ftFounder"/></a>
        @endif
        @if (gs_section_visible('contact') || auth()->check())
          <a href="{{ $home }}#contact" class="hov-accent-text" style="color: #a3b0bd;"><x-t k="ftContactUs"/></a>
        @endif
      </div>
    </div>
    <div>
      <h4 style="font-family: 'Space Grotesk'; font-weight: 600; font-size: 17px; color: #fff; margin-bottom: 20px;"><x-t k="ftServices"/></h4>
      <div style="display: flex; flex-direction: column; gap: 13px; font-size: 15px;">
        @if (gs_section_visible('services') || auth()->check())
          <a href="{{ $home }}#services" class="hov-accent-text" style="color: #a3b0bd;"><x-t k="webDev"/></a>
        @endif
        @if (gs_section_visible('services') || auth()->check())
          <a href="{{ $home }}#services" class="hov-accent-text" style="color: #a3b0bd;"><x-t k="ftWebApps"/></a>
        @endif
        @if (gs_section_visible('services') || auth()->check())
          <a href="{{ $home }}#services" class="hov-accent-text" style="color: #a3b0bd;"><x-t k="mobileApps"/></a>
        @endif
        @if (gs_section_visible('services') || auth()->check())
          <a href="{{ $home }}#services" class="hov-accent-text" style="color: #a3b0bd;"><x-t k="mgmtSystems"/></a>
        @endif
        @if (gs_section_visible('services') || auth()->check())
          <a href="{{ $home }}#services" class="hov-accent-text" style="color: #a3b0bd;"><x-t k="ftSupport"/></a>
        @endif
      </div>
    </div>
    <div>
      <h4 style="font-family: 'Space Grotesk'; font-weight: 600; font-size: 17px; color: #fff; margin-bottom: 20px;"><x-t k="ftNews"/></h4>
      <p style="font-size: 15px; line-height: 1.6; margin-bottom: 16px;"><x-t k="ftNewsBody"/></p>
      <div style="display: flex; background: rgba(255,255,255,.06); border-radius: 40px; padding: 5px 5px 5px 18px;">
        <input placeholder="{{ t('phYourEmail') }}" style="flex: 1; background: transparent; border: none; outline: none; color: #fff; font-family: 'DM Sans'; font-size: 14px; min-width: 0;">
        <button style="background: var(--gs-accent, #2CA69C); color: #fff; border: none; width: 44px; height: 44px; border-radius: 50%; cursor: pointer; display: grid; place-items: center; flex-shrink: 0;"><svg class="gs-flip" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></button>
      </div>
    </div>
  </div>
  <div style="max-width: 1240px; margin: 46px auto 0; padding: 24px; border-top: 1px solid rgba(255,255,255,.08); display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; font-size: 14px;">
    <span><x-t k="copyright"/></span>
    <div style="display: flex; gap: 24px;"><a href="{{ gs_route('privacy-policy') }}" class="hov-accent-text" style="color: #a3b0bd;"><x-t k="privacy"/></a><a href="{{ gs_route('terms-of-service') }}" class="hov-accent-text" style="color: #a3b0bd;"><x-t k="terms"/></a></div>
  </div>
  <div style="height: 20px;"></div>
</footer>
