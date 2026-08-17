<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') — GoSoftware</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-dark.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/admin.css'])
</head>
<body>
@php
    $unreadCount = $unread ?? \App\Models\ContactSubmission::unread()->count();
    $user = auth()->user();
    $initials = collect(explode(' ', trim($user?->name ?? 'GS')))
        ->filter()->take(2)->map(fn ($p) => mb_strtoupper(mb_substr($p, 0, 1)))->implode('');

    // key => [route pattern, label, svg path(s)]
    $groups = [
        null => [
            ['admin.dashboard', 'admin.dashboard', 'Dashboard',
             '<rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/>'],
        ],
        'Content' => [
            ['admin.services.index', 'admin.services.*', 'Services',
             '<path d="M12 3l2.6 5.6 6 .8-4.4 4.2 1.1 6L12 16.8 6.7 19.6l1.1-6L3.4 9.4l6-.8z"/>'],
            ['admin.projects.index', 'admin.projects.*', 'Projects',
             '<rect x="3" y="5" width="18" height="15" rx="2"/><path d="M3 10h18M9 5V3M15 5V3"/>'],
            ['admin.clients.index', 'admin.clients.*', 'Clients',
             '<path d="M16 20v-2a4 4 0 00-4-4H7a4 4 0 00-4 4v2"/><circle cx="9.5" cy="7" r="3.2"/><path d="M17 11a3 3 0 100-6M21 20v-2a3.5 3.5 0 00-2.5-3.3"/>'],
            ['admin.testimonials.index', 'admin.testimonials.*', 'Testimonials',
             '<path d="M21 12a8 8 0 01-8 8H7l-4 3V12a8 8 0 018-8h2a8 8 0 018 8z"/>'],
        ],
        'Messages' => [
            ['admin.inbox.index', 'admin.inbox.*', 'Inbox',
             '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M4 7l8 6 8-6"/>'],
        ],
        'Site' => [
            ['admin.strings.index', 'admin.strings.*', 'UI Strings',
             '<path d="M4 7V5h16v2M9 19h6M12 5v14"/>'],
            ['admin.media.index', 'admin.media.*', 'Media',
             '<rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="8.5" cy="9.5" r="1.8"/><path d="M21 16l-5-5-6.5 7"/>'],
            ['admin.settings.edit', 'admin.settings.*', 'Settings',
             '<circle cx="12" cy="12" r="3.2"/><path d="M19.4 15a1.7 1.7 0 00.3 1.9l.1.1a2 2 0 11-2.8 2.8l-.1-.1a1.7 1.7 0 00-1.9-.3 1.7 1.7 0 00-1 1.5V21a2 2 0 11-4 0v-.1A1.7 1.7 0 008.9 19a1.7 1.7 0 00-1.9.3l-.1.1a2 2 0 11-2.8-2.8l.1-.1a1.7 1.7 0 00.3-1.9 1.7 1.7 0 00-1.5-1H3a2 2 0 110-4h.1A1.7 1.7 0 004.6 8.9a1.7 1.7 0 00-.3-1.9l-.1-.1a2 2 0 112.8-2.8l.1.1a1.7 1.7 0 001.9.3H9a1.7 1.7 0 001-1.5V3a2 2 0 114 0v.1a1.7 1.7 0 001 1.5 1.7 1.7 0 001.9-.3l.1-.1a2 2 0 112.8 2.8l-.1.1a1.7 1.7 0 00-.3 1.9V9a1.7 1.7 0 001.5 1H21a2 2 0 110 4h-.1a1.7 1.7 0 00-1.5 1z"/>'],
        ],
    ];
@endphp

<aside class="sidebar">
    <a class="sidebar-logo" href="{{ route('admin.dashboard') }}">
        <img src="{{ asset('images/logo-dark.png') }}" alt="GoSoftware">
    </a>

    <nav>
        @foreach ($groups as $label => $items)
            @if ($label)
                <div class="nav-group">{{ $label }}</div>
            @endif
            @foreach ($items as [$route, $pattern, $text, $icon])
                <a href="{{ route($route) }}" class="{{ request()->routeIs($pattern) ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">{!! $icon !!}</svg>
                    <span>{{ $text }}</span>
                    @if ($route === 'admin.inbox.index' && $unreadCount)
                        <span class="badge badge-accent">{{ $unreadCount }}</span>
                    @endif
                </a>
            @endforeach
        @endforeach
    </nav>

    <a href="{{ route('home') }}" target="_blank" rel="noopener"
       style="display:flex;align-items:center;gap:9px;padding:8px 11px;font-size:12.5px;color:var(--muted)">
        <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><path d="M15 3h6v6M10 14L21 3"/></svg>
        View site
    </a>

    @if ($user)
        <div class="sidebar-bottom">
            <div class="avatar-initials">{{ $initials }}</div>
            <div class="sidebar-user">
                <div class="who">{{ $user->name }}</div>
                <div class="role">{{ $user->email }}</div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="icon-btn" title="Sign out">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><path d="M16 17l5-5-5-5M21 12H9"/></svg>
                </button>
            </form>
        </div>
    @endif
</aside>

<main class="main">
    <header class="main-header">
        <div>
            <h1>@yield('title')</h1>
            @hasSection('subtitle')<p class="sub">@yield('subtitle')</p>@endif
        </div>
        <div class="header-actions">@yield('header-actions')</div>
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
