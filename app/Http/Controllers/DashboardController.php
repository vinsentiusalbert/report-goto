<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Support\Optional;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $reporting = DB::connection('reporting');
        $reporting->reconnect();

        $data = $this->runReportingQuery(function () use ($reporting): array {
            $eventsQuery = $reporting->table('goto_reporting_events');

            $remoteEvents = (clone $eventsQuery)
                ->select(['merchant_id', 'event_type', 'created_at'])
                ->get()
                ->map(function (object $event): array {
                    return [
                        'merchant_id' => (string) $event->merchant_id,
                        'event_type' => (string) $event->event_type,
                        'created_at' => $event->created_at,
                        'source' => 'reporting',
                    ];
                });

            return [
                'remoteEvents' => $remoteEvents,
            ];
        });

        $localEvents = DB::connection('mysql')
            ->table('goto_uploaded_reports')
            ->selectRaw('merchant_id, event_type, event_created_at as created_at')
            ->get()
            ->map(function (object $event): array {
                return [
                    'merchant_id' => (string) $event->merchant_id,
                    'event_type' => (string) $event->event_type,
                    'created_at' => $event->created_at,
                    'source' => 'upload',
                ];
            });

        $mergedLatestEvents = $data['remoteEvents']
            ->concat($localEvents)
            ->sortByDesc(fn (array $event): int => strtotime((string) $event['created_at']))
            ->take(15)
            ->values();

        $myAdsSummary = [
            ['label' => 'Total Event', 'value' => number_format($data['remoteEvents']->count(), 0, ',', '.'), 'caption' => 'Data dari MyAds reporting DB', 'tone' => 'green', 'icon' => 'route'],
            ['label' => 'Merchant Unik / Hari', 'value' => number_format($this->buildUniqueDailySummary($data['remoteEvents'])->sum(), 0, ',', '.'), 'caption' => 'Unik per merchant_id per hari', 'tone' => 'blue', 'icon' => 'users'],
            ['label' => 'Jenis Event', 'value' => number_format($data['remoteEvents']->pluck('event_type')->filter()->unique()->count(), 0, ',', '.'), 'caption' => 'Total event_type MyAds', 'tone' => 'purple', 'icon' => 'stack'],
            ['label' => 'Event Terbaru', 'value' => $this->formatTimestamp($data['remoteEvents']->max('created_at')) ?? '-', 'caption' => 'created_at terbaru MyAds', 'tone' => 'orange', 'icon' => 'clock'],
        ];

        $gotoSummary = [
            ['label' => 'Total Event', 'value' => number_format($localEvents->count(), 0, ',', '.'), 'caption' => 'Data dari upload GOTO report', 'tone' => 'green', 'icon' => 'route'],
            ['label' => 'Merchant Unik / Hari', 'value' => number_format($this->buildUniqueDailySummary($localEvents)->sum(), 0, ',', '.'), 'caption' => 'Unik per merchant_id per hari', 'tone' => 'blue', 'icon' => 'users'],
            ['label' => 'Jenis Event', 'value' => number_format($localEvents->pluck('event_type')->filter()->unique()->count(), 0, ',', '.'), 'caption' => 'Total event_type GOTO', 'tone' => 'purple', 'icon' => 'stack'],
            ['label' => 'Event Terbaru', 'value' => $this->formatTimestamp($localEvents->max('created_at')) ?? '-', 'caption' => 'created_at terbaru GOTO', 'tone' => 'orange', 'icon' => 'clock'],
        ];

        return view('dashboard', [
            'gotoSummary' => $gotoSummary,
            'myAdsSummary' => $myAdsSummary,
            'latestEvents' => $mergedLatestEvents,
        ]);
    }

    private function buildUniqueDailySummary(Collection $events): Collection
    {
        return $events
            ->filter(fn (array $event): bool => ($event['merchant_id'] ?? '') !== '' && ($event['created_at'] ?? null) !== null)
            ->map(fn (array $event): array => [
                'event_type' => (string) $event['event_type'],
                'merchant_id' => (string) $event['merchant_id'],
                'event_date' => substr((string) $event['created_at'], 0, 10),
            ])
            ->unique(fn (array $event): string => implode('|', [$event['event_type'], $event['merchant_id'], $event['event_date']]))
            ->groupBy('event_type')
            ->map(fn (Collection $group): int => $group->count());
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

    private function formatTimestamp(mixed $value): ?string
    {
        if ($value instanceof Optional) {
            $value = $value->value;
        }

        if ($value === null || $value === '') {
            return null;
        }

        return Carbon::parse($value)->format('d M Y H:i');
    }
}
