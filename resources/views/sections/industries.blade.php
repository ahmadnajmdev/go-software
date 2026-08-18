{{--
    Industry router. One home page cannot speak to a pharmacy, a courier and a
    property agency at once without diluting the hero, so it asks instead and
    sends each of them somewhere written for them.

    Tiles link to whichever service page actually fits that industry — several
    share a destination, which is honest: a shop and a restaurant both need the
    same offline till.
--}}
@php
    $industries = [
        ['key' => 'indRetail',   'slug' => 'pos-inventory',          'glyph' => 'M4 8h16l-1.4 11.2a2 2 0 01-2 1.8H7.4a2 2 0 01-2-1.8L4 8zm4 0V6a4 4 0 018 0v2'],
        ['key' => 'indFood',     'slug' => 'pos-inventory',          'glyph' => 'M5 3v8a3 3 0 006 0V3M8 11v10M17 3c-1.7 1.5-2.5 3.6-2.5 6 0 1.8.8 3 2.5 3.4V21'],
        ['key' => 'indAcademy',  'slug' => 'management-systems',     'glyph' => 'M12 4L2 9l10 5 10-5-10-5zM6 11.5V17c0 1.7 2.7 3 6 3s6-1.3 6-3v-5.5'],
        ['key' => 'indProperty', 'slug' => 'web-applications',       'glyph' => 'M3 10.5L12 3l9 7.5M5.5 9.5V20h13V9.5M10 20v-6h4v6'],
        ['key' => 'indDelivery', 'slug' => 'mobile-app-development', 'glyph' => 'M3 7h11v9H3zM14 10h4l3 3v3h-7zM7 19a2 2 0 100-4 2 2 0 000 4zM18 19a2 2 0 100-4 2 2 0 000 4z'],
        ['key' => 'indEcom',     'slug' => 'ecommerce',              'glyph' => 'M3 4h2l2.4 11.2a2 2 0 002 1.6h7.7a2 2 0 002-1.5L21 8H6M9 21a1 1 0 100-2 1 1 0 000 2zM18 21a1 1 0 100-2 1 1 0 000 2z'],
    ];
@endphp
<!-- ===== INDUSTRY ROUTER ===== -->
<section id="industries" style="background: #fff; padding: 72px 0;">
  <div style="max-width: 1100px; margin: 0 auto; padding: 0 24px;">
    <div style="text-align: center; margin-bottom: 32px;">
      <div style="display: inline-flex; align-items: center; gap: 11px; margin-bottom: 14px;">
        <span style="width: 30px; height: 2px; background: var(--gs-accent, #2CA69C);"></span>
        <span style="color: var(--gs-accent, #2CA69C); font-family: 'Space Grotesk'; font-weight: 600; letter-spacing: .16em; font-size: 13px;"><x-t k="indTag"/></span>
        <span style="width: 30px; height: 2px; background: var(--gs-accent, #2CA69C);"></span>
      </div>
      <h2 style="font-family: 'Space Grotesk'; font-weight: 700; font-size: clamp(26px, 3.3vw, 40px); line-height: 1.16; color: #0d1826; letter-spacing: -.02em;"><x-t k="indTitle"/></h2>
    </div>

    <div class="gs-industries">
      @foreach ($industries as $industry)
        <a href="{{ gs_route('services/'.$industry['slug']) }}" class="gs-industry hov-lift"
           data-gs-track="cta_click" data-gs-label="{{ t($industry['key'], 'en') }}"
           data-gs-location="industry_router" data-gs-service="{{ $industry['slug'] }}">
          <span class="gs-industry-icon" aria-hidden="true">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $industry['glyph'] }}"/></svg>
          </span>
          <span class="gs-industry-name"><x-t :k="$industry['key']"/></span>
          <svg class="gs-flip gs-industry-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </a>
      @endforeach
    </div>
  </div>
</section>
