@extends('layouts.dashboard')

@section('title', 'Daily Event Report')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow text-green">REPORTING</p>
            <h1>Daily Event Report</h1>
        </div>
    </div>

    <section class="panel filter-panel">
        <div class="panel-head">
            <div>
                <h2>Filter Tanggal</h2>
                <p>Pilih rentang tanggal untuk report harian.</p>
            </div>
        </div>
        <form method="GET" action="{{ route('daily-event-report') }}" class="filter-form">
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
                <a href="{{ route('daily-event-report') }}" class="secondary-button filter-reset">Reset</a>
            </div>
        </form>
    </section>

    <section class="panel activity-panel">
        <div class="panel-head">
            <div>
                <h2>GOTO Report</h2>
            </div>
        </div>
        <div class="table-wrap">
            <table class="daily-report-table">
                <thead>
                    <tr>
                        <th>EVENT TYPE</th>
                        @foreach($dates as $date)
                            <th>{{ \Carbon\Carbon::parse($date)->format('d M') }}</th>
                        @endforeach
                        <th>TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gotoReport as $row)
                        <tr>
                            <td><span class="event-pill">{{ $row['event_type'] }}</span></td>
                            @foreach($dates as $date)
                                <td>{{ number_format((int) $row['values'][$date], 0, ',', '.') }}</td>
                            @endforeach
                            <td><strong>{{ number_format((int) $row['total'], 0, ',', '.') }}</strong></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $dates->count() + 2 }}" class="empty-state">Tidak ada data harian GOTO pada rentang tanggal ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="panel activity-panel">
        <div class="panel-head">
            <div>
                <h2>MyAds Report</h2>
                
            </div>
        </div>
        <div class="table-wrap">
            <table class="daily-report-table">
                <thead>
                    <tr>
                        <th>EVENT TYPE</th>
                        @foreach($dates as $date)
                            <th>{{ \Carbon\Carbon::parse($date)->format('d M') }}</th>
                        @endforeach
                        <th>TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($myAdsReport as $row)
                        <tr>
                            <td><span class="event-pill">{{ $row['event_type'] }}</span></td>
                            @foreach($dates as $date)
                                <td>{{ number_format((int) $row['values'][$date], 0, ',', '.') }}</td>
                            @endforeach
                            <td><strong>{{ number_format((int) $row['total'], 0, ',', '.') }}</strong></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $dates->count() + 2 }}" class="empty-state">Tidak ada data harian MyAds pada rentang tanggal ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
