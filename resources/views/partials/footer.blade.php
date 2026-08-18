@php($home = route('home'))
<!-- ===== FOOTER ===== -->
<footer style="background: #0a1420; color: #a3b0bd; padding: 70px 0 0;">
  <div style="max-width: 1240px; margin: 0 auto; padding: 0 24px; display: grid; grid-template-columns: 1.6fr 1fr 1fr; gap: 40px;" class="gs-footer-grid">
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
        @foreach (\App\Support\ServiceCatalogue::published() as $footerService)
          <a href="{{ gs_route('services/'.$footerService->slug) }}" class="hov-accent-text" style="color: #a3b0bd;">{{ \App\Support\ServiceCatalogue::page($footerService->slug)['name'] }}</a>
        @endforeach
      </div>
    </div>
  </div>
  <div style="max-width: 1240px; margin: 46px auto 0; padding: 24px; border-top: 1px solid rgba(255,255,255,.08); display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; font-size: 14px;">
    <div style="display: flex; flex-direction: column; gap: 4px;">
      <span><x-t k="copyright"/></span>
      {{-- A registered name and number is the cheapest trust signal there is.
           Hidden until config/company.php has them — see BLOCKED.md. --}}
      @if (config('company.legal_name'))
        <span style="font-size: 12.5px; color: #7b8794;">
          <x-t k="ftRegistered"/>: {{ config('company.legal_name') }}@if (config('company.registration_number')) · <x-t k="ftRegNo"/> {{ config('company.registration_number') }}@endif
        </span>
      @endif
    </div>
    <div style="display: flex; gap: 24px;"><a href="{{ gs_route('privacy-policy') }}" class="hov-accent-text" style="color: #a3b0bd;"><x-t k="privacy"/></a><a href="{{ gs_route('terms-of-service') }}" class="hov-accent-text" style="color: #a3b0bd;"><x-t k="terms"/></a></div>
  </div>
  <div style="height: 20px;"></div>
</footer>
