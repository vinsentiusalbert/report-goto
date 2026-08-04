@extends('layouts.dashboard')

@section('title', 'Upload Report Goto')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow text-green">IMPORT</p>
            <h1>Upload Report Goto</h1>
            <p>Upload file CSV dengan header <strong>merchant_id</strong>, <strong>event_type</strong>, dan <strong>created_at</strong>.</p>
        </div>
    </div>

    <section class="panel filter-panel">
        <div class="panel-head">
            <div>
                <h2>Upload File</h2>
                <p>Data upload akan digabung dengan daftar event di halaman Event Report.</p>
            </div>
        </div>

        <div class="upload-helper">
            <span>Butuh contoh format file?</span>
            <a href="{{ asset('downloads/goto-report-sample.csv') }}" download>Download CSV contoh</a>
        </div>


        @if (session('status'))
            <div class="flash-message flash-message--success">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="flash-message flash-message--error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('upload-report-goto.store') }}" enctype="multipart/form-data" class="upload-form">
            @csrf
            <label class="upload-field">
                <span>Pilih File CSV</span>
                <input type="file" name="report_file" accept=".csv,text/csv,.txt">
            </label>
            <button type="submit" class="primary-button upload-submit">Upload Report</button>
        </form>
    </section>

    <section class="panel activity-panel">
        <div class="panel-head">
            <div>
                <h2>Riwayat Upload</h2>
                <p>10 file upload terakhir yang berhasil disimpan.</p>
            </div>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>NAMA FILE</th>
                        <th>TOTAL BARIS</th>
                        <th>WAKTU UPLOAD</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentUploads as $upload)
                        <tr>
                            <td>{{ $upload->source_file ?: '-' }}</td>
                            <td>{{ number_format((int) $upload->total_rows, 0, ',', '.') }}</td>
                            <td>{{ $upload->uploaded_at ? \Carbon\Carbon::parse($upload->uploaded_at)->format('d M Y H:i:s') : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="empty-state">Belum ada file report yang diupload.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
