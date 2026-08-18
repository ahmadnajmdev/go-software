{{--
    The one primary call to action on the site.

    There were 17 CTAs using 7 different labels, all pointing at #contact —
    "Get a Quote", "Get a Free Quote", "Start Your Project", "More About Us",
    "Discover More", "Send Message", "GET IN TOUCH". A visitor cannot tell
    which of seven buttons is the real one, and none of them can be measured
    against another. The label is now defined once, here.

    <x-cta-primary location="hero"/>
    <x-cta-primary location="why" tone="dark"/>
--}}
@props([
    'location',
    'href' => null,
    'label' => null,
    'tone' => 'accent',
    'service' => null,
    'size' => 'md',
])
@php
    $caption = $label ?? t('ctaEstimate');
    $target = $href ?? route('home').'#contact';

    $padding = $size === 'sm' ? '13px 24px' : '15px 28px';
    $font = $size === 'sm' ? '15px' : '16px';

    $tones = [
        'accent' => ['background: var(--gs-accent, #2CA69C); color: #fff;', 'hov-white'],
        'dark' => ['background: #0d1826; color: #fff;', 'hov-accent-solid'],
        'light' => ['background: #fff; color: #0d1826; border: 1px solid #dde5e5;', 'hov-dark'],
    ];
    [$paint, $hover] = $tones[$tone] ?? $tones['accent'];
@endphp
{{-- The hover class goes through merge() with everything else. Emitting a
     separate class="…" here as well produced two class attributes on the same
     anchor, and the browser keeps only the first — so any class passed in by
     a caller was silently dropped. --}}
<a href="{{ $target }}"
   data-gs-track="cta_click"
   {{-- English label in every locale so one CTA is one row in the report --}}
   data-gs-label="{{ t('ctaEstimate', 'en') }}"
   data-gs-location="{{ $location }}"
   @if ($service) data-gs-service="{{ $service }}" @endif
   {{ $attributes->merge(['class' => $hover, 'style' => $paint." font-family: 'Space Grotesk'; font-weight: 600; font-size: {$font}; padding: {$padding}; border-radius: var(--gs-r-btn, 10px); display: inline-flex; align-items: center; justify-content: center; gap: 9px; transition: .25s;"]) }}>
    {{ $caption }}
    <svg class="gs-flip" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
</a>
