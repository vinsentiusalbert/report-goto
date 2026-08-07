<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') — Report GoTo</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body class="app-page">
    <aside class="sidebar" data-sidebar>
        <div class="sidebar-head">
            <a href="{{ route('dashboard') }}" class="brand-mark"><span>GoTo</span><strong>MyAds</strong></a>
        </div>
        <nav class="sidebar-nav">
            <p>MENU UTAMA</p>
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="2"/><rect x="14" y="3" width="7" height="7" rx="2"/><rect x="3" y="14" width="7" height="7" rx="2"/><rect x="14" y="14" width="7" height="7" rx="2"/></svg>
                Overview
            </a>
            <a href="{{ route('event-report') }}" class="{{ request()->routeIs('event-report') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M4 6h16"/><path d="M4 12h16"/><path d="M4 18h10"/><path d="M18 17l2 2 4-4"/></svg>
                Event Report
            </a>
            <a href="{{ route('daily-event-report') }}" class="{{ request()->routeIs('daily-event-report') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M4 19h16"/><path d="M6 16V8"/><path d="M12 16V5"/><path d="M18 16v-6"/></svg>
                Daily Report
            </a>
            <a href="{{ route('upload-report-goto.create') }}" class="{{ request()->routeIs('upload-report-goto.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M12 3v12"/><path d="M7 10l5 5 5-5"/><path d="M4 19h16"/></svg>
                Upload Report Goto
            </a>
            <a href="{{ route('password.edit') }}" class="{{ request()->routeIs('password.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><rect x="5" y="10" width="14" height="10" rx="2"/><path d="M8 10V7a4 4 0 018 0v3"/></svg>
                Change Password
            </a>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                    Manajemen User
                </a>
            @endif
        </nav>
        <div class="sidebar-user">
            <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div><strong>{{ auth()->user()->name }}</strong><span>{{ auth()->user()->email }}</span></div>
            <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" title="Keluar"><svg viewBox="0 0 24 24"><path d="M10 17l5-5-5-5m5 5H3m12-9h4a2 2 0 012 2v14a2 2 0 01-2 2h-4"/></svg></button></form>
        </div>
    </aside>
    <div class="sidebar-overlay" data-sidebar-overlay></div>
    <div class="app-content">
        <header class="topbar">
            <button class="icon-button menu-toggle" type="button" data-sidebar-open aria-label="Buka menu"><svg viewBox="0 0 24 24"><path d="M4 7h16M4 12h16M4 17h16"/></svg></button>
            <div class="topbar-spacer"></div>
            <button class="icon-button notification" type="button" aria-label="Notifikasi"><svg viewBox="0 0 24 24"><path d="M18 8a6 6 0 00-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/></svg><span></span></button>
            <div class="topbar-profile"><div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div><div><strong>{{ auth()->user()->name }}</strong><span>{{ auth()->user()->isAdmin() ? 'Administrator' : 'User' }}</span></div></div>
        </header>
        <main class="main-content">@yield('content')</main>
    </div>
</body>
</html>
