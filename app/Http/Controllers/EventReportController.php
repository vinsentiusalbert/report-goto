<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventReportController extends Controller
{
    public function __invoke(Request $request): View
    {
        $reporting = DB::connection('reporting');
        $reporting->reconnect();

        $filters = [
            'event_type' => trim((string) $request->string('event_type')),
            'date_from' => trim((string) $request->string('date_from')),
            'date_to' => trim((string) $request->string('date_to')),
        ];

        $data = $this->runReportingQuery(function () use ($reporting, $filters): array {
            $remoteBaseQuery = $reporting->table('goto_reporting_events');
            $remoteFilteredQuery = $this->applyFilters(clone $remoteBaseQuery, $filters);

            $remoteEventTypes = (clone $remoteBaseQuery)
                ->select('event_type')
                ->distinct()
                ->orderBy('event_type')
                ->pluck('event_type')
                ->filter()
                ->values();

            $remoteEvents = (clone $remoteFilteredQuery)
                ->select(['id', 'merchant_id', 'user_id', 'event_type', 'created_at'])
                ->get()
                ->map(function (object $event): object {
                    $event->source = 'reporting';

                    return $event;
                });

            return [
                'remoteEvents' => $remoteEvents,
                'remoteEventTypes' => $remoteEventTypes,
            ];
        });

        $localBaseQuery = DB::connection('mysql')->table('goto_uploaded_reports');
        $localFilteredQuery = $this->applyFilters(clone $localBaseQuery, $filters, 'event_created_at');

        $localEventTypes = (clone $localBaseQuery)
            ->select('event_type')
            ->distinct()
            ->orderBy('event_type')
            ->pluck('event_type')
            ->filter()
            ->values();

        $localEvents = (clone $localFilteredQuery)
            ->selectRaw('id, merchant_id, null as user_id, event_type, event_created_at as created_at')
            ->get()
            ->map(function (object $event): object {
                $event->source = 'upload';

                return $event;
            });

        $mergedEvents = $data['remoteEvents']
            ->concat($localEvents)
            ->sortByDesc(fn (object $event): int => strtotime((string) $event->created_at))
            ->values();

        $currentPage = max(1, (int) $request->integer('page', 1));
        $perPage = 25;
        $paginatedEvents = new LengthAwarePaginator(
            $mergedEvents->slice(($currentPage - 1) * $perPage, $perPage)->values(),
            $mergedEvents->count(),
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        $remoteSummaryByEventType = $this->buildUniqueDailySummary($data['remoteEvents']);
        $localSummaryByEventType = $this->buildUniqueDailySummary($localEvents);

        $gotoReportSummary = collect([
            ['label' => 'Solusi Mitra Usaha Shown', 'tone' => 'blue', 'icon' => 'eye'],
            ['label' => 'Solusi Mitra Usaha Clicked', 'tone' => 'green', 'icon' => 'cursor'],
            ['label' => 'Redirected to Partner Site', 'tone' => 'orange', 'icon' => 'arrow'],
            ['label' => 'Accept Consent Authentication', 'tone' => 'purple', 'icon' => 'shield'],
        ])->map(function (array $item) use ($localSummaryByEventType): array {
            return [
                'label' => $item['label'],
                'tone' => $item['tone'],
                'icon' => $item['icon'],
                'value' => number_format($localSummaryByEventType->get($item['label'], 0), 0, ',', '.'),
            ];
        });

        $groupedEvents = [
            [
                'label' => 'SSO',
                'tone' => 'blue',
                'icon' => 'shield',
                'events' => [
                    'sso_initiated' => 'SSO Initiated',
                    'sso_success' => 'SSO Success',
                ],
            ],
            [
                'label' => 'Campaign',
                'tone' => 'orange',
                'icon' => 'campaign',
                'events' => [
                    'click_product_inventory' => 'Click Product Inventory',
                    'campaign_activation' => 'Campaign Activation',
                ],
            ],
            [
                'label' => 'Payment',
                'tone' => 'green',
                'icon' => 'money',
                'events' => [
                    'top_up_initiated' => 'Top Up Initiated',
                    'top_up_success' => 'Top Up Success',
                ],
            ],
        ];

        $myAdsSummary = collect($groupedEvents)->map(function (array $group) use ($remoteSummaryByEventType): array {
            $items = collect($group['events'])->map(function (string $eventLabel, string $eventType) use ($remoteSummaryByEventType): array {
                $total = $remoteSummaryByEventType->get($eventType, 0);

                return [
                    'label' => $eventLabel,
                    'value' => number_format($total, 0, ',', '.'),
                ];
            });

            return [
                'label' => $group['label'],
                'tone' => $group['tone'],
                'icon' => $group['icon'],
                'total' => number_format($items->sum(fn (array $item): int => (int) str_replace('.', '', $item['value'])), 0, ',', '.'),
                'items' => $items,
            ];
        });

        return view('event-report', [
            'events' => $paginatedEvents,
            'eventTypes' => $data['remoteEventTypes']->concat($localEventTypes)->unique()->sort()->values(),
            'filters' => $filters,
            'gotoReportSummary' => $gotoReportSummary,
            'myAdsSummary' => $myAdsSummary,
        ]);
    }

    private function buildUniqueDailySummary($events)
    {
        return $events
            ->filter(fn (object $event): bool => $event->merchant_id !== null && $event->merchant_id !== '' && $event->created_at !== null)
            ->map(fn (object $event): array => [
                'event_type' => (string) $event->event_type,
                'merchant_id' => (string) $event->merchant_id,
                'event_date' => substr((string) $event->created_at, 0, 10),
            ])
            ->unique(fn (array $event): string => implode('|', [$event['event_type'], $event['merchant_id'], $event['event_date']]))
            ->groupBy('event_type')
            ->map(fn ($group): int => $group->count());
    }

    private function applyFilters(object $query, array $filters, string $createdAtColumn = 'created_at'): object
    {
        if ($filters['event_type'] !== '') {
            $query->where('event_type', $filters['event_type']);
        }

        if ($filters['date_from'] !== '') {
            $query->whereDate($createdAtColumn, '>=', $filters['date_from']);
        }

        if ($filters['date_to'] !== '') {
            $query->whereDate($createdAtColumn, '<=', $filters['date_to']);
        }

        return $query;
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
