{{--
    Hero. One static message, no carousel.

    The two-slide carousel split the pitch in half and auto-rotated, so most
    visitors only ever saw one of the two headlines — and the stronger of them
    ("cut manual work") was on the slide most people never reached. It also
    animated a 500px image on load, which is the LCP element.

    Everything above the image must fit on a 390×844 phone: eyebrow, H1,
    subhead, both CTAs and the three trust lines.
--}}
@php
    $heroImage = media_url(gs_setting('images.hero'));
    $clients = \App\Models\Client::ordered()->get();
@endphp
<!-- ===== HERO ===== -->
<section id="home" class="gs-hero" style="position: relative; background: var(--gs-deep-bg, #0D1826); color: var(--gs-deep-fg, #FFFFFF); overflow: hidden;">
  <div style="position: absolute; inset: 0; background-image: linear-gradient(var(--gs-deep-grid, rgba(255,255,255,.03)) 1px, transparent 1px), linear-gradient(90deg, var(--gs-deep-grid, rgba(255,255,255,.03)) 1px, transparent 1px); background-size: 54px 54px; mask-image: radial-gradient(ellipse 80% 70% at 30% 40%, #000 40%, transparent 100%);"></div>

  <div class="gs-hero-grid" style="max-width: 1240px; margin: 0 auto; padding: 56px 24px 64px; display: grid; grid-template-columns: 1.05fr .95fr; gap: 56px; align-items: center; position: relative;">

    <div class="gs-hero-copy">
      <div style="display: flex; align-items: center; gap: 11px; margin-bottom: 18px;">
        <span style="width: 30px; height: 2px; background: var(--gs-accent, #2CA69C);"></span>
        <span style="color: var(--gs-accent-lite, #6FDED3); font-family: 'Space Grotesk'; font-weight: 600; letter-spacing: .14em; font-size: 12.5px;"><x-t k="h1Tag"/></span>
      </div>

      <h1 style="font-family: 'Space Grotesk'; font-weight: 700; font-size: clamp(31px, 4.7vw, 60px); line-height: 1.07; margin-bottom: 16px; color: var(--gs-deep-fg, #FFFFFF); letter-spacing: -.02em; text-wrap: balance;"><x-t k="heroTitle"/></h1>

      <p style="font-size: clamp(15px, 1.6vw, 17px); line-height: 1.65; color: var(--gs-deep-muted, #A9B6C3); max-width: 520px; margin-bottom: 26px;"><x-t k="heroSub"/></p>

      <div class="gs-hero-ctas" style="display: flex; align-items: center; gap: 14px; flex-wrap: wrap; margin-bottom: 24px;">
        <a href="{{ route('home') }}#contact" class="hov-white"
           data-gs-track="cta_click" data-gs-label="{{ t('ctaEstimate', 'en') }}" data-gs-location="hero"
           style="background: var(--gs-accent, #2CA69C); color: #fff; font-family: 'Space Grotesk'; font-weight: 600; font-size: 16px; padding: 15px 28px; border-radius: var(--gs-r-btn, 10px); display: inline-flex; align-items: center; gap: 9px; transition: .25s;"><x-t k="ctaEstimate"/> <svg class="gs-flip" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
        <x-whatsapp-cta source="hero" variant="secondary"/>
      </div>

      <ul class="gs-hero-trust" style="list-style: none; display: flex; flex-direction: column; gap: 8px; margin: 0; padding: 0;">
        @foreach (['heroTrust1', 'heroTrust2', 'heroTrust3'] as $trust)
          <li style="display: flex; align-items: center; gap: 9px; font-size: 14.5px; color: var(--gs-deep-muted, #A9B6C3);">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--gs-accent-lite, #6FDED3)" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;" aria-hidden="true"><path d="M20 6L9 17l-5-5"/></svg>
            <x-t :k="$trust"/>
          </li>
        @endforeach
      </ul>
    </div>

    {{-- The LCP element: never lazy-loaded, sized so it reserves its own space
         and contributes nothing to CLS. --}}
    <div class="gs-hero-media" style="position: relative;">
      <div style="border-radius: var(--gs-r-card, 20px); overflow: hidden; box-shadow: 0 40px 80px rgba(0,0,0,.4); border: 1px solid rgba(255,255,255,.08);">
        @if ($heroImage)
          <img src="{{ $heroImage }}" alt="{{ gs_setting_tr('contact.address') ?: 'GoSoftware' }}"
               width="620" height="500" fetchpriority="high" decoding="async"
               @auth data-edit-image data-image-setting="images.hero" @endauth
               style="width: 100%; height: 500px; object-fit: cover; display: block; background: #16283c;">
        @endif
      </div>
    </div>
  </div>

  {{-- Client logos, static. A marquee here would animate continuously behind
       the fold on every page view for no conversion benefit. --}}
  @if ($clients->isNotEmpty())
    <div style="position: relative; border-top: 1px solid rgba(255,255,255,.08);">
      <div style="max-width: 1240px; margin: 0 auto; padding: 22px 24px; display: flex; align-items: center; gap: 30px; flex-wrap: wrap;">
        <span style="font-size: 12.5px; letter-spacing: .12em; text-transform: uppercase; color: #6d7d8c; flex-shrink: 0;"><x-t k="trustedBy"/></span>
        <div class="gs-hero-logos" style="display: flex; align-items: center; gap: 34px; flex-wrap: wrap;">
          @foreach ($clients as $client)
            @if ($client->logo)
              <img src="{{ media_url($client->logo) }}" alt="{{ $client->name }}" loading="lazy" decoding="async"
                   height="26" style="height: 26px; width: auto; max-width: 120px; object-fit: contain; display: block; opacity: .78; filter: grayscale(1) brightness(1.9);">
            @else
              <span style="font-family: 'Space Grotesk'; font-weight: 600; font-size: 16px; color: #8b99a7; white-space: nowrap;">{{ $client->name }}</span>
            @endif
          @endforeach
        </div>
      </div>
    </div>
  @endif
</section>
