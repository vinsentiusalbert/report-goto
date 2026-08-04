<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Report GoTo') }}</title>
    <meta name="description" content="Dashboard internal Report GoTo">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="login-page">
    <main class="login-shell">
        <section class="login-brand">
            <div class="brand-mark brand-mark--light" aria-label="Report GoTo">
                <span>GoTo</span><strong>MyAds Report</strong>
            </div>
            <div class="brand-copy">
                <p class="eyebrow">Internal Dashboard</p>
                <h1>Monitor campaign lebih cepat.</h1>
                <p>Lihat spending, delivery, dan performa campaign GoTo MyAds dari satu dashboard yang sederhana.</p>
            </div>
            <div class="login-orb login-orb--one"></div>
            <div class="login-orb login-orb--two"></div>
        </section>

        <section class="login-panel">
            <div class="login-card">
                <div class="mobile-brand brand-mark"><span>GoTo</span><strong>MyAds Report</strong></div>
                <p class="eyebrow text-green">Selamat datang</p>
                <h2>Report GoTo</h2>
                <p class="login-subtitle">Aplikasi ini sekarang menggunakan asset CSS dan JavaScript biasa dari folder public.</p>

                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="primary-button" style="text-decoration:none;">
                        Masuk ke dashboard
                        <svg viewBox="0 0 24 24"><path d="M5 12h14m-5-5l5 5-5 5"/></svg>
                    </a>
                @endif
            </div>
        </section>
    </main>
</body>
</html>
