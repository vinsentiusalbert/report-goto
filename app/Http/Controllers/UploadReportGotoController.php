<?php

namespace App\Http\Controllers;

use App\Models\GotoUploadedReport;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class UploadReportGotoController extends Controller
{
    private const ALLOWED_EVENT_TYPES = [
        'solusi mitra usaha shown' => 'Solusi Mitra Usaha Shown',
        'solusi mitra usaha clicked' => 'Solusi Mitra Usaha Clicked',
        'redirected to partner site' => 'Redirected to Partner Site',
        'accept consent authentication' => 'Accept Consent Authentication',
    ];

    public function create(): View
    {
        $recentUploads = GotoUploadedReport::query()
            ->selectRaw('source_file, COUNT(*) as total_rows, MAX(created_at) as uploaded_at')
            ->whereNotNull('source_file')
            ->groupBy('source_file')
            ->orderByDesc('uploaded_at')
            ->limit(10)
            ->get();

        return view('upload-report-goto', [
            'recentUploads' => $recentUploads,
            'allowedEventTypes' => array_values(self::ALLOWED_EVENT_TYPES),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'report_file' => ['required', 'file', 'mimes:csv,txt', 'max:10240'],
        ]);

        $file = $validated['report_file'];
        $rows = $this->parseCsvFile($file->getRealPath());

        if ($rows === []) {
            return back()->withErrors([
                'report_file' => 'File tidak berisi data yang valid.',
            ]);
        }

        $eventDates = collect($rows)
            ->map(fn (array $row): string => substr($row['event_created_at'], 0, 10))
            ->unique()
            ->values();

        DB::connection('mysql')->transaction(function () use ($rows, $file, $request, $eventDates): void {
            GotoUploadedReport::query()
                ->where(function ($query) use ($eventDates): void {
                    foreach ($eventDates as $eventDate) {
                        $query->orWhereDate('event_created_at', $eventDate);
                    }
                })
                ->delete();

            foreach (array_chunk($rows, 500) as $chunk) {
                GotoUploadedReport::query()->insert(array_map(
                    fn (array $row): array => [
                        'merchant_id' => $row['merchant_id'],
                        'event_type' => $row['event_type'],
                        'event_created_at' => $row['event_created_at'],
                        'source_file' => $file->getClientOriginalName(),
                        'uploaded_by' => $request->user()?->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    $chunk
                ));
            }
        });

        return redirect()
            ->route('upload-report-goto.create')
            ->with('status', count($rows).' baris report berhasil diupload untuk tanggal '.implode(', ', $eventDates->all()).'.');
    }

    /**
     * @return array<int, array{merchant_id: string|null, event_type: string, event_created_at: string}>
     */
    private function parseCsvFile(string $path): array
    {
        $handle = fopen($path, 'rb');

        if ($handle === false) {
            throw new RuntimeException('Gagal membaca file upload.');
        }

        $header = fgetcsv($handle);

        if ($header === false) {
            fclose($handle);

            return [];
        }

        $indexes = $this->resolveColumnIndexes($header);
        $rows = [];

        while (($data = fgetcsv($handle)) !== false) {
            if ($this->isEmptyCsvRow($data)) {
                continue;
            }

            $eventType = $this->normalizeEventType($data[$indexes['event_type']] ?? '');
            $createdAt = trim((string) ($data[$indexes['created_at']] ?? ''));

            if ($eventType === '' || $createdAt === '') {
                continue;
            }

            $rows[] = [
                'merchant_id' => $this->normalizeNullableString($data[$indexes['merchant_id']] ?? null),
                'event_type' => $eventType,
                'event_created_at' => Carbon::parse($createdAt)->format('Y-m-d H:i:s'),
            ];
        }

        fclose($handle);

        return $rows;
    }

    /**
     * @param array<int, string|null> $header
     * @return array{merchant_id: int, event_type: int, created_at: int}
     */
    private function resolveColumnIndexes(array $header): array
    {
        $normalized = collect($header)
            ->map(fn ($value): string => strtolower(trim((string) $value)))
            ->values();

        $merchantIdIndex = $normalized->search('merchant_id');
        $eventTypeIndex = $normalized->search('event_type');
        $createdAtIndex = $normalized->search('created_at');

        if ($merchantIdIndex === false || $eventTypeIndex === false || $createdAtIndex === false) {
            throw new RuntimeException('Header CSV harus berisi merchant_id, event_type, dan created_at.');
        }

        return [
            'merchant_id' => $merchantIdIndex,
            'event_type' => $eventTypeIndex,
            'created_at' => $createdAtIndex,
        ];
    }

    /**
     * @param array<int, string|null> $row
     */
    private function isEmptyCsvRow(array $row): bool
    {
        foreach ($row as $value) {
            if (trim((string) $value) !== '') {
                return false;
            }
        }

        return true;
    }

    private function normalizeNullableString(mixed $value): ?string
    {
        $normalized = trim((string) $value);

        return $normalized === '' ? null : $normalized;
    }

    private function normalizeEventType(mixed $value): string
    {
        $normalized = strtolower(trim((string) $value));

        if ($normalized === '') {
            return '';
        }

        if (! array_key_exists($normalized, self::ALLOWED_EVENT_TYPES)) {
            throw new RuntimeException(
                'Event type tidak valid. Gunakan salah satu dari: '.implode(', ', array_values(self::ALLOWED_EVENT_TYPES)).'.'
            );
        }

        return self::ALLOWED_EVENT_TYPES[$normalized];
    }
}
