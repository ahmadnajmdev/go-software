@once
    @push('scripts')
        @php
            // The step titles and the per-service prompts, handed to the Alpine
            // component so it can switch them without a round trip. Blade cannot
            // parse a nested array literal inside @json(), so it is built here.
            $gsFormStrings = [];

            foreach (['stepWhat', 'stepAbout', 'stepReach', 'stepQuote', 'fStep', 'phMsg',
                'phWebsite', 'phMobileApp', 'phMgmt', 'phPos', 'phEcom', 'phOther'] as $gsKey) {
                $gsFormStrings[$gsKey] = t($gsKey);
            }
        @endphp
        <script>window.gsStrings = {!! json_encode($gsFormStrings, JSON_UNESCAPED_UNICODE) !!};</script>
    @endpush
@endonce
@php
    use App\Support\MapEmbed;

    $address = gs_setting_tr('contact.address');

    // A pasted Google "Embed a map" snippet wins; otherwise build a key-less
    // embed from the coordinates. Either one is enough to show the map.
    $custom = MapEmbed::custom(gs_setting('contact.map_embed'));
    $lat = gs_setting('contact.map_lat');
    $lng = gs_setting('contact.map_lng');
    $hasCoords = is_numeric($lat) && is_numeric($lng);
    // an address alone is enough — Google's `q` resolves a place name too
    $hasMap = $custom || $hasCoords || filled($address);

    if ($hasMap) {
        $zoom = min(21, max(3, (int) gs_setting('contact.map_zoom', 16)));
        $hl = MapEmbed::locale(app()->getLocale());

        $embed = match (true) {
            (bool) $custom => $custom,
            $hasCoords => MapEmbed::forCoordinates((float) $lat, (float) $lng, $zoom, $hl),
            default => MapEmbed::forQuery($address, $zoom, $hl),
        };

        $directions = $hasCoords
            ? MapEmbed::directions((float) $lat, (float) $lng)
            : MapEmbed::search($address ?: 'GoSoftware');
    }
@endphp
<!-- ===== CONTACT ===== -->
<section id="contact" style="background: var(--gs-deep-bg, #0D1826); color: var(--gs-deep-fg, #FFFFFF); padding: 94px 0; position: relative; overflow: hidden;">
  <div style="position: absolute; inset: 0; background-image: linear-gradient(var(--gs-deep-grid, rgba(255,255,255,.025)) 1px, transparent 1px), linear-gradient(90deg, var(--gs-deep-grid, rgba(255,255,255,.025)) 1px, transparent 1px); background-size: 54px 54px; mask-image: radial-gradient(ellipse 60% 80% at 80% 50%, #000 30%, transparent 100%);"></div>
  <div style="max-width: 1240px; margin: 0 auto; padding: 0 24px; display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center; position: relative;" class="gs-2col">
    <div>
      <div style="display: flex; align-items: center; gap: 11px; margin-bottom: 16px;"><span style="width: 30px; height: 2px; background: var(--gs-accent, #2CA69C);"></span><span style="color: var(--gs-accent-lite, #6FDED3); font-family: 'Space Grotesk'; font-weight: 600; letter-spacing: .16em; font-size: 13px;"><x-t k="ctTag"/></span></div>
      <h2 style="font-family: 'Space Grotesk'; font-weight: 700; font-size: clamp(30px, 3.6vw, 45px); line-height: 1.14; margin-bottom: 20px; letter-spacing: -.02em;"><x-t k="ctTitle"/></h2>
      <p style="font-size: 16px; line-height: 1.7; color: var(--gs-deep-muted, #A9B6C3); margin-bottom: 34px; max-width: 460px;"><x-t k="ctBody"/></p>
      <div style="display: flex; flex-direction: column; gap: 20px;">
        <div style="display: flex; align-items: center; gap: 16px;">
          <div style="width: 52px; height: 52px; border-radius: var(--gs-r-tile, 12px); background: color-mix(in srgb, var(--gs-accent, #2CA69C) 16%, transparent); color: var(--gs-accent-lite, #6FDED3); display: grid; place-items: center;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M5 4h3l2 5-2 1.5a11 11 0 005 5L15 13l5 2v3a2 2 0 01-2 2A15 15 0 013 6a2 2 0 012-2z"/></svg></div>
          <div><div style="font-size: 13px; color: var(--gs-deep-muted, #A3B0BD);"><x-t k="callUs"/></div><a href="tel:{{ gs_setting('contact.phone') }}" style="font-family: 'Space Grotesk'; font-weight: 700; font-size: 20px; color: var(--gs-deep-fg, #FFFFFF);">{{ gs_setting('contact.phone') }}</a></div>
        </div>
        <div style="display: flex; align-items: center; gap: 16px;">
          <div style="width: 52px; height: 52px; border-radius: var(--gs-r-tile, 12px); background: color-mix(in srgb, var(--gs-accent, #2CA69C) 16%, transparent); color: var(--gs-accent-lite, #6FDED3); display: grid; place-items: center;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M4 7l8 6 8-6"/></svg></div>
          <div><div style="font-size: 13px; color: var(--gs-deep-muted, #A3B0BD);"><x-t k="emailUs"/></div><a href="mailto:{{ gs_setting('contact.email') }}" style="font-family: 'Space Grotesk'; font-weight: 700; font-size: 20px; color: var(--gs-deep-fg, #FFFFFF);">{{ gs_setting('contact.email') }}</a></div>
        </div>
        @if (\App\Support\WhatsApp::isConfigured())
          <div style="display: flex; align-items: center; gap: 16px;">
            <div style="width: 52px; height: 52px; border-radius: var(--gs-r-tile, 12px); background: rgba(37,211,102,.16); color: #25D366; display: grid; place-items: center; flex-shrink: 0;"><svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2 22l5.25-1.38a9.9 9.9 0 004.79 1.22h.01c5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0012.04 2zm0 18.15h-.01a8.23 8.23 0 01-4.19-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.2 8.2 0 01-1.26-4.38c0-4.54 3.7-8.23 8.25-8.23 2.2 0 4.27.86 5.83 2.42a8.18 8.18 0 012.41 5.82c0 4.54-3.7 8.23-8.24 8.23z"/></svg></div>
            <div>
              <div style="font-size: 13px; color: var(--gs-deep-muted, #A3B0BD);">WhatsApp</div>
              <x-whatsapp-cta source="contact" variant="link" :label="t('waAsk')"
                              style="font-size: 20px; font-weight: 700; color: #fff; margin-top: 2px;"/>
            </div>
          </div>
        @endif
        @if ($address)
          <div style="display: flex; align-items: flex-start; gap: 16px;">
            <div style="width: 52px; height: 52px; flex-shrink: 0; border-radius: var(--gs-r-tile, 12px); background: color-mix(in srgb, var(--gs-accent, #2CA69C) 16%, transparent); color: var(--gs-accent-lite, #6FDED3); display: grid; place-items: center;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s7-6 7-11a7 7 0 10-14 0c0 5 7 11 7 11z"/><circle cx="12" cy="10" r="2.4"/></svg></div>
            <div>
              <div style="font-size: 13px; color: var(--gs-deep-muted, #A3B0BD);"><x-t k="visitUs"/></div>
              <address style="font-family: 'Space Grotesk'; font-weight: 700; font-size: 20px; line-height: 1.4; font-style: normal; color: var(--gs-deep-fg, #FFFFFF); margin: 0;">{{ $address }}</address>
              @if ($hasMap)
                <a href="{{ $directions }}" target="_blank" rel="noopener" class="hov-accent-text" style="display: inline-flex; align-items: center; gap: 6px; margin-top: 7px; font-size: 14px; font-weight: 600; color: var(--gs-accent-lite, #6FDED3);"><x-t k="getDirections"/> <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17L17 7M9 7h8v8"/></svg></a>
              @endif
            </div>
          </div>
        @endif

      </div>
    </div>
    @php($sent = (bool) session('contact_sent'))
    <div x-data="contactForm(@js($errors->getMessages()), @js($sent), @js(old('service')))"
         style="background: #fff; border-radius: var(--gs-r-card, 20px); padding: 34px; color: #0d1826;">

      <div x-show="submitted" @unless ($sent) x-cloak @endunless>
        <div style="background: #eefaf8; border: 1px solid #b8e6e0; border-radius: var(--gs-r-tile, 14px); padding: 30px; text-align: center;">
          <div style="width: 54px; height: 54px; border-radius: 50%; background: var(--gs-accent, #2CA69C); display: grid; place-items: center; margin: 0 auto;"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg></div>
          <h4 style="font-family: 'Space Grotesk'; font-weight: 700; font-size: 20px; margin: 12px 0 6px; color: #0d1826;"><x-t k="thanksT"/></h4>
          {-- Says what the reply will actually contain, so the wait has a shape --}
          <p style="font-size: 14.5px; line-height: 1.6; color: #4a5a6a;"><x-t k="thanksB"/></p>
          @if (\App\Support\WhatsApp::isConfigured())
            <p style="font-size: 14px; color: #4a5a6a; margin-top: 16px;"><x-t k="waPrefer"/></p>
            <x-whatsapp-cta source="form_success" variant="light" style="margin-top: 10px;"/>
          @endif
        </div>
      </div>

      <div x-show="!submitted" @if ($sent) x-cloak @endif>
        {-- Progress. Hidden without JS, where the form is simply one page. --}
        <div class="gs-steps" x-cloak>
          <template x-for="n in total" :key="n">
            <span class="gs-step-dot" :class="n <= step && 'is-done'"></span>
          </template>
          <span class="gs-step-count" x-text="stepLabel()"></span>
        </div>

        <h3 style="font-family: 'Space Grotesk'; font-weight: 700; font-size: 22px; margin-bottom: 6px;">
          <span x-text="title()" x-cloak></span>
          <span x-show="false"><x-t k="formTitle"/></span>
        </h3>
        <p style="font-size: 14px; color: #6a7a8a; margin-bottom: 20px;"><x-t k="formSub"/></p>

        <p role="alert" x-show="hasErrors()"
           style="display: {{ $errors->any() ? 'block' : 'none' }}; background: #fdecea; border: 1px solid #f5c6c2; border-radius: var(--gs-r-tile, 12px); padding: 12px 15px; margin-bottom: 16px; font-size: 14px; color: #a5281c;"><x-t k="errGeneric"/></p>

        <form action="{{ route('contact.store') }}" method="POST" data-gs-form="contact" @submit.prevent="submit($event)" style="display: flex; flex-direction: column; gap: 16px;">
          @csrf
          <input type="hidden" name="locale" value="{{ app()->getLocale() }}">
          <input type="hidden" name="source" value="{{ '/'.ltrim(request()->path(), '/') }}">
          <input type="text" name="website" value="" style="display:none" tabindex="-1" autocomplete="off" aria-hidden="true">

          {-- STEP 1 — one tap, no typing --}
          <fieldset x-show="step === 1" style="border: 0; padding: 0; margin: 0;">
            <legend style="font-family: 'Space Grotesk'; font-weight: 600; font-size: 15px; margin-bottom: 12px;"><x-t k="stepWhat"/></legend>
            <div class="gs-tiles">
              <label class="gs-tile">
                <input type="radio" name="service" value="website" x-model="service" @change="advance()" @checked(old('service') === 'website')>
                <span class="gs-tile-face">
                  <span class="gs-tile-icon" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 5h18v14H3zM3 9h18M7 7h.01M10 7h.01"/></svg></span>
                  <x-t k="svcWebsite"/>
                </span>
                <svg class="gs-tile-check" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6L9 17l-5-5"/></svg>
              </label>
              <label class="gs-tile">
                <input type="radio" name="service" value="mobile" x-model="service" @change="advance()" @checked(old('service') === 'mobile')>
                <span class="gs-tile-face">
                  <span class="gs-tile-icon" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M7 2h10a1 1 0 011 1v18a1 1 0 01-1 1H7a1 1 0 01-1-1V3a1 1 0 011-1zM11 18h2"/></svg></span>
                  <x-t k="svcMobileApp"/>
                </span>
                <svg class="gs-tile-check" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6L9 17l-5-5"/></svg>
              </label>
              <label class="gs-tile">
                <input type="radio" name="service" value="system" x-model="service" @change="advance()" @checked(old('service') === 'system')>
                <span class="gs-tile-face">
                  <span class="gs-tile-icon" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 4h7v7H3zM14 4h7v7h-7zM3 13h7v7H3zM14 13h7v7h-7z"/></svg></span>
                  <x-t k="svcMgmt"/>
                </span>
                <svg class="gs-tile-check" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6L9 17l-5-5"/></svg>
              </label>
              <label class="gs-tile">
                <input type="radio" name="service" value="pos" x-model="service" @change="advance()" @checked(old('service') === 'pos')>
                <span class="gs-tile-face">
                  <span class="gs-tile-icon" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 8h16l-1.4 11.2a2 2 0 01-2 1.8H7.4a2 2 0 01-2-1.8L4 8zm4 0V6a4 4 0 018 0v2"/></svg></span>
                  <x-t k="svcPos"/>
                </span>
                <svg class="gs-tile-check" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6L9 17l-5-5"/></svg>
              </label>
              <label class="gs-tile">
                <input type="radio" name="service" value="ecommerce" x-model="service" @change="advance()" @checked(old('service') === 'ecommerce')>
                <span class="gs-tile-face">
                  <span class="gs-tile-icon" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 4h2l2.4 11.2a2 2 0 002 1.6h7.7a2 2 0 002-1.5L21 8H6M9 21a1 1 0 100-2 1 1 0 000 2zM18 21a1 1 0 100-2 1 1 0 000 2z"/></svg></span>
                  <x-t k="svcEcom"/>
                </span>
                <svg class="gs-tile-check" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6L9 17l-5-5"/></svg>
              </label>
              <label class="gs-tile">
                <input type="radio" name="service" value="other" x-model="service" @change="advance()" @checked(old('service') === 'other')>
                <span class="gs-tile-face">
                  <span class="gs-tile-icon" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19h.01M9.1 9a3 3 0 115.8 1c0 2-2.9 2.5-2.9 4"/></svg></span>
                  <x-t k="svcOtherOpt"/>
                </span>
                <svg class="gs-tile-check" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6L9 17l-5-5"/></svg>
              </label>
            </div>
            <p id="err-service" x-show="errors.service" x-text="errors.service?.[0]"
               style="display: {{ $errors->has('service') ? 'block' : 'none' }}; margin: 6px 2px 0; font-size: 13px; line-height: 1.45; color: #c0392b;">{{ $errors->first('service') }}</p>
          </fieldset>

          {-- STEP 2 — one question, prompted by the step 1 answer --}
          <fieldset x-show="step === 2" style="border: 0; padding: 0; margin: 0;">
            <legend style="font-family: 'Space Grotesk'; font-weight: 600; font-size: 15px; margin-bottom: 12px;"><x-t k="stepAbout"/></legend>
            <textarea required name="message" rows="5" class="foc-accent"
                      placeholder="{{ t('phMsg') }}" :placeholder="prompt()"
                      aria-describedby="err-message"
                      style="width: 100%; padding: 14px 16px; border: 1px solid #e2e8e8; border-radius: var(--gs-r-btn, 10px); font-family: 'DM Sans'; font-size: 15px; outline: none; resize: vertical;">{{ old('message') }}</textarea>
            <p id="err-message" x-show="errors.message" x-text="errors.message?.[0]"
               style="display: {{ $errors->has('message') ? 'block' : 'none' }}; margin: 6px 2px 0; font-size: 13px; line-height: 1.45; color: #c0392b;">{{ $errors->first('message') }}</p>
          </fieldset>

          {-- STEP 3 --}
          <fieldset x-show="step === 3" style="border: 0; padding: 0; margin: 0; display: grid; gap: 12px;">
            <legend style="font-family: 'Space Grotesk'; font-weight: 600; font-size: 15px; margin-bottom: 12px;"><x-t k="stepReach"/></legend>
            <div>
              <input required name="name" value="{{ old('name') }}" placeholder="{{ t('phName') }}" autocomplete="name"
                     class="foc-accent" aria-describedby="err-name" style="width: 100%; padding: 14px 16px; border: 1px solid #e2e8e8; border-radius: var(--gs-r-btn, 10px); font-family: 'DM Sans'; font-size: 15px; outline: none;">
            <p id="err-name" x-show="errors.name" x-text="errors.name?.[0]"
               style="display: {{ $errors->has('name') ? 'block' : 'none' }}; margin: 6px 2px 0; font-size: 13px; line-height: 1.45; color: #c0392b;">{{ $errors->first('name') }}</p>
            </div>
            <div>
              <input required name="phone" type="tel" inputmode="tel" autocomplete="tel"
                     value="{{ old('phone', '+964') }}" placeholder="{{ t('phWhatsapp') }}"
                     class="foc-accent" aria-describedby="err-phone" style="width: 100%; padding: 14px 16px; border: 1px solid #e2e8e8; border-radius: var(--gs-r-btn, 10px); font-family: 'DM Sans'; font-size: 15px; outline: none;">
            <p id="err-phone" x-show="errors.phone" x-text="errors.phone?.[0]"
               style="display: {{ $errors->has('phone') ? 'block' : 'none' }}; margin: 6px 2px 0; font-size: 13px; line-height: 1.45; color: #c0392b;">{{ $errors->first('phone') }}</p>
            </div>
            <div>
              <input required type="email" name="email" value="{{ old('email') }}" placeholder="{{ t('phEmail') }}" autocomplete="email"
                     class="foc-accent" aria-describedby="err-email" style="width: 100%; padding: 14px 16px; border: 1px solid #e2e8e8; border-radius: var(--gs-r-btn, 10px); font-family: 'DM Sans'; font-size: 15px; outline: none;">
            <p id="err-email" x-show="errors.email" x-text="errors.email?.[0]"
               style="display: {{ $errors->has('email') ? 'block' : 'none' }}; margin: 6px 2px 0; font-size: 13px; line-height: 1.45; color: #c0392b;">{{ $errors->first('email') }}</p>
            </div>
            <div>
              <input name="company" value="{{ old('company') }}" placeholder="{{ t('phCompany') }}" autocomplete="organization"
                     class="foc-accent" aria-describedby="err-company" style="width: 100%; padding: 14px 16px; border: 1px solid #e2e8e8; border-radius: var(--gs-r-btn, 10px); font-family: 'DM Sans'; font-size: 15px; outline: none;">
            <p id="err-company" x-show="errors.company" x-text="errors.company?.[0]"
               style="display: {{ $errors->has('company') ? 'block' : 'none' }}; margin: 6px 2px 0; font-size: 13px; line-height: 1.45; color: #c0392b;">{{ $errors->first('company') }}</p>
            </div>
          </fieldset>

          {-- STEP 4 — optional, and visibly so. "Not sure yet" and "Just
               exploring" are the whole point: without them the budget question
               causes abandonment instead of producing answers. --}
          <fieldset x-show="step === 4" style="border: 0; padding: 0; margin: 0; display: grid; gap: 12px;">
            <legend style="font-family: 'Space Grotesk'; font-weight: 600; font-size: 15px; margin-bottom: 12px;"><x-t k="stepQuote"/><span class="gs-optional-badge"><x-t k="f4Optional"/></span></legend>
            <div class="gs-field-row">
              <div>
                <label for="budget" style="display: block; font-size: 13px; color: #6a7a8a; margin-bottom: 5px;"><x-t k="fBudget"/></label>
                <select id="budget" name="budget" style="width: 100%; padding: 14px 16px; border: 1px solid #e2e8e8; border-radius: var(--gs-r-btn, 10px); font-family: 'DM Sans'; font-size: 15px; outline: none; background: #fff; color: #4a5a6a;">
                  <option value=""></option>
                  <option value="under-3k" @selected(old('budget') === 'under-3k')>{{ t('bud1') }}</option>
                  <option value="3k-8k" @selected(old('budget') === '3k-8k')>{{ t('bud2') }}</option>
                  <option value="8k-20k" @selected(old('budget') === '8k-20k')>{{ t('bud3') }}</option>
                  <option value="20k-plus" @selected(old('budget') === '20k-plus')>{{ t('bud4') }}</option>
                  <option value="unsure" @selected(old('budget') === 'unsure')>{{ t('bud5') }}</option>
                </select>
              </div>
              <div>
                <label for="timeline" style="display: block; font-size: 13px; color: #6a7a8a; margin-bottom: 5px;"><x-t k="fTimeline"/></label>
                <select id="timeline" name="timeline" style="width: 100%; padding: 14px 16px; border: 1px solid #e2e8e8; border-radius: var(--gs-r-btn, 10px); font-family: 'DM Sans'; font-size: 15px; outline: none; background: #fff; color: #4a5a6a;">
                  <option value=""></option>
                  <option value="asap" @selected(old('timeline') === 'asap')>{{ t('tim1') }}</option>
                  <option value="1-3-months" @selected(old('timeline') === '1-3-months')>{{ t('tim2') }}</option>
                  <option value="3-6-months" @selected(old('timeline') === '3-6-months')>{{ t('tim3') }}</option>
                  <option value="exploring" @selected(old('timeline') === 'exploring')>{{ t('tim4') }}</option>
                </select>
              </div>
            </div>
          </fieldset>

          {-- Navigation only exists when JS does; otherwise every step is
               already on screen and the submit button is the only control. --}
          <div class="gs-form-nav" x-cloak x-show="step < total">
            <button type="button" class="gs-back" x-show="step > 1" @click="back()"><x-t k="fBack"/></button>
            <button type="button" class="gs-next" @click="advance()"><x-t k="fNext"/></button>
          </div>

          <div class="gs-form-nav" x-show="step === total">
            <button type="button" class="gs-back" x-cloak x-show="step > 1" @click="back()"><x-t k="fBack"/></button>
            <button type="submit" class="hov-dark gs-next" :disabled="sending"><x-t k="sendMsg"/> <svg class="gs-flip" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></button>
          </div>

          <p style="font-size: 13px; line-height: 1.6; color: #7b8794; text-align: center;">
            <x-t k="fReassure"/>
            @if (\App\Support\WhatsApp::isConfigured())
              <br><x-t k="waPrefer"/> <x-whatsapp-cta source="contact" variant="link" :label="t('waTalk')" style="font-size: 13px; display: inline-flex;"/>
            @endif
          </p>
        </form>
      </div>
    </div>
    @if ($hasMap)
      {{-- Click-to-load. The Google embed was loading eagerly on every page
           view and costing 434KB of an 817KB page — over half the payload for
           a map most visitors never look at. Nothing is requested from Google
           until someone actually asks for the map. --}}
      <div class="gs-map" x-data="{ loaded: false }"
           style="grid-column: 1 / -1; margin-top: 16px; border-radius: var(--gs-r-card, 20px); overflow: hidden; border: 1px solid rgba(255,255,255,.10); position: relative;">

        <template x-if="loaded">
          <iframe src="{{ $embed }}" title="{{ $address ?: 'GoSoftware office location' }}"
                  width="1200" height="360" loading="lazy"
                  referrerpolicy="no-referrer-when-downgrade" allowfullscreen
                  style="width: 100%; height: 360px; border: 0; display: block;"></iframe>
        </template>

        <div x-show="!loaded" class="gs-map-facade" style="height: 360px;">
          <svg width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="var(--gs-accent-lite, #6FDED3)" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 21s7-6 7-11a7 7 0 10-14 0c0 5 7 11 7 11z"/><circle cx="12" cy="10" r="2.4"/></svg>
          @if ($address)
            <p style="font-family: 'Space Grotesk'; font-weight: 600; font-size: 16px; color: #fff; margin: 14px 0 4px; text-align: center; max-width: 420px;">{{ $address }}</p>
          @endif
          <p style="font-size: 12.5px; color: #7d8d9c; margin-bottom: 16px;"><x-t k="mapNote"/></p>
          <div style="display: flex; gap: 10px; flex-wrap: wrap; justify-content: center;">
            <button type="button" @click="loaded = true"
                    style="background: var(--gs-accent, #2CA69C); color: #fff; border: none; font-family: 'Space Grotesk'; font-weight: 600; font-size: 15px; padding: 12px 22px; border-radius: var(--gs-r-btn, 10px); cursor: pointer;"><x-t k="mapLoad"/></button>
            <a href="{{ $directions }}" target="_blank" rel="noopener"
               style="background: rgba(255,255,255,.08); color: #fff; font-family: 'Space Grotesk'; font-weight: 600; font-size: 15px; padding: 12px 22px; border-radius: var(--gs-r-btn, 10px); display: inline-flex; align-items: center; gap: 8px;"><x-t k="getDirections"/> <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17L17 7M9 7h8v8"/></svg></a>
          </div>
        </div>
      </div>
    @endif
  </div>
</section>
