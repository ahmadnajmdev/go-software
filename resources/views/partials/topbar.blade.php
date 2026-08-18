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
      @php($social = \App\Support\Social::company())
      @if ($social)
        <div class="gs-topbar-social" style="display: flex; align-items: center; gap: 10px;">
          <span style="color: #75828f; margin-inline-end: 4px;"><x-t k="followUs"/></span>
          <x-social-links :links="$social" :size="30" radius="var(--gs-r-sm, 8px)" color="#aeb9c4" :icon-size="15"/>
        </div>
      @endif
      </div>
    </div>
  </div>
</div>
