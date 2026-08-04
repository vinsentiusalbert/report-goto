@extends('layouts.dashboard')

@section('title', 'Event Report')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow text-green">REPORTING</p>
            <h1>Event Report</h1>
        </div>
    </div>

    <section class="panel filter-panel">
        <div class="panel-head">
            <div>
                <h2>GOTO Report</h2>
            </div>
        </div>
        <div class="stats-grid">
            @forelse ($gotoReportSummary as $item)
                <article class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon {{ $item['tone'] }}">
                            @if($item['icon'] === 'eye')
                                <svg viewBox="0 0 24 24"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12z"/><circle cx="12" cy="12" r="2.5"/></svg>
                            @elseif($item['icon'] === 'cursor')
                                <svg viewBox="0 0 24 24"><path d="M5 3l10 10-4 1 2 5-2.5 1-2-5-3 3z"/></svg>
                            @elseif($item['icon'] === 'arrow')
                                <svg viewBox="0 0 24 24"><path d="M7 17L17 7"/><path d="M8 7h9v9"/></svg>
                            @else
                                <svg viewBox="0 0 24 24"><path d="M12 3l7 3v5c0 4.6-2.7 8.8-7 10-4.3-1.2-7-5.4-7-10V6l7-3z"/><path d="M9.5 12.5l1.7 1.7 3.3-4"/></svg>
                            @endif
                        </div>
                        <span class="trend neutral trend-{{ $item['tone'] }}">GOTO</span>
                    </div>
                    <p>{{ $item['label'] }}</p>
                    <strong>{{ $item['value'] }}</strong>
                </article>
            @empty
                <article class="stat-card">
                    <p>Tidak ada summary GOTO Report yang cocok dengan filter saat ini.</p>
                </article>
            @endforelse
        </div>
    </section>

    <section class="panel filter-panel">
        <div class="panel-head">
            <div>
                <h2>MyAds Report</h2>
            </div>
        </div>
        <div class="stats-grid">
            @forelse ($myAdsSummary as $item)
                <article class="stat-card event-summary-card event-summary-card--{{ $item['tone'] }}">
                    <div class="stat-top">
                        <div class="stat-icon {{ $item['tone'] }}">
                            @if($item['icon'] === 'shield')
                                <svg viewBox="0 0 24 24"><path d="M12 3l7 3v5c0 4.6-2.7 8.8-7 10-4.3-1.2-7-5.4-7-10V6l7-3z"/><path d="M9.5 12.5l1.7 1.7 3.3-4"/></svg>
                            @elseif($item['icon'] === 'campaign')
                                <svg viewBox="0 0 24 24"><path d="M4 13V7l10-3v16l-10-3v-4"/><path d="M14 8h3a3 3 0 0 1 0 6h-3"/><path d="M6 17l1.5 3h2L8 16.4"/></svg>
                            @else
                                <svg viewBox="0 0 24 24"><path d="M12 3v18"/><path d="M16 7.5c0-1.9-1.8-3.5-4-3.5s-4 1.6-4 3.5 1.8 3 4 3 4 1.1 4 3-1.8 3.5-4 3.5-4-1.6-4-3.5"/></svg>
                            @endif
                        </div>
                        <span class="trend neutral trend-{{ $item['tone'] }}">{{ $item['label'] }}</span>
                    </div>
                    <p>{{ $item['label'] }}</p>
                    <div class="event-summary-sublist">
                        @foreach ($item['items'] as $subItem)
                            <div class="event-summary-subitem">
                                <span class="event-summary-subitem__label">{{ $subItem['label'] }}</span>
                                <strong>{{ $subItem['value'] }}</strong>
                            </div>
                        @endforeach
                    </div>
                </article>
            @empty
                <article class="stat-card">
                    <p>Tidak ada summary event yang cocok dengan filter saat ini.</p>
                </article>
            @endforelse
        </div>
    </section>

    <section class="panel filter-panel">
        <div class="panel-head">
            <div>
                <h2>Filter Event</h2>
                <p>Persempit data yang ingin ditampilkan.</p>
            </div>
        </div>
        <form method="GET" action="{{ route('event-report') }}" class="filter-form">
            <label>
                <span>Event Type</span>
                <select name="event_type">
                    <option value="">Semua event</option>
                    @foreach($eventTypes as $eventType)
                        <option value="{{ $eventType }}" @selected($filters['event_type'] === $eventType)>{{ $eventType }}</option>
                    @endforeach
                </select>
            </label>
            <label>
                <span>Dari Tanggal</span>
                <input type="date" name="date_from" value="{{ $filters['date_from'] }}">
            </label>
            <label>
                <span>Sampai Tanggal</span>
                <input type="date" name="date_to" value="{{ $filters['date_to'] }}">
            </label>
            <div class="filter-actions">
                <button type="submit" class="primary-button filter-submit">Terapkan Filter</button>
                <a href="{{ route('event-report') }}" class="secondary-button filter-reset">Reset</a>
            </div>
        </form>
    </section>

    <section class="panel activity-panel">
        <div class="panel-head">
            <div>
                <h2>Daftar Event</h2>
                <p>Menampilkan data terbaru dari tabel goto_reporting_events.</p>
            </div>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>MERCHANT ID</th>
                        <th>EVENT TYPE</th>
                        <th>SOURCE</th>
                        <th>CREATED AT</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                        <tr>
                            <td>{{ $event->merchant_id ?: '-' }}</td>
                            <td><span class="event-pill">{{ $event->event_type }}</span></td>
                            <td>{{ $event->source === 'upload' ? 'Upload Report' : 'Reporting DB' }}</td>
                            <td>{{ $event->created_at ? \Carbon\Carbon::parse($event->created_at)->format('d M Y H:i:s') : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="empty-state">Tidak ada data event yang cocok dengan filter saat ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination-wrap">
            {{ $events->onEachSide(1)->links('pagination.event-report') }}
        </div>
    </section>
@endsection
