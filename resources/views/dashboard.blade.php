@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow text-green">OVERVIEW</p>
            <h1>Selamat pagi, {{ explode(' ', auth()->user()->name)[0] }}! <span>👋</span></h1>
        </div>
        <div class="heading-actions">
            <button class="secondary-button"><svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18"/></svg>30 Juli 2026</button>
        </div>
    </div>

    <section class="panel filter-panel">
        <div class="panel-head"><div><h2>GOTO Report</h2><p>Ringkasan data dari upload report GOTO.</p></div></div>
        <div class="stats-grid">
            @foreach ($gotoSummary as $item)
                <article class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon {{ $item['tone'] }}">
                            @if($item['icon'] === 'route')<svg viewBox="0 0 24 24"><circle cx="6" cy="19" r="2"/><circle cx="18" cy="5" r="2"/><path d="M6 17c0-7 12-3 12-10"/></svg>
                            @elseif($item['icon'] === 'users')<svg viewBox="0 0 24 24"><circle cx="9" cy="8" r="4"/><path d="M2 21v-2a5 5 0 015-5h4a5 5 0 015 5v2M17 11a4 4 0 000-7m5 17v-2a5 5 0 00-3-4.58"/></svg>
                            @elseif($item['icon'] === 'stack')<svg viewBox="0 0 24 24"><path d="M12 3l9 4.5-9 4.5-9-4.5L12 3z"/><path d="M3 12l9 4.5 9-4.5"/><path d="M3 16.5l9 4.5 9-4.5"/></svg>
                            @else<svg viewBox="0 0 24 24"><path d="M12 7v5l3 3"/><circle cx="12" cy="12" r="9"/></svg>@endif
                        </div>
                        <span class="trend neutral">GOTO</span>
                    </div>
                    <p>{{ $item['label'] }}</p>
                    <strong>{{ $item['value'] }}</strong>
                    <small>{{ $item['caption'] }}</small>
                </article>
            @endforeach
        </div>
    </section>

    <section class="panel filter-panel">
        <div class="panel-head"><div><h2>MyAds Report</h2><p>Ringkasan data dari reporting DB MyAds.</p></div></div>
        <div class="stats-grid">
            @foreach ($myAdsSummary as $item)
                <article class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon {{ $item['tone'] }}">
                            @if($item['icon'] === 'route')<svg viewBox="0 0 24 24"><circle cx="6" cy="19" r="2"/><circle cx="18" cy="5" r="2"/><path d="M6 17c0-7 12-3 12-10"/></svg>
                            @elseif($item['icon'] === 'users')<svg viewBox="0 0 24 24"><circle cx="9" cy="8" r="4"/><path d="M2 21v-2a5 5 0 015-5h4a5 5 0 015 5v2M17 11a4 4 0 000-7m5 17v-2a5 5 0 00-3-4.58"/></svg>
                            @elseif($item['icon'] === 'stack')<svg viewBox="0 0 24 24"><path d="M12 3l9 4.5-9 4.5-9-4.5L12 3z"/><path d="M3 12l9 4.5 9-4.5"/><path d="M3 16.5l9 4.5 9-4.5"/></svg>
                            @else<svg viewBox="0 0 24 24"><path d="M12 7v5l3 3"/><circle cx="12" cy="12" r="9"/></svg>@endif
                        </div>
                        <span class="trend neutral">MyAds</span>
                    </div>
                    <p>{{ $item['label'] }}</p>
                    <strong>{{ $item['value'] }}</strong>
                    <small>{{ $item['caption'] }}</small>
                </article>
            @endforeach
        </div>
    </section>

    <section class="dashboard-grid">
        <article class="panel revenue-panel">
            <div class="panel-head"><div><h2>Event Terbaru Gabungan</h2><p>Data terbaru dari GOTO upload dan MyAds reporting.</p></div></div>
            <div class="summary-list">
                @foreach ($latestEvents as $item)
                    <div class="summary-row">
                        <span>{{ $item['event_type'] }}<small>{{ $item['merchant_id'] ?: '-' }} • {{ $item['source'] === 'upload' ? 'GOTO Report' : 'MyAds Report' }}</small></span>
                        <strong>{{ \Carbon\Carbon::parse($item['created_at'])->format('d M Y H:i') }}</strong>
                    </div>
                @endforeach
            </div>
        </article>

        <article class="panel service-panel">
            <div class="panel-head"><div><h2>Sumber Data</h2><p>Overview sekarang sudah dipisah per report</p></div></div>
            <div class="service-legend compact">
                <div><i class="s1"></i><span>GOTO Report<small>Data hasil upload CSV lokal</small></span></div>
                <div><i class="s2"></i><span>MyAds Report<small>Data dari tabel DB</small></span></div>
                <div><i class="s3"></i><span>Merchant unik / hari<small>Satu merchant di hari yang sama dihitung sekali</small></span></div>
                <div><i class="s4"></i><span>Event terbaru gabungan<small>Daftar event diurutkan descending berdasarkan waktu</small></span></div>
            </div>
        </article>
    </section>
@endsection
