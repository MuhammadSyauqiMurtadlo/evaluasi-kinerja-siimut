<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 11px;
            color: #1f2937;
        }

        h1 {
            font-size: 16px;
            margin-bottom: 2px;
        }

        .subtitle {
            color: #6b7280;
            font-size: 10px;
            margin-bottom: 16px;
        }

        .summary-box {
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 16px;
        }

        .summary-row {
            display: table;
            width: 100%;
            margin-bottom: 4px;
        }

        .summary-item {
            display: table-cell;
            width: 25%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        th,
        td {
            border: 1px solid #e5e7eb;
            padding: 6px 8px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #f3f4f6;
            font-size: 10px;
            text-transform: uppercase;
        }

        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            color: #fff;
        }

        .sev-low {
            background-color: #10b981;
        }

        .sev-medium {
            background-color: #f59e0b;
        }

        .sev-high {
            background-color: #f97316;
        }

        .sev-critical {
            background-color: #ef4444;
        }

        .footer {
            margin-top: 20px;
            font-size: 9px;
            color: #9ca3af;
            text-align: right;
        }

        .filter-info {
            font-size: 9px;
            color: #6b7280;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <h1>Laporan Evaluasi Kinerja SI-IMUT</h1>
    <div class="subtitle">Evaluasi UX/Usability dengan Metode Contextual Inquiry</div>

    <div class="filter-info">
        Filter diterapkan:
        @if (!empty($filter['evaluation_id']))
            Evaluasi ID #{{ $filter['evaluation_id'] }} ·
        @endif
        @if (!empty($filter['kategori']))
            Kategori: {{ ucfirst(str_replace('_', '/', $filter['kategori'])) }} ·
        @endif
        @if (!empty($filter['severity']))
            Severity: {{ ucfirst($filter['severity']) }} ·
        @endif
        @if (!empty($filter['tanggal_mulai']))
            Dari: {{ $filter['tanggal_mulai'] }} ·
        @endif
        @if (!empty($filter['tanggal_selesai']))
            Sampai: {{ $filter['tanggal_selesai'] }}
        @endif
        @if (collect($filter)->filter()->isEmpty())
            Tidak ada filter (menampilkan seluruh data)
        @endif
    </div>

    <div class="summary-box">
        <div class="summary-row">
            <div class="summary-item"><strong>Total Evaluasi Tercakup:</strong> {{ $ringkasan['total_evaluasi'] }}</div>
            <div class="summary-item"><strong>Total Finding:</strong> {{ $ringkasan['total_finding'] }}</div>
        </div>
        <div class="summary-row">
            <div class="summary-item" style="width:100%;">
                <strong>Distribusi Severity:</strong>
                @foreach ($ringkasan['per_severity'] as $sev => $jml)
                    <span class="badge sev-{{ $sev }}">{{ ucfirst($sev) }}: {{ $jml }}</span>
                @endforeach
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Evaluasi</th>
                <th>Informan</th>
                <th>Judul Finding</th>
                <th>Kategori</th>
                <th>Severity</th>
                <th>Deskripsi</th>
                <th>Root Cause</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($findings as $f)
                <tr>
                    <td>{{ $f->evaluation->kode_evaluasi }}</td>
                    <td>{{ $f->evaluation->informant->nama ?? '-' }}</td>
                    <td>{{ $f->judul }}</td>
                    <td>{{ ucfirst(str_replace('_', '/', $f->kategori)) }}</td>
                    <td><span class="badge sev-{{ $f->severity }}">{{ ucfirst($f->severity) }}</span></td>
                    <td>{{ $f->deskripsi }}</td>
                    <td>{{ $f->root_cause ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align:center;">Tidak ada data sesuai filter.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada {{ $dicetakPada->translatedFormat('d F Y, H:i') }} WIB
    </div>
</body>

</html>
