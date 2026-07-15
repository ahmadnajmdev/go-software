<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'GoSoftware — '.t('h1Tag'))</title>
<meta name="description" content="@yield('meta_description', t('h1Body'))">
<link rel="canonical" href="{{ url()->current() }}">
<link rel="alternate" hreflang="en" href="{{ url('/') }}">
<link rel="alternate" hreflang="ar" href="{{ url('/ar') }}">
<link rel="alternate" hreflang="ckb" href="{{ url('/ckb') }}">
<link rel="alternate" hreflang="x-default" href="{{ url('/') }}">
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

<div style="overflow-x: clip; width: 100%; display: flex; flex-direction: column;">

    @include('partials.topbar')
    @include('partials.header')

    @yield('content')

    @include('partials.footer')

</div>

@auth
    @include('partials.edit-mode')
@endauth

</body>
</html>
