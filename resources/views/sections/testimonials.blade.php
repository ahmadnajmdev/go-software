<!-- ===== TESTIMONIALS ===== -->
<section style="background: #f5f8f8; padding: 94px 0;">
  <div style="max-width: 1240px; margin: 0 auto; padding: 0 24px;">
    <div style="text-align: center; max-width: 640px; margin: 0 auto 20px;">
      <div style="display: inline-flex; align-items: center; gap: 11px; margin-bottom: 14px;"><span style="width: 30px; height: 2px; background: var(--gs-accent, #2CA69C);"></span><span style="color: var(--gs-accent, #2CA69C); font-family: 'Space Grotesk'; font-weight: 600; letter-spacing: .16em; font-size: 13px;"><x-t k="tstTag"/></span><span style="width: 30px; height: 2px; background: var(--gs-accent, #2CA69C);"></span></div>
      <h2 style="font-family: 'Space Grotesk'; font-weight: 700; font-size: clamp(30px, 3.6vw, 45px); line-height: 1.14; color: #0d1826; letter-spacing: -.02em;"><x-t k="tstTitle"/></h2>
    </div>
    @if ($clients->isNotEmpty())
      <div style="overflow: hidden; margin: 28px 0 48px;">
        <div class="gs-marquee-track" style="display: flex; width: max-content; gap: 60px; align-items: center; animation: gsMarquee 30s linear infinite;">
          @include('partials.clients-track', ['clients' => $clients])
          @include('partials.clients-track', ['clients' => $clients, 'duplicate' => true])
        </div>
      </div>
    @endif
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px;" class="gs-3col">
      @foreach ($testimonials as $testimonial)
        <div data-item-id="{{ $testimonial->id }}" data-item-model="testimonials" style="background: #fff; border-radius: var(--gs-r-card, 16px); padding: 32px; border: 1px solid #eaefef;">
          <div style="display: flex; gap: 3px; margin-bottom: 16px;">@for ($s = 0; $s < $testimonial->rating; $s++)<svg width="18" height="18" viewBox="0 0 24 24" fill="#f5b301"><path d="M12 2l2.9 6.3 6.6.7-4.9 4.5 1.3 6.5L12 17.8 6.1 20.5l1.3-6.5L2.5 9l6.6-.7L12 2z"/></svg>@endfor</div>
          <p style="font-size: 16px; line-height: 1.7; color: #3a4a5a; margin-bottom: 24px;"><span class="gs-edit" @auth data-edit-model="testimonials" data-edit-id="{{ $testimonial->id }}" data-edit-field="quote" @endauth>{{ $testimonial->tr('quote') }}</span></p>
          <div style="display: flex; align-items: center; gap: 14px;">
            {{-- A missing avatar shows the person's initial, never an empty
                 <img> and never a stock face. --}}
            @if ($testimonial->avatar)
              <img src="{{ media_url($testimonial->avatar) }}" alt="{{ $testimonial->author }}" loading="lazy" decoding="async" width="50" height="50" @auth data-edit-image data-image-field="avatar" @endauth style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
            @else
              <div aria-hidden="true" @auth data-edit-image data-image-field="avatar" @endauth style="width: 50px; height: 50px; flex-shrink: 0; border-radius: 50%; background: #e7efee; color: #17877e; display: grid; place-items: center; font-family: 'Space Grotesk'; font-weight: 700; font-size: 19px;">{{ mb_strtoupper(mb_substr($testimonial->author, 0, 1)) }}</div>
            @endif
            <div><div style="font-family: 'Space Grotesk'; font-weight: 700; color: #0d1826;">{{ $testimonial->author }}</div><div style="font-size: 13px; color: #6a7a8a;"><span class="gs-edit" @auth data-edit-model="testimonials" data-edit-id="{{ $testimonial->id }}" data-edit-field="role" @endauth>{{ $testimonial->tr('role') }}</span></div></div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>
