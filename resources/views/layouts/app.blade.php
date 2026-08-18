<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('partials.gtm-head')
@php
    use App\Support\Seo;
    // Blade escapes the content of @section('title', $value) itself, so these
    // are already HTML-safe and are echoed with {!! !!}. The fallbacks come
    // from t() unescaped, so they are escaped here to match.
    $gsTitle = trim($__env->yieldContent('title')) ?: e(t('metaHomeTitle'));
    $gsDescription = trim($__env->yieldContent('meta_description')) ?: e(t('metaHomeDesc'));
    $gsAlternates = Seo::alternates();
@endphp
<title>{!! $gsTitle !!}</title>
<meta name="description" content="{!! $gsDescription !!}">
<link rel="canonical" href="{{ Seo::canonical() }}">
{{-- Alternates are derived from the current path. They used to be hardcoded to
     the home page on every page, which told Google that the Kurdish version of
     /projects was the Kurdish home page. --}}
@foreach ($gsAlternates as $gsLocale => $gsHref)
<link rel="alternate" hreflang="{{ $gsLocale }}" href="{{ $gsHref }}">
@endforeach
<link rel="alternate" hreflang="x-default" href="{{ $gsAlternates['en'] }}">

<meta property="og:type" content="website">
<meta property="og:site_name" content="{{ config('app.name') }}">
<meta property="og:locale" content="{{ ['en' => 'en_GB', 'ar' => 'ar_IQ', 'ckb' => 'ckb_IQ'][app()->getLocale()] }}">
<meta property="og:title" content="{!! $gsTitle !!}">
<meta property="og:description" content="{!! $gsDescription !!}">
<meta property="og:url" content="{{ Seo::canonical() }}">
<meta property="og:image" content="@yield('og_image', Seo::ogImage())">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{!! $gsTitle !!}">
<meta name="twitter:description" content="{!! $gsDescription !!}">
<meta name="twitter:image" content="@yield('og_image', Seo::ogImage())">
<link rel="icon" type="image/png" href="{{ asset('images/logo-dark.png') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700&family=IBM+Plex+Sans+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>:root { {!! \App\Support\Theme::cssVars() !!} }</style>
@auth
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/admin-edit.js'])
@else
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@endauth
</head>
<body dir="{{ app()->getLocale() === 'en' ? 'ltr' : 'rtl' }}">
@include('partials.gtm-body')

{{-- Who and where we are, for search engines and Maps. --}}
<script type="application/ld+json">{!! json_encode(Seo::localBusiness(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
<script type="application/ld+json">{!! json_encode(Seo::organization(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@stack('schema')

<div style="overflow-x: clip; width: 100%; display: flex; flex-direction: column;">

    @include('partials.topbar')
    @include('partials.header')

    @yield('content')

    @include('partials.footer')

</div>

@include('partials.sticky-bar')

@auth
    @include('partials.edit-mode')
@endauth

</body>
</html>
