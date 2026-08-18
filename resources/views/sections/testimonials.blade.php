{{--
    Client logos, and — only when real ones exist — client testimonials.

    This section used to be headed TESTIMONIALS and contain a logo marquee
    duplicated twice, with no quote, no name and no result anywhere in it. A
    heading that primes a visitor to look for endorsement and gives them none
    is worse than no section. It is now honestly labelled "Companies we work
    with", and the quotes appear beneath it only once somebody real has said
    something.
--}}
@php($voices = $testimonials->filter->isPublishable())
<!-- ===== CLIENTS + TESTIMONIALS ===== -->
<section style="background: #fff; padding: 78px 0;">
  <div style="max-width: 1240px; margin: 0 auto; padding: 0 24px;">

    @if ($clients->isNotEmpty())
      <div style="text-align: center; max-width: 640px; margin: 0 auto 38px;">
        <div style="display: inline-flex; align-items: center; gap: 11px; margin-bottom: 14px;"><span style="width: 30px; height: 2px; background: var(--gs-accent, #2CA69C);"></span><span style="color: var(--gs-accent, #2CA69C); font-family: 'Space Grotesk'; font-weight: 600; letter-spacing: .16em; font-size: 13px;"><x-t k="tstTag"/></span><span style="width: 30px; height: 2px; background: var(--gs-accent, #2CA69C);"></span></div>
        <h2 style="font-family: 'Space Grotesk'; font-weight: 700; font-size: clamp(28px, 3.4vw, 42px); line-height: 1.14; color: #0d1826; letter-spacing: -.02em;"><x-t k="tstTitle"/></h2>
      </div>

      {{-- Rendered once, statically. The marquee duplicated every logo into
           the DOM and animated them continuously for no conversion benefit. --}}
      <div style="display: flex; align-items: center; justify-content: center; gap: 26px 54px; flex-wrap: wrap;">
        @foreach ($clients as $client)
          @php($tag = $client->url ? 'a' : 'span')
          <{{ $tag }} class="gs-client"
             @if ($client->url) href="{{ $client->url }}" target="_blank" rel="noopener" @endif
             style="display: flex; align-items: center; flex-shrink: 0;">
            @if ($client->logo)
              <img src="{{ media_url($client->logo) }}" alt="{{ $client->name }}" loading="lazy" decoding="async"
                   height="42" style="height: 42px; width: auto; max-width: 165px; object-fit: contain; display: block;">
            @else
              <span style="font-family: 'Space Grotesk'; font-weight: 700; font-size: 23px; color: #0d1826; white-space: nowrap;">{{ $client->name }}</span>
            @endif
          </{{ $tag }}>
        @endforeach
      </div>
    @endif

    @if ($voices->isNotEmpty())
      <div style="text-align: center; max-width: 640px; margin: {{ $clients->isNotEmpty() ? '66px' : '0' }} auto 38px;">
        <div style="display: inline-flex; align-items: center; gap: 11px; margin-bottom: 14px;"><span style="width: 30px; height: 2px; background: var(--gs-accent, #2CA69C);"></span><span style="color: var(--gs-accent, #2CA69C); font-family: 'Space Grotesk'; font-weight: 600; letter-spacing: .16em; font-size: 13px;"><x-t k="tstVoicesTag"/></span><span style="width: 30px; height: 2px; background: var(--gs-accent, #2CA69C);"></span></div>
        <h2 style="font-family: 'Space Grotesk'; font-weight: 700; font-size: clamp(28px, 3.4vw, 42px); line-height: 1.14; color: #0d1826; letter-spacing: -.02em;"><x-t k="tstVoicesTitle"/></h2>
      </div>

      <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 22px;" class="gs-3col">
        @foreach ($voices as $testimonial)
          <figure data-item-id="{{ $testimonial->id }}" data-item-model="testimonials"
                  style="margin: 0; background: #f7fafa; border-radius: var(--gs-r-card, 16px); padding: 28px; border: 1px solid #e8efef; display: flex; flex-direction: column;">

            @if ($testimonial->video_url)
              <div style="position: relative; margin: -28px -28px 22px; border-radius: var(--gs-r-card, 16px) var(--gs-r-card, 16px) 0 0; overflow: hidden; aspect-ratio: 16 / 9; background: #0d1826;">
                <iframe src="{{ $testimonial->video_url }}" title="{{ $testimonial->author }}" loading="lazy"
                        allow="accelerometer; clipboard-write; encrypted-media; picture-in-picture" allowfullscreen
                        style="width: 100%; height: 100%; border: 0; display: block;"></iframe>
              </div>
            @endif

            <blockquote style="margin: 0 0 20px; font-size: 16px; line-height: 1.72; color: #33434f;">
              <span class="gs-edit" @auth data-edit-model="testimonials" data-edit-id="{{ $testimonial->id }}" data-edit-field="quote" @endauth>“{{ $testimonial->tr('quote') }}”</span>
            </blockquote>

            @if ($testimonial->tr('result'))
              <div style="margin-bottom: 20px; padding: 13px 16px; background: #eefaf8; border-radius: var(--gs-r-tile, 11px);">
                <div style="font-size: 11.5px; letter-spacing: .12em; text-transform: uppercase; color: #17877e; margin-bottom: 3px;"><x-t k="tstResult"/></div>
                <div style="font-family: 'Space Grotesk'; font-weight: 600; font-size: 15px; line-height: 1.45; color: #0d1826;">
                  <span class="gs-edit" @auth data-edit-model="testimonials" data-edit-id="{{ $testimonial->id }}" data-edit-field="result" @endauth>{{ $testimonial->tr('result') }}</span>
                </div>
              </div>
            @endif

            <figcaption style="display: flex; align-items: center; gap: 14px; margin-top: auto;">
              @if ($testimonial->avatar)
                <img src="{{ media_url($testimonial->avatar) }}" alt="{{ $testimonial->author }}" loading="lazy" decoding="async" width="50" height="50" @auth data-edit-image data-image-field="avatar" @endauth style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover; flex-shrink: 0;">
              @else
                <div aria-hidden="true" @auth data-edit-image data-image-field="avatar" @endauth style="width: 50px; height: 50px; flex-shrink: 0; border-radius: 50%; background: #e7efee; color: #17877e; display: grid; place-items: center; font-family: 'Space Grotesk'; font-weight: 700; font-size: 19px;">{{ mb_strtoupper(mb_substr($testimonial->author, 0, 1)) }}</div>
              @endif
              <div>
                <div style="font-family: 'Space Grotesk'; font-weight: 700; color: #0d1826;">{{ $testimonial->author }}</div>
                <div style="font-size: 13px; color: #6a7a8a;">
                  <span class="gs-edit" @auth data-edit-model="testimonials" data-edit-id="{{ $testimonial->id }}" data-edit-field="role" @endauth>{{ $testimonial->tr('role') }}</span>@if ($testimonial->company), {{ $testimonial->company }}@endif
                </div>
              </div>
            </figcaption>
          </figure>
        @endforeach
      </div>
    @endif
  </div>
</section>
