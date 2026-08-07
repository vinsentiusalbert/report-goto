<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DailyEventReportController extends Controller
{
    private const GOTO_EVENT_TYPES = [
        'Solusi Mitra Usaha Shown',
        'Solusi Mitra Usaha Clicked',
        'Redirected to Partner Site',
        'Accept Consent Authentication',
    ];

    private const MYADS_EVENT_TYPES = [
        'sso_initiated',
        'sso_success',
        'click_product_inventory',
        'top_up_initiated',
        'top_up_success',
        'campaign_activation',
    ];

    public function __invoke(Request $request): View
    {
        $reporting = DB::connection('reporting');
        $reporting->reconnect();

        $dateTo = $request->string('date_to')->toString() ?: Carbon::now()->format('Y-m-d');
        $dateFrom = $request->string('date_from')->toString() ?: Carbon::parse($dateTo)->subDays(6)->format('Y-m-d');

        $data = $this->runReportingQuery(function () use ($reporting, $dateFrom, $dateTo): array {
            $rows = $reporting->table('vw_goto_reporting_events')
                ->selectRaw('event_type, DATE(created_at) as event_date, COUNT(*) as total')
                ->whereDate('created_at', '>=', $dateFrom)
                ->whereDate('created_at', '<=', $dateTo)
                ->groupBy('event_type', DB::raw('DATE(created_at)'))
                ->orderBy('event_type')
                ->orderBy('event_date')
                ->get();

            return [
                'rows' => $rows,
            ];
        });

        $gotoRows = DB::connection('mysql')
            ->table('goto_uploaded_reports')
            ->selectRaw('event_type, DATE(event_created_at) as event_date, COUNT(*) as total')
            ->whereDate('event_created_at', '>=', $dateFrom)
            ->whereDate('event_created_at', '<=', $dateTo)
            ->groupBy('event_type', DB::raw('DATE(event_created_at)'))
            ->orderBy('event_type')
            ->orderBy('event_date')
            ->get();

        $dates = collect();
        $cursor = Carbon::parse($dateFrom);
        $lastDate = Carbon::parse($dateTo);

        while ($cursor->lte($lastDate)) {
            $dates->push($cursor->format('Y-m-d'));
            $cursor->addDay();
        }

        $myAdsReport = $this->buildDailyReport($data['rows'], $dates, self::MYADS_EVENT_TYPES);
        $gotoReport = $this->buildDailyReport($gotoRows, $dates, self::GOTO_EVENT_TYPES);

        return view('daily-event-report', [
            'dates' => $dates,
            'gotoReport' => $gotoReport,
            'myAdsReport' => $myAdsReport,
            'filters' => [
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
            ],
        ]);
    }

    private function buildDailyReport(Collection $rows, Collection $dates, ?array $orderedEventTypes = null): Collection
    {
        $report = $rows
            ->groupBy('event_type')
            ->map(function (Collection $group, string $eventType) use ($dates): array {
                $totalsByDate = $group->mapWithKeys(fn (object $item): array => [
                    (string) $item->event_date => (int) $item->total,
                ]);

                $values = $dates->mapWithKeys(fn (string $date): array => [
                    $date => $totalsByDate->get($date, 0),
                ]);

                return [
                    'event_type' => $eventType,
                    'values' => $values,
                    'total' => $values->sum(),
                ];
            })
            ->sortKeys()
            ->values();

        if ($orderedEventTypes === null) {
            return $report;
        }

        $reportByEventType = $report->keyBy('event_type');

        return collect($orderedEventTypes)->map(function (string $eventType) use ($reportByEventType, $dates): array {
            return $reportByEventType->get($eventType, [
                'event_type' => $eventType,
                'values' => $dates->mapWithKeys(fn (string $date): array => [$date => 0]),
                'total' => 0,
            ]);
        });
    }

    private function runReportingQuery(callable $callback): array
    {
        try {
            return $callback();
        } catch (QueryException $exception) {
            if (! $this->isGoneAway($exception)) {
                throw $exception;
            }

            DB::purge('reporting');
            DB::reconnect('reporting');

            return $callback();
        }
    }

    private function isGoneAway(QueryException $exception): bool
    {
        $message = strtolower($exception->getMessage());

        return str_contains($message, 'server has gone away')
            || str_contains($message, 'lost connection')
            || str_contains($message, '[2006]');
    }
}
