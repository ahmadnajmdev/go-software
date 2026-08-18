{{--
    One project, told as a story.

    Every section renders only if it has real content. Seven projects existed
    with no detail page at all — four linked straight off-site with no way
    back, three had no link. Nothing here is invented to fill a gap: an empty
    section is simply absent. See BLOCKED.md for what each project still needs.
--}}
@extends('layouts.app')

@php
    $outcome = $project->tr('outcome');
    $industry = $project->industry?->tr('name');
    $type = $project->category?->tr('name');
    $screenshots = array_values(array_filter((array) $project->screenshots));
    $waMessage = trim(t('waMsgHero').' '.$project->tr('title'));
@endphp

@section('title', $project->tr('title').' — '.($industry ?: t('navProjects')).' | GoSoftware')
@section('meta_description', $outcome ?: \Illuminate\Support\Str::limit(strip_tags($project->tr('problem')), 150))
@if ($project->image)
    @section('og_image', media_url($project->image))
@endif

@push('schema')
<script type="application/ld+json">{!! json_encode(\App\Support\Seo::breadcrumbs([
    ['name' => t('navHome'), 'url' => gs_route('')],
    ['name' => t('navProjects'), 'url' => gs_route('projects')],
    ['name' => $project->tr('title'), 'url' => gs_route('projects/'.$project->slug)],
]), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@section('content')

{{-- HERO --}}
<section style="background: var(--gs-deep-bg, #0D1826); color: #fff; padding: 56px 0 62px; position: relative; overflow: hidden;">
  <div style="position: absolute; inset: 0; background-image: linear-gradient(rgba(255,255,255,.03) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.03) 1px, transparent 1px); background-size: 54px 54px; mask-image: radial-gradient(ellipse 80% 70% at 30% 40%, #000 40%, transparent 100%);"></div>
  <div style="max-width: 900px; margin: 0 auto; padding: 0 24px; position: relative;">

    <nav aria-label="Breadcrumb" style="font-size: 13.5px; color: #7d8d9c; margin-bottom: 18px;">
      <a href="{{ gs_route('') }}" class="hov-accent-text" style="color: #7d8d9c;"><x-t k="navHome"/></a>
      <span style="margin: 0 8px;">/</span>
      <a href="{{ gs_route('projects') }}" class="hov-accent-text" style="color: #7d8d9c;"><x-t k="navProjects"/></a>
      <span style="margin: 0 8px;">/</span>
      <span style="color: var(--gs-accent-lite, #6FDED3);">{{ $project->tr('title') }}</span>
    </nav>

    @if ($industry)
      <span style="display: inline-block; background: rgba(44,166,156,.16); color: var(--gs-accent-lite, #6FDED3); font-family: 'Space Grotesk'; font-weight: 600; font-size: 12.5px; letter-spacing: .1em; padding: 6px 13px; border-radius: var(--gs-r-sm, 8px); margin-bottom: 14px;">{{ $industry }}</span>
    @endif

    <h1 style="font-family: 'Space Grotesk'; font-weight: 700; font-size: clamp(30px, 4.4vw, 52px); line-height: 1.08; letter-spacing: -.02em; margin-bottom: 14px;">{{ $project->tr('title') }}</h1>

    @if ($project->client)
      <p style="font-size: 15px; color: #93a2b1; margin-bottom: 14px;">{{ $project->client }}</p>
    @endif

    @if ($outcome)
      <p style="font-size: clamp(16px, 1.8vw, 19px); line-height: 1.65; color: var(--gs-deep-muted, #A9B6C3); max-width: 640px;">{{ $outcome }}</p>
    @endif
  </div>
</section>

{{-- AT A GLANCE --}}
@php
    $facts = array_filter([
        t('prjIndustry') => $industry,
        t('prjType') => $type,
        t('prjPlatforms') => $project->platforms,
        t('prjTimeline') => $project->timeline,
        t('prjLiveSince') => $project->live_since,
    ]);
@endphp
@if ($facts)
  <section style="background: #0a1420; color: #93a2b1; padding: 20px 0; border-top: 1px solid rgba(255,255,255,.07);">
    <div style="max-width: 900px; margin: 0 auto; padding: 0 24px; display: flex; gap: 14px 40px; flex-wrap: wrap; align-items: center;">
      <span style="font-family: 'Space Grotesk'; font-weight: 600; font-size: 12.5px; letter-spacing: .12em; text-transform: uppercase; color: var(--gs-accent-lite, #6FDED3);"><x-t k="prjGlance"/></span>
      @foreach ($facts as $label => $value)
        <div>
          <div style="font-size: 11.5px; letter-spacing: .1em; text-transform: uppercase; color: #6d7d8c; margin-bottom: 3px;">{{ $label }}</div>
          <div style="font-family: 'Space Grotesk'; font-weight: 600; font-size: 15px; color: #fff;">{{ $value }}</div>
        </div>
      @endforeach
    </div>
  </section>
@endif

<div style="background: #fff;">
  <div style="max-width: 900px; margin: 0 auto; padding: 58px 24px 20px;">

    @foreach ([
        ['prjProblem', $project->tr('problem')],
        ['prjSolution', $project->tr('solution')],
        ['prjResult', $project->tr('result')],
    ] as [$heading, $body])
      @if ($body)
        <section style="margin-bottom: 48px;">
          <h2 style="font-family: 'Space Grotesk'; font-weight: 700; font-size: clamp(23px, 2.7vw, 32px); color: #0d1826; margin-bottom: 16px; letter-spacing: -.01em;"><x-t :k="$heading"/></h2>
          @foreach (preg_split('/\R{2,}/', trim($body)) as $paragraph)
            <p style="font-size: 16.5px; line-height: 1.75; color: #4d5d6c; margin-bottom: 12px;">{{ $paragraph }}</p>
          @endforeach
        </section>
      @endif
    @endforeach

    {{-- SCREENSHOTS --}}
    @if ($screenshots)
      <section style="margin-bottom: 48px;">
        <h2 style="font-family: 'Space Grotesk'; font-weight: 700; font-size: clamp(23px, 2.7vw, 32px); color: #0d1826; margin-bottom: 18px; letter-spacing: -.01em;"><x-t k="prjScreens"/></h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px;">
          @foreach ($screenshots as $shot)
            <img src="{{ media_url($shot) }}" alt="{{ $project->tr('title') }}" loading="lazy" decoding="async"
                 width="420" height="280"
                 style="width: 100%; height: auto; border-radius: var(--gs-r-tile, 13px); border: 1px solid #e6eded; display: block;">
          @endforeach
        </div>
      </section>
    @endif

    {{-- TECHNOLOGY --}}
    @if ($project->technology)
      <section style="margin-bottom: 48px;">
        <h2 style="font-family: 'Space Grotesk'; font-weight: 700; font-size: clamp(23px, 2.7vw, 32px); color: #0d1826; margin-bottom: 16px; letter-spacing: -.01em;"><x-t k="prjTech"/></h2>
        <div style="display: flex; gap: 9px; flex-wrap: wrap;">
          @foreach (array_filter(array_map('trim', explode(',', $project->technology))) as $tech)
            <span style="background: #f2f7f7; border: 1px solid #e2eaea; border-radius: 999px; padding: 7px 15px; font-size: 14px; color: #40505f;">{{ $tech }}</span>
          @endforeach
        </div>
      </section>
    @endif

    {{-- CLIENT QUOTE --}}
    @if ($project->tr('quote'))
      <figure style="margin: 0 0 48px; background: #f7fafa; border-inline-start: 3px solid var(--gs-accent, #2CA69C); border-radius: var(--gs-r-tile, 13px); padding: 26px 28px;">
        <blockquote style="margin: 0 0 14px; font-size: 17px; line-height: 1.72; color: #33434f;">“{{ $project->tr('quote') }}”</blockquote>
        @if ($project->quote_author)
          <figcaption style="font-size: 14px; color: #6a7a8a;">
            <strong style="font-family: 'Space Grotesk'; color: #0d1826;">{{ $project->quote_author }}</strong>@if ($project->quote_role), {{ $project->quote_role }}@endif
          </figcaption>
        @endif
      </figure>
    @endif

    {{-- CTA + the off-site link, now INSIDE the page rather than instead of it --}}
    <section style="background: var(--gs-deep-bg, #0D1826); color: #fff; border-radius: var(--gs-r-card, 18px); padding: 32px; margin-bottom: 54px;">
      <h2 style="font-family: 'Space Grotesk'; font-weight: 700; font-size: clamp(21px, 2.4vw, 27px); line-height: 1.24; margin-bottom: 18px;"><x-t k="prjCtaTitle"/></h2>
      <div style="display: flex; gap: 12px; flex-wrap: wrap;">
        <x-cta-primary location="project_detail" size="sm" :service="$project->slug"/>
        <x-whatsapp-cta source="hero" :message="$waMessage" :service="$project->slug" variant="secondary"/>
        @if ($project->url)
          <a href="{{ $project->url }}" target="_blank" rel="noopener"
             data-gs-track="project_view" data-gs-project="{{ $project->tr('title', 'en') }}"
             style="background: rgba(255,255,255,.08); color: #fff; font-family: 'Space Grotesk'; font-weight: 600; font-size: 15px; padding: 14px 24px; border-radius: var(--gs-r-btn, 10px); display: inline-flex; align-items: center; gap: 9px;"><x-t k="prjVisit"/> <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17L17 7M9 7h8v8"/></svg></a>
        @endif
      </div>
    </section>
  </div>
</div>

{{-- RELATED --}}
@if ($related->isNotEmpty())
  <section style="background: #f5f8f8; padding: 54px 0 64px;">
    <div style="max-width: 1100px; margin: 0 auto; padding: 0 24px;">
      <h2 style="font-family: 'Space Grotesk'; font-weight: 700; font-size: 26px; color: #0d1826; margin-bottom: 22px;"><x-t k="prjRelated"/></h2>
      <div x-data="{ cat: 'all' }" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); gap: 18px;">
        @foreach ($related as $other)
          @include('partials.project-tile', ['project' => $other])
        @endforeach
      </div>
      <div style="text-align: center; margin-top: 34px;">
        <x-cta-primary location="project_related" tone="dark" size="sm" :href="gs_route('projects')" :label="t('prjAllWork')"/>
      </div>
    </div>
  </section>
@endif
@endsection
