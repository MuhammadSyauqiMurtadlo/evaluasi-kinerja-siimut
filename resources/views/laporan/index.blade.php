@extends('layouts.app')

@section('title', 'Laporan')
@section('page-title', 'Laporan Evaluasi')

@section('content')

    <div class="card-panel p-3 mb-4">
        <form method="GET" action="{{ route('laporan.index') }}" class="row g-3">
            <div class="col-md-3">
                <label class="form-label small">Evaluasi</label>
                <select name="evaluation_id" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach ($evaluasiOptions as $opt)
                        <option value="{{ $opt->id }}" @selected($filter['evaluation_id'] == $opt->id)>{{ $opt->kode_evaluasi }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small">Informan</label>
                <select name="informant_id" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach ($informanOptions as $opt)
                        <option value="{{ $opt->id }}" @selected($filter['informant_id'] == $opt->id)>{{ $opt->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small">Kategori</label>
                <select name="kategori" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach (['navigation' => 'Navigation', 'interaction' => 'Interaction', 'information' => 'Information', 'content' => 'Content', 'visual_ui' => 'Visual/UI', 'functionality' => 'Functionality'] as $val => $label)
                        <option value="{{ $val }}" @selected($filter['kategori'] === $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small">Severity</label>
                <select name="severity" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach (['low' => 'Low', 'medium' => 'Medium', 'high' => 'High', 'critical' => 'Critical'] as $val => $label)
                        <option value="{{ $val }}" @selected($filter['severity'] === $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-funnel"></i></button>
            </div>

            <div class="col-md-3">
                <label class="form-label small">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" class="form-control form-control-sm"
                    value="{{ $filter['tanggal_mulai'] }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small">Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" class="form-control form-control-sm"
                    value="{{ $filter['tanggal_selesai'] }}">
            </div>
            <div class="col-md-3 d-flex align-items-end gap-2">
                <a href="{{ route('laporan.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                <a href="{{ route('laporan.export-pdf', $filter) }}" class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-file-earmark-pdf"></i> Export PDF
                </a>
            </div>
        </form>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card-stat p-3 text-center">
                <div class="text-muted small">Total Evaluasi Tercakup</div>
                <div class="fs-4 fw-bold">{{ $ringkasan['total_evaluasi'] }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-stat p-3 text-center">
                <div class="text-muted small">Total Finding</div>
                <div class="fs-4 fw-bold">{{ $ringkasan['total_finding'] }}</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card-stat p-3">
                <div class="text-muted small mb-1">Distribusi Severity</div>
                @foreach ($ringkasan['per_severity'] as $sev => $jml)
                    <span class="badge badge-severity-{{ $sev }} me-1">{{ ucfirst($sev) }}:
                        {{ $jml }}</span>
                @endforeach
            </div>
        </div>
    </div>

    <div class="card-panel p-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="text-muted small text-uppercase">
                    <tr>
                        <th>Evaluasi</th>
                        <th>Informan</th>
                        <th>Judul Finding</th>
                        <th>Kategori</th>
                        <th>Severity</th>
                        <th>Tanggal Sesi</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($findings as $f)
                        <tr>
                            <td class="fw-semibold">{{ $f->evaluation->kode_evaluasi }}</td>
                            <td>{{ $f->evaluation->informant->nama ?? '-' }}</td>
                            <td>{{ $f->judul }}</td>
                            <td>{{ ucfirst(str_replace('_', '/', $f->kategori)) }}</td>
                            <td><span class="badge badge-severity-{{ $f->severity }}">{{ ucfirst($f->severity) }}</span>
                            </td>
                            <td class="small text-muted">
                                {{ optional($f->evaluation->session)->tanggal?->translatedFormat('d M Y') ?? '-' }}</td>
                            <td class="text-end">
                                <a href="{{ route('evaluasi.show', $f->evaluation_id) }}"
                                    class="btn btn-sm btn-outline-primary">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Tidak ada data sesuai filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
