@php($home = route('home'))
<!-- ===== FOOTER ===== -->
<footer style="background: #0a1420; color: #a3b0bd; padding: 70px 0 0;">
  <div style="max-width: 1240px; margin: 0 auto; padding: 0 24px; display: grid; grid-template-columns: 1.4fr 1fr 1fr 1.2fr; gap: 40px;" class="gs-footer-grid">
    <div>
      <img src="{{ media_url(gs_setting('logo.light', 'images/logo-light.png')) }}" alt="GoSoftware" style="height: 38px; margin-bottom: 20px;">
      <p style="font-size: 15px; line-height: 1.7; margin-bottom: 22px; max-width: 300px;"><x-t k="ftBlurb"/></p>
      <div style="display: flex; gap: 10px;">
        <a href="{{ gs_setting('social.facebook', '#') }}" class="hov-accent-bg" style="width: 40px; height: 40px; border-radius: var(--gs-r-btn, 10px); background: rgba(255,255,255,.06); display: grid; place-items: center; color: #a3b0bd;"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M13.5 21v-8h2.5l.4-3h-2.9V8.1c0-.9.3-1.5 1.6-1.5H16.5V3.9c-.3 0-1.2-.1-2.3-.1-2.3 0-3.8 1.4-3.8 3.9V10H8v3h2.4v8h3.1z"/></svg></a>
        <a href="{{ gs_setting('social.linkedin', '#') }}" class="hov-accent-bg" style="width: 40px; height: 40px; border-radius: var(--gs-r-btn, 10px); background: rgba(255,255,255,.06); display: grid; place-items: center; color: #a3b0bd;"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M6.9 8.5H4V20h2.9V8.5zM5.4 4a1.7 1.7 0 100 3.4 1.7 1.7 0 000-3.4zM20 20v-6.3c0-3.1-1.7-4.6-3.9-4.6-1.8 0-2.6 1-3 1.7V8.5H10V20h2.9v-6c0-1.4.8-2.1 1.8-2.1s1.7.7 1.7 2.1V20H20z"/></svg></a>
        <a href="{{ gs_setting('social.x', '#') }}" class="hov-accent-bg" style="width: 40px; height: 40px; border-radius: var(--gs-r-btn, 10px); background: rgba(255,255,255,.06); display: grid; place-items: center; color: #a3b0bd;"><svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M17.5 3h3l-6.6 7.5L21.7 21h-6l-4.7-6.1L5.6 21H2.5l7-8L2 3h6.1l4.3 5.6L17.5 3z"/></svg></a>
        <a href="{{ gs_setting('social.youtube', '#') }}" class="hov-accent-bg" style="width: 40px; height: 40px; border-radius: var(--gs-r-btn, 10px); background: rgba(255,255,255,.06); display: grid; place-items: center; color: #a3b0bd;"><svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M22 8.2s-.2-1.5-.8-2.1c-.8-.8-1.6-.8-2-.9C16.3 5 12 5 12 5s-4.3 0-7.2.2c-.4.1-1.2.1-2 .9C2.2 6.7 2 8.2 2 8.2S1.8 9.9 1.8 11.6v1.6C1.8 15 2 16.6 2 16.6s.2 1.5.8 2.1c.8.8 1.8.8 2.3.9 1.7.2 6.9.2 6.9.2s4.3 0 7.2-.2c.4-.1 1.2-.1 2-.9.6-.6.8-2.1.8-2.1s.2-1.6.2-3.3v-1.6c0-1.7-.2-3.4-.2-3.4zM9.9 14.6V9.2l4.5 2.7-4.5 2.7z"/></svg></a>
      </div>
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
        <a href="#" class="hov-accent-text" style="color: #a3b0bd;"><x-t k="ftCareers"/></a>
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
    <div style="display: flex; gap: 24px;"><a href="#" class="hov-accent-text" style="color: #a3b0bd;"><x-t k="privacy"/></a><a href="#" class="hov-accent-text" style="color: #a3b0bd;"><x-t k="terms"/></a></div>
  </div>
  <div style="height: 20px;"></div>
</footer>
