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
    <div x-data="contactForm(@js($errors->getMessages()), @js($sent))" style="background: #fff; border-radius: var(--gs-r-card, 20px); padding: 38px; color: #0d1826;">
      <h3 style="font-family: 'Space Grotesk'; font-weight: 700; font-size: 24px; margin-bottom: 6px;"><x-t k="formTitle"/></h3>
      <p style="font-size: 14px; color: #6a7a8a; margin-bottom: 24px;"><x-t k="formSub"/></p>
      {{-- Without JS the server decides which panel is cloaked, so a submission
           still ends on a visible confirmation rather than a silent reload. --}}
      <div x-show="submitted" @unless($sent) x-cloak @endunless>
        <div style="background: #eefaf8; border: 1px solid #b8e6e0; border-radius: var(--gs-r-tile, 14px); padding: 30px; text-align: center;">
          <div style="width: 54px; height: 54px; border-radius: 50%; background: var(--gs-accent, #2CA69C); display: grid; place-items: center; margin: 0 auto;"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg></div>
          <h4 style="font-family: 'Space Grotesk'; font-weight: 700; font-size: 20px; margin: 12px 0 6px; color: #0d1826;"><x-t k="thanksT"/></h4>
          <p style="font-size: 14px; color: #4a5a6a;"><x-t k="thanksB"/></p>
          @if (\App\Support\WhatsApp::isConfigured())
            <p style="font-size: 14px; color: #4a5a6a; margin-top: 16px;"><x-t k="waPrefer"/></p>
            <x-whatsapp-cta source="form_success" variant="light" style="margin-top: 10px;"/>
          @endif
        </div>
      </div>
      <div x-show="!submitted" @if ($sent) x-cloak @endif>
        <p role="alert" x-show="hasErrors()"
           style="display: {{ $errors->any() ? 'block' : 'none' }}; background: #fdecea; border: 1px solid #f5c6c2; border-radius: var(--gs-r-tile, 12px); padding: 12px 15px; margin-bottom: 16px; font-size: 14px; color: #a5281c;"><x-t k="errGeneric"/></p>
        <form action="{{ route('contact.store') }}" method="POST" data-gs-form="contact" @submit.prevent="submit($event)" style="display: flex; flex-direction: column; gap: 14px;">
          @csrf
          {{-- POST /contact has no locale prefix, so the form carries the
               language and the page it was submitted from. --}}
          <input type="hidden" name="locale" value="{{ app()->getLocale() }}">
          <input type="hidden" name="source" value="{{ '/'.ltrim(request()->path(), '/') }}">
          <input type="text" name="website" value="" style="display:none" tabindex="-1" autocomplete="off" aria-hidden="true">
          <div>
            <input required name="name" value="{{ old('name') }}" placeholder="{{ t('phName') }}"
                   class="foc-accent" aria-describedby="err-name" :aria-invalid="errors.name ? 'true' : 'false'" aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}" style="width: 100%; padding: 14px 16px; border: 1px solid #e2e8e8; border-radius: var(--gs-r-btn, 10px); font-family: 'DM Sans'; font-size: 15px; outline: none;">
            <p id="err-name" x-show="errors.name" x-text="errors.name?.[0]"
               style="display: {{ $errors->has('name') ? 'block' : 'none' }}; margin: 6px 2px 0; font-size: 13px; line-height: 1.45; color: #c0392b;">{{ $errors->first('name') }}</p>
          </div>
          <div>
            <input required type="email" name="email" value="{{ old('email') }}" placeholder="{{ t('phEmail') }}"
                   class="foc-accent" aria-describedby="err-email" :aria-invalid="errors.email ? 'true' : 'false'" aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}" style="width: 100%; padding: 14px 16px; border: 1px solid #e2e8e8; border-radius: var(--gs-r-btn, 10px); font-family: 'DM Sans'; font-size: 15px; outline: none;">
            <p id="err-email" x-show="errors.email" x-text="errors.email?.[0]"
               style="display: {{ $errors->has('email') ? 'block' : 'none' }}; margin: 6px 2px 0; font-size: 13px; line-height: 1.45; color: #c0392b;">{{ $errors->first('email') }}</p>
          </div>
          <div>
            <input name="phone" value="{{ old('phone') }}" placeholder="{{ t('phPhone') }}" inputmode="tel"
                   class="foc-accent" aria-describedby="err-phone" :aria-invalid="errors.phone ? 'true' : 'false'" aria-invalid="{{ $errors->has('phone') ? 'true' : 'false' }}" style="width: 100%; padding: 14px 16px; border: 1px solid #e2e8e8; border-radius: var(--gs-r-btn, 10px); font-family: 'DM Sans'; font-size: 15px; outline: none;">
            <p id="err-phone" x-show="errors.phone" x-text="errors.phone?.[0]"
               style="display: {{ $errors->has('phone') ? 'block' : 'none' }}; margin: 6px 2px 0; font-size: 13px; line-height: 1.45; color: #c0392b;">{{ $errors->first('phone') }}</p>
          </div>
          <div>
            <select required name="service" aria-describedby="err-service" :aria-invalid="errors.service ? 'true' : 'false'" aria-invalid="{{ $errors->has('service') ? 'true' : 'false' }}"
                    style="width: 100%; padding: 14px 16px; border: 1px solid #e2e8e8; border-radius: var(--gs-r-btn, 10px); font-family: 'DM Sans'; font-size: 15px; outline: none; color: #4a5a6a; background: #fff;">
              <option value="">{{ t('optSelect') }}</option>
              <option value="web" @selected(old('service') === 'web')>{{ t('webDev') }}</option>
              <option value="webapp" @selected(old('service') === 'webapp')>{{ t('optWebApp') }}</option>
              <option value="mobile" @selected(old('service') === 'mobile')>{{ t('svc3T') }}</option>
              <option value="system" @selected(old('service') === 'system')>{{ t('optSystem') }}</option>
              <option value="other" @selected(old('service') === 'other')>{{ t('optOther') }}</option>
            </select>
            <p id="err-service" x-show="errors.service" x-text="errors.service?.[0]"
               style="display: {{ $errors->has('service') ? 'block' : 'none' }}; margin: 6px 2px 0; font-size: 13px; line-height: 1.45; color: #c0392b;">{{ $errors->first('service') }}</p>
          </div>
          <div>
            <textarea required name="message" placeholder="{{ t('phMsg') }}" rows="4"
                      class="foc-accent" aria-describedby="err-message" :aria-invalid="errors.message ? 'true' : 'false'" aria-invalid="{{ $errors->has('message') ? 'true' : 'false' }}"
                      style="width: 100%; padding: 14px 16px; border: 1px solid #e2e8e8; border-radius: var(--gs-r-btn, 10px); font-family: 'DM Sans'; font-size: 15px; outline: none; resize: vertical;">{{ old('message') }}</textarea>
            <p id="err-message" x-show="errors.message" x-text="errors.message?.[0]"
               style="display: {{ $errors->has('message') ? 'block' : 'none' }}; margin: 6px 2px 0; font-size: 13px; line-height: 1.45; color: #c0392b;">{{ $errors->first('message') }}</p>
          </div>
          <button type="submit" class="hov-dark" :disabled="sending" style="background: var(--gs-accent, #2CA69C); color: #fff; font-family: 'Space Grotesk'; font-weight: 600; font-size: 16px; padding: 16px; border: none; border-radius: var(--gs-r-btn, 10px); cursor: pointer; transition: .2s; display: inline-flex; align-items: center; justify-content: center; gap: 9px;"><x-t k="sendMsg"/> <svg class="gs-flip" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></button>
        </form>
      </div>
    </div>
    @if ($hasMap)
      <div class="gs-map" style="grid-column: 1 / -1; margin-top: 16px; border-radius: var(--gs-r-card, 20px); overflow: hidden; border: 1px solid rgba(255,255,255,.10); background: #e9eef0; position: relative;">
        {{-- not lazy-loaded on purpose: the Google embed sizes its map when it
             loads, and lazy-loading makes that happen mid-scroll, which leaves
             a small map floating in a full-width frame --}}
        <iframe src="{{ $embed }}" title="{{ $address ?: 'GoSoftware office location' }}"
                width="1200" height="360"
                referrerpolicy="no-referrer-when-downgrade" allowfullscreen
                style="width: 100%; height: 360px; border: 0; display: block;"></iframe>
        @if ($address)
          {{-- top-left in both directions: Google keeps its logo bottom-left and terms bottom-right --}}
          <a href="{{ $directions }}" target="_blank" rel="noopener" class="gs-map-chip" style="position: absolute; top: 18px; left: 18px; max-width: min(420px, calc(100% - 36px)); display: inline-flex; align-items: center; gap: 11px; background: rgba(13,24,38,.94); color: #fff; padding: 12px 18px; border-radius: var(--gs-r-btn, 10px); box-shadow: 0 14px 34px rgba(13,24,38,.28); transition: .2s;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; color: var(--gs-accent-lite, #6FDED3);"><path d="M12 21s7-6 7-11a7 7 0 10-14 0c0 5 7 11 7 11z"/><circle cx="12" cy="10" r="2.4"/></svg>
            <span style="font-family: 'Space Grotesk'; font-weight: 600; font-size: 14.5px; line-height: 1.4;">{{ $address }}</span>
          </a>
        @endif
      </div>
    @endif
  </div>
</section>
