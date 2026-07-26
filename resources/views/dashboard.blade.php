@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow text-green">OVERVIEW</p>
            <h1>Selamat pagi, {{ explode(' ', auth()->user()->name)[0] }}! <span>👋</span></h1>
            <p>Berikut ringkasan performa campaign GoTo MyAds Anda.</p>
        </div>
        <div class="heading-actions">
            <button class="secondary-button"><svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18"/></svg>26 Juli 2026</button>
            <button class="primary-button small"><svg viewBox="0 0 24 24"><path d="M12 3v12m0 0l4-4m-4 4l-4-4M4 19h16"/></svg>Unduh Laporan</button>
        </div>
    </div>

    <section class="stats-grid">
        @foreach ($summary as $item)
            <article class="stat-card">
                <div class="stat-top">
                    <div class="stat-icon {{ $item['tone'] }}">
                        @if($item['icon'] === 'route')<svg viewBox="0 0 24 24"><circle cx="6" cy="19" r="2"/><circle cx="18" cy="5" r="2"/><path d="M6 17c0-7 12-3 12-10"/></svg>
                        @elseif($item['icon'] === 'wallet')<svg viewBox="0 0 24 24"><path d="M3 6h16a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V6zm0 2h16V5a2 2 0 00-2-2H5a2 2 0 00-2 2"/><circle cx="17" cy="14" r="1"/></svg>
                        @elseif($item['icon'] === 'users')<svg viewBox="0 0 24 24"><circle cx="9" cy="8" r="4"/><path d="M2 21v-2a5 5 0 015-5h4a5 5 0 015 5v2M17 11a4 4 0 000-7m5 17v-2a5 5 0 00-3-4.58"/></svg>
                        @else<svg viewBox="0 0 24 24"><path d="M12 2l3 6 7 .9-5 4.8 1.2 7-6.2-3.3-6.2 3.3 1.2-7-5-4.8L9 8z"/></svg>@endif
                    </div>
                    <span class="trend">{{ $item['change'] }} <small>↗</small></span>
                </div>
                <p>{{ $item['label'] }}</p>
                <strong>{{ $item['value'] }}</strong>
                <small>dibanding bulan lalu</small>
            </article>
        @endforeach
    </section>

    <section class="dashboard-grid">
        <article class="panel revenue-panel">
            <div class="panel-head">
                <div><h2>Tren Spending</h2><p>Realisasi biaya iklan 7 hari terakhir</p></div>
                <select aria-label="Periode"><option>7 Hari</option><option>30 Hari</option></select>
            </div>
            <div class="chart-legend"><span><i class="green-dot"></i>Ad Spending</span><strong>Rp 46,8 jt <small>+11,2%</small></strong></div>
            <div class="chart">
                <div class="chart-lines"><i></i><i></i><i></i><i></i></div>
                <svg viewBox="0 0 700 210" preserveAspectRatio="none" aria-label="Grafik pendapatan">
                    <defs><linearGradient id="area" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#00aa5b" stop-opacity=".25"/><stop offset="1" stop-color="#00aa5b" stop-opacity="0"/></linearGradient></defs>
                    <path class="chart-area" d="M0 171 C45 165 60 133 105 139 S170 165 210 130 S275 98 315 115 S380 156 420 115 S485 72 525 85 S590 112 630 63 S680 52 700 34 L700 210 L0 210Z"/>
                    <path class="chart-line" d="M0 171 C45 165 60 133 105 139 S170 165 210 130 S275 98 315 115 S380 156 420 115 S485 72 525 85 S590 112 630 63 S680 52 700 34"/>
                </svg>
                <div class="chart-labels"><span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span><span>Sab</span><span>Min</span></div>
            </div>
        </article>

        <article class="panel service-panel">
            <div class="panel-head"><div><h2>Distribusi Channel</h2><p>Berdasarkan total campaign</p></div><button class="more">•••</button></div>
            <div class="donut-wrap">
                <div class="donut"><div><strong>148</strong><span>Campaign</span></div></div>
                <div class="service-legend">
                    <div><i class="s1"></i><span>SMS<small>65 campaign</small></span><strong>44%</strong></div>
                    <div><i class="s2"></i><span>MMS<small>41 campaign</small></span><strong>28%</strong></div>
                    <div><i class="s3"></i><span>USSD<small>27 campaign</small></span><strong>18%</strong></div>
                    <div><i class="s4"></i><span>Messaging<small>15 campaign</small></span><strong>10%</strong></div>
                </div>
            </div>
        </article>
    </section>

    <section class="panel activity-panel">
        <div class="panel-head"><div><h2>Campaign Terbaru</h2><p>Performa campaign yang terakhir diperbarui</p></div><button class="text-button">Lihat Semua <span>→</span></button></div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>ID CAMPAIGN</th><th>CHANNEL</th><th>NAMA CAMPAIGN</th><th>TANGGAL</th><th>DELIVERED</th><th>STATUS</th></tr></thead>
                <tbody>
                @foreach($activities as $activity)
                    <tr>
                        <td><strong>{{ $activity['id'] }}</strong></td>
                        <td><span class="service-badge {{ strtolower($activity['service']) }}">{{ substr($activity['service'], 0, 2) }}</span>{{ $activity['service'] }}</td>
                        <td><span class="mini-avatar">{{ strtoupper(substr($activity['driver'], 0, 1)) }}</span>{{ $activity['driver'] }}</td>
                        <td>{{ $activity['time'] }}</td><td><strong>{{ $activity['amount'] }}</strong></td>
                        <td><span class="status {{ strtolower($activity['status']) }}"><i></i>{{ $activity['status'] }}</span></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
