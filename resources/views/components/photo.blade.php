{{--
    A photograph, or an honest gap where one belongs.

    Stock photography was doing real damage here: the same recognisable
    Unsplash faces used as "the GoSoftware team" tell a prospect the site is a
    template. So there is no stock fallback and never will be — when no real
    photo is set this renders a plain branded panel instead, which reads as
    "no photo yet" rather than "here is a stranger we are passing off as us".

    <x-photo setting="images.about_main" alt="…" height="460" ratio="4 / 3"/>
--}}
@props([
    'setting',
    'alt' => '',
    'height' => null,
    'width' => null,
    'eager' => false,
    'round' => false,
])
@php
    $src = media_url(gs_setting($setting));
    $box = $height ? "height: {$height}px;" : '';
    $shape = $round ? 'border-radius: 50%;' : '';
@endphp
@if ($src)
    <img src="{{ $src }}" alt="{{ $alt }}"
         @if ($width) width="{{ $width }}" @endif
         @if ($height) height="{{ $height }}" @endif
         @if ($eager) fetchpriority="high" @else loading="lazy" @endif
         decoding="async"
         @auth data-edit-image data-image-setting="{{ $setting }}" @endauth
         {{ $attributes->merge(['style' => "width: 100%; {$box} object-fit: cover; display: block; {$shape}"]) }}>
@else
    <div role="img" aria-label="{{ $alt }}"
         @auth data-edit-image data-image-setting="{{ $setting }}" @endauth
         {{ $attributes->merge(['style' => "width: 100%; {$box} {$shape} display: grid; place-items: center;"
             .' background: linear-gradient(135deg, #16283c 0%, #0d1826 60%, #123037 100%);'
             .' background-image: linear-gradient(135deg, #16283c 0%, #0d1826 60%, #123037 100%),'
             .' linear-gradient(rgba(255,255,255,.028) 1px, transparent 1px),'
             .' linear-gradient(90deg, rgba(255,255,255,.028) 1px, transparent 1px);'
             .' background-size: auto, 26px 26px, 26px 26px;']) }}>
        <svg width="46" height="46" viewBox="0 0 24 24" fill="none" stroke="var(--gs-accent, #2CA69C)" stroke-width="1.3"
             stroke-linecap="round" stroke-linejoin="round" opacity=".55" aria-hidden="true">
            <rect x="3" y="4" width="18" height="16" rx="2.5"/><circle cx="8.6" cy="9.6" r="1.7"/><path d="M3.5 17l4.8-4.6 3.4 3.2 3-2.7 5.8 5"/>
        </svg>
        @auth
            <span style="position: absolute; font-size: 11.5px; color: #6d8794; margin-top: 74px;">Click to add a photo</span>
        @endauth
    </div>
@endif
