<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') — GoSoftware</title>
    @vite(['resources/css/admin.css'])
</head>
<body>
@php($unreadCount = $unread ?? \App\Models\ContactSubmission::unread()->count())
<aside class="sidebar">
    <a class="sidebar-logo" href="{{ route('admin.dashboard') }}">
        <img src="{{ asset('images/logo-light.png') }}" alt="GoSoftware" height="28">
    </a>
    <nav>
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
        <a href="{{ route('admin.services.index') }}" class="{{ request()->routeIs('admin.services.*') ? 'active' : '' }}">Services</a>
        <a href="{{ route('admin.projects.index') }}" class="{{ request()->routeIs('admin.projects.*') ? 'active' : '' }}">Projects</a>
        <a href="{{ route('admin.testimonials.index') }}" class="{{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">Testimonials</a>
        <a href="{{ route('admin.clients.index') }}" class="{{ request()->routeIs('admin.clients.*') ? 'active' : '' }}">Clients</a>
        <a href="{{ route('admin.strings.index') }}" class="{{ request()->routeIs('admin.strings.*') ? 'active' : '' }}">UI Strings</a>
        <a href="{{ route('admin.inbox.index') }}" class="{{ request()->routeIs('admin.inbox.*') ? 'active' : '' }}">
            Inbox @if($unreadCount) <span class="badge badge-accent">{{ $unreadCount }}</span> @endif
        </a>
        <a href="{{ route('admin.media.index') }}" class="{{ request()->routeIs('admin.media.*') ? 'active' : '' }}">Media</a>
        <a href="{{ route('admin.settings.edit') }}" class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">Settings</a>
    </nav>
    <div class="sidebar-bottom">
        <a href="{{ route('home') }}" target="_blank">View site ↗</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="linklike">Log out</button>
        </form>
    </div>
</aside>

<main class="main">
    <header class="main-header">
        <h1>@yield('title')</h1>
        @yield('header-actions')
    </header>

    @if(session('ok'))
        <div class="flash-ok">{{ session('ok') }}</div>
    @endif

    @if($errors->any())
        <div class="flash-err">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</main>
@stack('scripts')
</body>
</html>
