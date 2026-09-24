@extends('layouts.app')

@section('title', 'Evaluasi')
@section('page-title', 'Daftar Evaluasi')

@section('content')
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('evaluasi.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Evaluasi Baru
        </a>
    </div>

    <div class="card-panel p-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="text-muted small text-uppercase">
                    <tr>
                        <th>Kode</th>
                        <th>Informan</th>
                        <th>Tanggal Sesi</th>
                        <th>Status</th>
                        <th>Task</th>
                        <th>Pain Point</th>
                        <th>Finding</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($evaluasi as $ev)
                        <tr>
                            <td class="fw-semibold">{{ $ev->kode_evaluasi }}</td>
                            <td>{{ $ev->informant->nama ?? '-' }}</td>
                            <td>{{ optional($ev->session)->tanggal?->translatedFormat('d M Y') ?? '-' }}</td>
                            <td>
                                <span
                                    class="badge {{ $ev->status === 'selesai' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                                    {{ ucfirst($ev->status) }}
                                </span>
                            </td>
                            <td>{{ $ev->tasks_count }}</td>
                            <td>{{ $ev->pain_points_count }}</td>
                            <td>{{ $ev->findings_count }}</td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('evaluasi.show', $ev) }}" class="btn btn-outline-primary"><i
                                            class="bi bi-eye"></i></a>
                                    <a href="{{ route('evaluasi.edit', $ev) }}" class="btn btn-outline-secondary"><i
                                            class="bi bi-pencil"></i></a>
                                    <form action="{{ route('evaluasi.destroy', $ev) }}" method="POST"
                                        onsubmit="return confirm('Hapus evaluasi {{ $ev->kode_evaluasi }} beserta seluruh datanya?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger"><i
                                                class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Belum ada data evaluasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $evaluasi->links() }}</div>
    </div>
@endsection
