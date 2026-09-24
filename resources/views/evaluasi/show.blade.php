@extends('layouts.app')

@section('title', 'Detail Evaluasi')
@section('page-title', 'Detail Evaluasi: ' . $evaluasi->kode_evaluasi)

@section('content')

    <div class="d-flex justify-content-end gap-2 mb-3">
        <a href="{{ route('evaluasi.edit', $evaluasi) }}" class="btn btn-outline-secondary"><i class="bi bi-pencil"></i>
            Edit</a>
        <a href="{{ route('evaluasi.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>

    <div class="card-panel p-3 mb-3">
        <h6 class="fw-semibold"><i class="bi bi-person-badge"></i> Informan</h6>
        <div class="row small">
            <div class="col-md-3"><strong>Kode:</strong> {{ $evaluasi->informant->kode ?? '-' }}</div>
            <div class="col-md-3"><strong>Nama:</strong> {{ $evaluasi->informant->nama ?? '-' }}</div>
            <div class="col-md-3"><strong>Jabatan:</strong> {{ $evaluasi->informant->jabatan ?? '-' }}</div>
            <div class="col-md-3"><strong>Unit:</strong> {{ $evaluasi->informant->unit ?? '-' }}</div>
        </div>
    </div>

    <div class="card-panel p-3 mb-3">
        <h6 class="fw-semibold"><i class="bi bi-calendar-event"></i> Sesi</h6>
        <div class="row small">
            <div class="col-md-3"><strong>Tanggal:</strong>
                {{ optional($evaluasi->session)->tanggal?->translatedFormat('d M Y') ?? '-' }}</div>
            <div class="col-md-3"><strong>Durasi:</strong> {{ $evaluasi->session->durasi ?? '-' }}</div>
            <div class="col-md-6"><strong>Tujuan:</strong> {{ $evaluasi->session->tujuan ?? '-' }}</div>
        </div>
    </div>

    <div class="card-panel p-3 mb-3">
        <h6 class="fw-semibold"><i class="bi bi-list-check"></i> Task & Observasi ({{ $evaluasi->tasks->count() }})</h6>
        <div class="accordion" id="showTaskAccordion">
            @foreach ($evaluasi->tasks as $i => $task)
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#showTask{{ $i }}">
                            Task #{{ $i + 1 }}: {{ \Illuminate\Support\Str::limit($task->tujuan, 60) }}
                            <span
                                class="badge ms-2 {{ $task->status === 'berhasil' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                                {{ $task->status ? ucfirst($task->status) : '-' }}
                            </span>
                        </button>
                    </h2>
                    <div id="showTask{{ $i }}" class="accordion-collapse collapse">
                        <div class="accordion-body small">
                            <p><strong>Instruksi:</strong> {{ $task->instruksi ?? '-' }}</p>
                            <p><strong>Waktu Penyelesaian:</strong> {{ $task->waktu_penyelesaian ?? '-' }}</p>
                            @if ($obs = $task->observations->first())
                                <hr>
                                <p><strong>Tindakan:</strong> {{ $obs->tindakan ?? '-' }}</p>
                                <p><strong>Kesulitan:</strong> {{ $obs->kesulitan ?? '-' }}</p>
                                <p><strong>Strategi Pengguna:</strong> {{ $obs->strategi_pengguna ?? '-' }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card-panel p-3 h-100">
                <h6 class="fw-semibold"><i class="bi bi-exclamation-triangle"></i> Pain Point
                    ({{ $evaluasi->painPoints->count() }})</h6>
                <ul class="list-group list-group-flush">
                    @forelse ($evaluasi->painPoints as $pp)
                        <li class="list-group-item small">{{ $pp->deskripsi }}</li>
                    @empty
                        <li class="list-group-item small text-muted">Tidak ada pain point.</li>
                    @endforelse
                </ul>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card-panel p-3 h-100">
                <h6 class="fw-semibold"><i class="bi bi-search"></i> Usability Finding ({{ $evaluasi->findings->count() }})
                </h6>
                @forelse ($evaluasi->findings as $f)
                    <div class="border rounded p-2 mb-2 small">
                        <div class="d-flex justify-content-between">
                            <strong>{{ $f->judul }}</strong>
                            <span class="badge badge-severity-{{ $f->severity }}">{{ ucfirst($f->severity) }}</span>
                        </div>
                        <div class="text-muted">{{ ucfirst(str_replace('_', '/', $f->kategori)) }} ·
                            {{ $f->frequency ?? '-' }}</div>
                        <p class="mb-0 mt-1">{{ $f->deskripsi }}</p>
                    </div>
                @empty
                    <p class="small text-muted">Tidak ada finding.</p>
                @endforelse
            </div>
        </div>
    </div>

@endsection
