@php($locale = app()->getLocale())
<!-- ===== TOP BAR ===== -->
<div style="background: #0d1826; color: #aeb9c4; font-size: 13.5px;">
  <div style="max-width: 1240px; margin: 0 auto; padding: 10px 24px; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;">
    <div style="display: flex; align-items: center; gap: 26px; flex-wrap: wrap;">
      <span style="display: flex; align-items: center; gap: 8px;"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" style="color:var(--gs-accent, #2CA69C);" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s7-6 7-11a7 7 0 10-14 0c0 5 7 11 7 11z"/><circle cx="12" cy="10" r="2.4"/></svg> <x-t k="location"/></span>
      <span style="display: flex; align-items: center; gap: 8px;"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" style="color:var(--gs-accent, #2CA69C);" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M4 7l8 6 8-6"/></svg> {{ gs_setting('contact.email', 'info@gosoftware.krd') }}</span>
    </div>
    <div style="display: flex; align-items: center; gap: 10px;">
      <div style="display: flex; align-items: center; gap: 2px; background: rgba(255,255,255,.08); border-radius: var(--gs-r-sm, 8px); padding: 3px; margin-inline-end: 10px;">
        @foreach (['en' => 'EN', 'ar' => 'عربي', 'ckb' => 'کوردی'] as $code => $label)
          <a href="{{ $code === 'en' ? url('/') : url('/'.$code) }}" style="font-family: 'Space Grotesk'; font-size: 12px; font-weight: 600; padding: 4px 11px; border-radius: var(--gs-r-sm, 6px); background: {{ $locale === $code ? 'var(--gs-accent, #2CA69C)' : 'transparent' }}; color: {{ $locale === $code ? '#fff' : '#aeb9c4' }}; transition: .2s;">{{ $label }}</a>
        @endforeach
      </div>
      <div class="gs-topbar-social" style="display: flex; align-items: center; gap: 10px;">
      <span style="color: #75828f; margin-inline-end: 4px;"><x-t k="followUs"/></span>
      <a href="{{ gs_setting('social.facebook', '#') }}" class="hov-accent-bg" style="width: 30px; height: 30px; border-radius: var(--gs-r-sm, 8px); background: rgba(255,255,255,.06); display: grid; place-items: center; color: #aeb9c4; transition: .2s;"><svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M13.5 21v-8h2.5l.4-3h-2.9V8.1c0-.9.3-1.5 1.6-1.5H16.5V3.9c-.3 0-1.2-.1-2.3-.1-2.3 0-3.8 1.4-3.8 3.9V10H8v3h2.4v8h3.1z"/></svg></a>
      <a href="{{ gs_setting('social.linkedin', '#') }}" class="hov-accent-bg" style="width: 30px; height: 30px; border-radius: var(--gs-r-sm, 8px); background: rgba(255,255,255,.06); display: grid; place-items: center; color: #aeb9c4; transition: .2s;"><svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M6.9 8.5H4V20h2.9V8.5zM5.4 4a1.7 1.7 0 100 3.4 1.7 1.7 0 000-3.4zM20 20v-6.3c0-3.1-1.7-4.6-3.9-4.6-1.8 0-2.6 1-3 1.7V8.5H10V20h2.9v-6c0-1.4.8-2.1 1.8-2.1s1.7.7 1.7 2.1V20H20z"/></svg></a>
      <a href="{{ gs_setting('social.x', '#') }}" class="hov-accent-bg" style="width: 30px; height: 30px; border-radius: var(--gs-r-sm, 8px); background: rgba(255,255,255,.06); display: grid; place-items: center; color: #aeb9c4; transition: .2s;"><svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M17.5 3h3l-6.6 7.5L21.7 21h-6l-4.7-6.1L5.6 21H2.5l7-8L2 3h6.1l4.3 5.6L17.5 3zm-1 16h1.7L7.6 4.7H5.8L16.5 19z"/></svg></a>
      <a href="{{ gs_setting('social.youtube', '#') }}" class="hov-accent-bg" style="width: 30px; height: 30px; border-radius: var(--gs-r-sm, 8px); background: rgba(255,255,255,.06); display: grid; place-items: center; color: #aeb9c4; transition: .2s;"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M22 8.2s-.2-1.5-.8-2.1c-.8-.8-1.6-.8-2-.9C16.3 5 12 5 12 5s-4.3 0-7.2.2c-.4.1-1.2.1-2 .9C2.2 6.7 2 8.2 2 8.2S1.8 9.9 1.8 11.6v1.6C1.8 15 2 16.6 2 16.6s.2 1.5.8 2.1c.8.8 1.8.8 2.3.9 1.7.2 6.9.2 6.9.2s4.3 0 7.2-.2c.4-.1 1.2-.1 2-.9.6-.6.8-2.1.8-2.1s.2-1.6.2-3.3v-1.6c0-1.7-.2-3.4-.2-3.4zM9.9 14.6V9.2l4.5 2.7-4.5 2.7z"/></svg></a>
      </div>
    </div>
  </div>
</div>
