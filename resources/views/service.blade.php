@extends('layouts.app')

@section('title', $page['title'])
@section('meta_description', $page['meta'])

@section('content')
@php($clients = \App\Models\Client::ordered()->get())

{{-- 1. HERO — the outcome, not the technology --}}
<section style="background: var(--gs-deep-bg, #0D1826); color: #fff; padding: 60px 0 66px; position: relative; overflow: hidden;">
  <div style="position: absolute; inset: 0; background-image: linear-gradient(rgba(255,255,255,.03) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.03) 1px, transparent 1px); background-size: 54px 54px; mask-image: radial-gradient(ellipse 80% 70% at 30% 40%, #000 40%, transparent 100%);"></div>
  <div style="max-width: 900px; margin: 0 auto; padding: 0 24px; position: relative;">
    <nav aria-label="Breadcrumb" style="font-size: 13.5px; color: #7d8d9c; margin-bottom: 18px;">
      <a href="{{ route('home') }}" class="hov-accent-text" style="color: #7d8d9c;"><x-t k="navHome"/></a>
      <span style="margin: 0 8px;">/</span>
      <a href="{{ route('home') }}#services" class="hov-accent-text" style="color: #7d8d9c;"><x-t k="navServices"/></a>
      <span style="margin: 0 8px;">/</span>
      <span style="color: var(--gs-accent-lite, #6FDED3);">{{ $page['name'] }}</span>
    </nav>

    <h1 style="font-family: 'Space Grotesk'; font-weight: 700; font-size: clamp(29px, 4.3vw, 52px); line-height: 1.08; letter-spacing: -.02em; margin-bottom: 18px; text-wrap: balance;">{{ $page['h1'] }}</h1>
    <p style="font-size: clamp(15.5px, 1.7vw, 18px); line-height: 1.7; color: var(--gs-deep-muted, #A9B6C3); max-width: 660px; margin-bottom: 28px;">{{ $page['intro'] }}</p>

    <div class="gs-hero-ctas" style="display: flex; gap: 14px; flex-wrap: wrap;">
      <x-cta-primary location="service_hero" :service="$page['slug']"/>
      <x-whatsapp-cta :source="$page['whatsapp']" :service="$page['slug']" variant="secondary"/>
    </div>
  </div>
</section>

{{-- 2. TRUST BAR --}}
<section style="background: #0a1420; color: #93a2b1; padding: 16px 0; border-top: 1px solid rgba(255,255,255,.07);">
  <div style="max-width: 1100px; margin: 0 auto; padding: 0 24px; display: flex; align-items: center; justify-content: center; gap: 14px 34px; flex-wrap: wrap; font-size: 13.5px;">
    <span style="display: inline-flex; align-items: center; gap: 8px;"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--gs-accent-lite, #6FDED3)" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s7-6 7-11a7 7 0 10-14 0c0 5 7 11 7 11z"/><circle cx="12" cy="10" r="2.4"/></svg><x-t k="location"/></span>
    <span style="display: inline-flex; align-items: center; gap: 8px;"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--gs-accent-lite, #6FDED3)" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg><x-t k="heroTrust1"/></span>
    <span style="display: inline-flex; align-items: center; gap: 8px;"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--gs-accent-lite, #6FDED3)" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg><x-t k="heroTrust3"/></span>
    @foreach ($clients->take(5) as $client)
      @if ($client->logo)
        <img src="{{ media_url($client->logo) }}" alt="{{ $client->name }}" loading="lazy" decoding="async" height="22" style="height: 22px; width: auto; max-width: 96px; object-fit: contain; opacity: .7; filter: grayscale(1) brightness(1.9);">
      @endif
    @endforeach
  </div>
</section>

<div style="background: #fff;">
  <div style="max-width: 900px; margin: 0 auto; padding: 62px 24px 20px;">

    {{-- 3. WHO THIS IS FOR --}}
    <section style="margin-bottom: 54px;">
      <h2 style="font-family: 'Space Grotesk'; font-weight: 700; font-size: clamp(24px, 2.8vw, 33px); color: #0d1826; margin-bottom: 20px; letter-spacing: -.01em;">{{ $page['whoTitle'] }}</h2>
      <ul style="list-style: none; margin: 0; padding: 0; display: grid; gap: 12px;">
        @foreach ($page['who'] as $item)
          <li style="display: flex; gap: 12px; align-items: flex-start; font-size: 16.5px; line-height: 1.65; color: #4d5d6c;">
            <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="var(--gs-accent, #2CA69C)" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 3px;" aria-hidden="true"><path d="M20 6L9 17l-5-5"/></svg>
            <span>{{ $item }}</span>
          </li>
        @endforeach
      </ul>
    </section>

    {{-- 4. WHAT YOU GET --}}
    <section style="margin-bottom: 54px;">
      <h2 style="font-family: 'Space Grotesk'; font-weight: 700; font-size: clamp(24px, 2.8vw, 33px); color: #0d1826; margin-bottom: 20px; letter-spacing: -.01em;">{{ $page['getTitle'] }}</h2>
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 14px;">
        @foreach ($page['get'] as $item)
          <div style="background: #f5f8f8; border: 1px solid #e6eded; border-radius: var(--gs-r-tile, 13px); padding: 18px 20px; font-size: 15.5px; line-height: 1.6; color: #40505f;">{{ $item }}</div>
        @endforeach
      </div>
    </section>

    {{-- 5. WHAT IT COSTS — a marked placeholder, never an invented number --}}
    <section style="margin-bottom: 54px;">
      <h2 style="font-family: 'Space Grotesk'; font-weight: 700; font-size: clamp(24px, 2.8vw, 33px); color: #0d1826; margin-bottom: 20px; letter-spacing: -.01em;"><x-t k="svcCostTitle"/></h2>
      <div style="border: 1px solid #e6eded; border-radius: var(--gs-r-card, 16px); padding: 26px 28px; background: #fff;">
        <p style="font-size: 16.5px; line-height: 1.7; color: #4d5d6c; margin-bottom: 18px;"><x-t k="svcCostBody"/></p>
        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
          <x-cta-primary location="service_pricing" tone="dark" size="sm" :service="$page['slug']"/>
          <x-whatsapp-cta :source="$page['whatsapp']" :service="$page['slug']" variant="light"/>
        </div>
      </div>
    </section>

    {{-- 6. RELATED WORK --}}
    @if ($projects->isNotEmpty())
      <section style="margin-bottom: 54px;">
        <h2 style="font-family: 'Space Grotesk'; font-weight: 700; font-size: clamp(24px, 2.8vw, 33px); color: #0d1826; margin-bottom: 20px; letter-spacing: -.01em;"><x-t k="svcWorkTitle"/></h2>
        <div x-data="{ cat: 'all' }" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 18px;">
          @foreach ($projects as $project)
            @include('partials.project-tile', ['project' => $project])
          @endforeach
        </div>
      </section>
    @endif

    {{-- 7. PROCESS --}}
    <section style="margin-bottom: 54px;">
      <h2 style="font-family: 'Space Grotesk'; font-weight: 700; font-size: clamp(24px, 2.8vw, 33px); color: #0d1826; margin-bottom: 22px; letter-spacing: -.01em;">{{ $page['processTitle'] }}</h2>
      <ol style="list-style: none; margin: 0; padding: 0; display: grid; gap: 18px;">
        @foreach ($page['process'] as $i => $step)
          <li style="display: flex; gap: 16px; align-items: flex-start;">
            <span style="flex-shrink: 0; width: 38px; height: 38px; border-radius: var(--gs-r-tile, 10px); background: #eefaf8; color: #17877e; display: grid; place-items: center; font-family: 'Space Grotesk'; font-weight: 700; font-size: 15px;">{{ sprintf('%02d', $i + 1) }}</span>
            <div>
              <strong style="display: block; font-family: 'Space Grotesk'; font-size: 17px; color: #0d1826; margin-bottom: 4px;">{{ $step['step'] }}</strong>
              <span style="font-size: 15.5px; line-height: 1.65; color: #5a6a79;">{{ $step['body'] }}</span>
            </div>
          </li>
        @endforeach
      </ol>
    </section>

    {{-- 8. OBJECTION FAQ --}}
    <section style="margin-bottom: 54px;">
      <h2 style="font-family: 'Space Grotesk'; font-weight: 700; font-size: clamp(24px, 2.8vw, 33px); color: #0d1826; margin-bottom: 20px; letter-spacing: -.01em;">{{ $page['faqTitle'] }}</h2>
      @foreach ($page['faqs'] as $faq)
        <details class="gs-faq" data-gs-question="{{ $faq['q'] }}">
          <summary>{{ $faq['q'] }}<span class="gs-faq-mark" aria-hidden="true"></span></summary>
          <div class="gs-faq-body">{{ $faq['a'] }}</div>
        </details>
      @endforeach
      @include('partials.faq-schema', ['faqEntries' => $page['faqs']])
    </section>

    {{-- IP ownership: no local competitor states this (CRO-23) --}}
    <p style="display: flex; gap: 11px; align-items: flex-start; background: #eefaf8; border: 1px solid #cdece7; border-radius: var(--gs-r-tile, 12px); padding: 16px 18px; font-size: 15px; line-height: 1.6; color: #17605a; margin-bottom: 54px;">
      <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 2px;" aria-hidden="true"><rect x="4" y="10" width="16" height="10" rx="2"/><path d="M8 10V7a4 4 0 018 0v3"/></svg>
      <span><x-t k="ownCode"/></span>
    </p>
  </div>
</div>

{{-- 9. FINAL CTA + FORM + WHATSAPP --}}
@include('sections.contact')

{{-- Other services --}}
@if ($others->isNotEmpty())
  <section style="background: #f5f8f8; padding: 54px 0 64px;">
    <div style="max-width: 1100px; margin: 0 auto; padding: 0 24px;">
      <h2 style="font-family: 'Space Grotesk'; font-weight: 700; font-size: 26px; color: #0d1826; margin-bottom: 22px;"><x-t k="svcOthers"/></h2>
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 18px;">
        @foreach ($others as $other)
          @php($otherPage = \App\Support\ServiceCatalogue::page($other->slug))
          <a href="{{ gs_route('services/'.$other->slug) }}" class="hov-lift"
             style="background: #fff; border: 1px solid #e6eded; border-radius: var(--gs-r-card, 15px); padding: 22px; display: block; transition: .25s;">
            <strong style="display: block; font-family: 'Space Grotesk'; font-size: 17px; color: #0d1826; margin-bottom: 7px;">{{ $otherPage['name'] }}</strong>
            <span style="font-size: 14.5px; line-height: 1.6; color: #62717f;">{{ $otherPage['card'] }}</span>
          </a>
        @endforeach
      </div>
    </div>
  </section>
@endif
@endsection
