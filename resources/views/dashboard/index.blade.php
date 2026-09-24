@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

    <div class="row g-3 mb-4">
        @php
            $cards = [
                [
                    'label' => 'Total Evaluasi',
                    'value' => $stats['total_evaluasi'],
                    'icon' => 'bi-journal-text',
                    'color' => '#4f46e5',
                    'bg' => '#eef2ff',
                ],
                [
                    'label' => 'Total Informan',
                    'value' => $stats['total_informan'],
                    'icon' => 'bi-people-fill',
                    'color' => '#0891b2',
                    'bg' => '#ecfeff',
                ],
                [
                    'label' => 'Total Task',
                    'value' => $stats['total_task'],
                    'icon' => 'bi-list-check',
                    'color' => '#16a34a',
                    'bg' => '#f0fdf4',
                ],
                [
                    'label' => 'Total Pain Point',
                    'value' => $stats['total_pain_point'],
                    'icon' => 'bi-exclamation-triangle-fill',
                    'color' => '#d97706',
                    'bg' => '#fffbeb',
                ],
                [
                    'label' => 'Total Finding',
                    'value' => $stats['total_finding'],
                    'icon' => 'bi-search',
                    'color' => '#dc2626',
                    'bg' => '#fef2f2',
                ],
            ];
        @endphp

        @foreach ($cards as $card)
            <div class="col-6 col-md-4 col-xl-2dot4" style="flex: 1 0 19%;">
                <div class="card-stat p-3 h-100">
                    <div class="icon-box mb-2" style="background: {{ $card['bg'] }}; color: {{ $card['color'] }};">
                        <i class="bi {{ $card['icon'] }}"></i>
                    </div>
                    <div class="text-muted small">{{ $card['label'] }}</div>
                    <div class="fs-4 fw-bold">{{ $card['value'] }}</div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-6">
            <div class="card-panel p-3 h-100">
                <h6 class="fw-semibold mb-3">Finding Berdasarkan Kategori</h6>
                <canvas id="chartKategori" height="220"></canvas>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card-panel p-3 h-100">
                <h6 class="fw-semibold mb-3">Finding Berdasarkan Severity</h6>
                <canvas id="chartSeverity" height="220"></canvas>
            </div>
        </div>
    </div>

    <div class="card-panel p-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-semibold mb-0">Evaluasi Terbaru</h6>
            <a href="{{ route('evaluasi.index') }}" class="small text-decoration-none">Lihat semua <i
                    class="bi bi-arrow-right"></i></a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="text-muted small text-uppercase">
                    <tr>
                        <th>Kode</th>
                        <th>Informan</th>
                        <th>Status</th>
                        <th>Jumlah Finding</th>
                        <th>Tanggal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($evaluasiTerbaru as $ev)
                        <tr>
                            <td class="fw-semibold">{{ $ev->kode_evaluasi }}</td>
                            <td>{{ $ev->informant->nama ?? '-' }}</td>
                            <td>
                                <span
                                    class="badge {{ $ev->status === 'selesai' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                                    {{ ucfirst($ev->status) }}
                                </span>
                            </td>
                            <td>{{ $ev->findings_count }}</td>
                            <td class="text-muted small">{{ $ev->created_at->translatedFormat('d M Y') }}</td>
                            <td class="text-end">
                                <a href="{{ route('evaluasi.show', $ev) }}" class="btn btn-sm btn-outline-primary">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada data evaluasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        const kategoriData = @json($findingPerKategori);
        const severityData = @json($findingPerSeverity);

        const kategoriLabelMap = {
            navigation: 'Navigation',
            interaction: 'Interaction',
            information: 'Information',
            content: 'Content',
            visual_ui: 'Visual/UI',
            functionality: 'Functionality',
        };

        new Chart(document.getElementById('chartKategori'), {
            type: 'bar',
            data: {
                labels: Object.keys(kategoriData).map(k => kategoriLabelMap[k] ?? k),
                datasets: [{
                    label: 'Jumlah Finding',
                    data: Object.values(kategoriData),
                    backgroundColor: '#4f46e5',
                    borderRadius: 6,
                    maxBarThickness: 40,
                }],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                },
            },
        });

        const severityColor = {
            low: '#10b981',
            medium: '#f59e0b',
            high: '#f97316',
            critical: '#ef4444',
        };
        const severityLabelMap = {
            low: 'Low',
            medium: 'Medium',
            high: 'High',
            critical: 'Critical'
        };

        new Chart(document.getElementById('chartSeverity'), {
            type: 'doughnut',
            data: {
                labels: Object.keys(severityData).map(k => severityLabelMap[k] ?? k),
                datasets: [{
                    data: Object.values(severityData),
                    backgroundColor: Object.keys(severityData).map(k => severityColor[k] ?? '#9ca3af'),
                    borderWidth: 0,
                }],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
            },
        });
    </script>
@endpush
