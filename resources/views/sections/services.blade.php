<!-- ===== SERVICES ===== -->
<section id="services" style="background: #f5f8f8; padding: 94px 0;">
  <div style="max-width: 1240px; margin: 0 auto; padding: 0 24px;">
    <div style="text-align: center; max-width: 640px; margin: 0 auto 54px;">
      <div style="display: inline-flex; align-items: center; gap: 11px; margin-bottom: 14px;"><span style="width: 30px; height: 2px; background: var(--gs-accent, #2CA69C);"></span><span style="color: var(--gs-accent, #2CA69C); font-family: 'Space Grotesk'; font-weight: 600; letter-spacing: .16em; font-size: 13px;"><x-t k="svcTag"/></span><span style="width: 30px; height: 2px; background: var(--gs-accent, #2CA69C);"></span></div>
      <h2 style="font-family: 'Space Grotesk'; font-weight: 700; font-size: clamp(30px, 3.6vw, 45px); line-height: 1.14; color: #0d1826; letter-spacing: -.02em;"><x-t k="svcTitle"/></h2>
    </div>
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px;" class="gs-2col">
      @foreach ($services as $i => $service)
        <div class="gs-service-card hov-lift" data-item-id="{{ $service->id }}" data-item-model="services" style="background: #fff; border-radius: var(--gs-r-card, 18px); overflow: hidden; border: 1px solid #eaefef; transition: .3s; position: relative;">
          <div style="position: relative; overflow: hidden; height: 220px;">
            <div style="width: 100%; height: 100%; background: #e7edee center/cover no-repeat; background-image: url('{{ media_url($service->image) }}');" @auth data-edit-image data-image-field="image" @endauth></div>
            @auth<button type="button" class="gs-edit-x" data-edit-delete title="Delete service">✕</button>@endauth
            <span style="position: absolute; top: 16px; left: 16px; background: rgba(13,24,38,.85); color: #fff; font-family: 'Space Grotesk'; font-weight: 600; font-size: 12px; padding: 6px 13px; border-radius: var(--gs-r-sm, 8px); letter-spacing: .06em;">{{ sprintf('%02d', $i + 1) }} · <span class="gs-edit" @auth data-edit-model="services" data-edit-id="{{ $service->id }}" data-edit-field="tag" @endauth>{{ $service->tag }}</span></span>
          </div>
          <div style="padding: 28px;">
            <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 12px;"><span style="width: 46px; height: 46px; border-radius: var(--gs-r-tile, 11px); background: #eefaf8; color: #17877e; display: grid; place-items: center; flex-shrink: 0; font-family: 'Space Grotesk'; font-weight: 700; font-size: 17px;">{{ sprintf('%02d', $i + 1) }}</span><h3 style="font-family: 'Space Grotesk'; font-weight: 600; font-size: 22px; color: #0d1826;"><span class="gs-edit" @auth data-edit-model="services" data-edit-id="{{ $service->id }}" data-edit-field="title" @endauth>{{ $service->tr('title') }}</span></h3></div>
            <p style="font-size: 15px; color: #6a7a8a; line-height: 1.7; margin-bottom: 18px;"><span class="gs-edit" @auth data-edit-model="services" data-edit-id="{{ $service->id }}" data-edit-field="description" @endauth>{{ $service->tr('description') }}</span></p>
            <a href="{{ route('home') }}#contact" class="hov-accent-text" data-gs-track="cta_click" data-gs-label="{{ t('learnMore', 'en') }}" data-gs-location="service_card" data-gs-service="{{ $service->tr('title', 'en') }}" style="font-family: 'Space Grotesk'; font-weight: 600; color: #0d1826; display: inline-flex; align-items: center; gap: 8px;"><x-t k="learnMore"/> <svg class="gs-flip" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
          </div>
        </div>
      @endforeach
      @auth
        <button type="button" class="gs-add-tile" data-edit-add="services" style="min-height: 220px; border: 2px dashed #b9c6c9; border-radius: var(--gs-r-card, 16px); background: transparent; color: #6a7a8a; font-family: 'Space Grotesk'; font-weight: 600; font-size: 15px; cursor: pointer; align-items: center; justify-content: center; gap: 8px;">＋ Add service</button>
      @endauth
    </div>
  </div>
</section>
