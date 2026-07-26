<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') — Report GoTo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="app-page">
    <aside class="sidebar" data-sidebar>
        <div class="sidebar-head">
            <a href="{{ route('dashboard') }}" class="brand-mark"><span>GoTo</span><strong>MyAds</strong></a>
            <button class="icon-button sidebar-close" type="button" data-sidebar-close aria-label="Tutup menu">×</button>
        </div>
        <nav class="sidebar-nav">
            <p>MENU UTAMA</p>
            <a href="{{ route('dashboard') }}" class="active">
                <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="2"/><rect x="14" y="3" width="7" height="7" rx="2"/><rect x="3" y="14" width="7" height="7" rx="2"/><rect x="14" y="14" width="7" height="7" rx="2"/></svg>
                Overview
            </a>
            <a href="#"><svg viewBox="0 0 24 24"><path d="M4 19V9m6 10V5m6 14v-7m4 7H2"/></svg>Campaign Report</a>
            <a href="#"><svg viewBox="0 0 24 24"><path d="M4 4v16h16M7 15l4-4 3 3 6-7"/></svg>Performance</a>
            <p>MANAJEMEN</p>
            <a href="#"><svg viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>Advertiser</a>
            <a href="#"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 00.34 1.88l.06.06-2.83 2.83-.06-.06A1.7 1.7 0 0015 19.4a1.7 1.7 0 00-1 .6 1.7 1.7 0 00-.4 1v.1h-4v-.1a1.7 1.7 0 00-1.1-1.6 1.7 1.7 0 00-1.88.34l-.06.06-2.83-2.83.06-.06A1.7 1.7 0 004.6 15a1.7 1.7 0 00-.6-1 1.7 1.7 0 00-1-.4h-.1v-4H3A1.7 1.7 0 004.6 8a1.7 1.7 0 00-.34-1.88l-.06-.06 2.83-2.83.06.06A1.7 1.7 0 009 4.6a1.7 1.7 0 001-.6 1.7 1.7 0 00.4-1v-.1h4V3a1.7 1.7 0 001.1 1.6 1.7 1.7 0 001.88-.34l.06-.06 2.83 2.83-.06.06A1.7 1.7 0 0019.4 9c.12.38.34.73.6 1 .27.27.62.48 1 .6h.1v4H21a1.7 1.7 0 00-1.6 1.1z"/></svg>Pengaturan</a>
        </nav>
        <div class="sidebar-help">
            <div class="help-icon">?</div>
            <strong>Butuh bantuan?</strong>
            <span>Hubungi tim support kami.</span>
            <button type="button">Pusat Bantuan</button>
        </div>
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
            <div class="topbar-profile"><div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div><div><strong>{{ auth()->user()->name }}</strong><span>Administrator</span></div></div>
        </header>
        <main class="main-content">@yield('content')</main>
    </div>
</body>
</html>
