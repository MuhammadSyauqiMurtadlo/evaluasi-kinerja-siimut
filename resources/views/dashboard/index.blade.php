@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <div class="row g-3 mb-4 justify-content-center">
        <div class="col-md-4">
            <div class="card-stat p-3 text-center">
                <div class="text-muted small">Total Responden</div>
                <div class="fs-3 fw-bold">{{ $totalResponden }}</div>
            </div>
        </div>
    </div>

    <div class="card-panel p-3 mb-4">
        <h6 class="fw-semibold mb-3">Severity Rating per Kategori</h6>
        <div class="row g-3">
            @foreach ($perKategori as $kategori => $data)
                <div class="col-6 col-md-4 col-xl" style="flex: 1 0 19%;">
                    <div class="border rounded p-3 text-center h-100">
                        <div class="text-muted small text-capitalize">{{ $kategori }}</div>
                        <div class="fs-4 fw-bold">{{ $data['avg_sr'] ?? '-' }}</div>
                        @if ($data['klasifikasi'])
                            <span
                                class="badge bg-{{ $data['klasifikasi']['warna'] }}-subtle text-{{ $data['klasifikasi']['warna'] }} small">
                                {{ $data['klasifikasi']['label'] }}
                            </span>
                        @else
                            <span class="badge bg-light text-muted small">Belum ada data</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-7">
            <div class="card-panel p-3 h-100">
                <h6 class="fw-semibold mb-3">Severity Rating per Pertanyaan</h6>
                <canvas id="chartSrPertanyaan" height="320"></canvas>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card-panel p-3 h-100 d-flex flex-column">
                <h6 class="fw-semibold mb-3">Distribusi Jawaban (Seluruh Pertanyaan)</h6>
                <canvas id="chartDistribusi" height="280"></canvas>
            </div>
        </div>
    </div>

    <div class="card-panel p-3">
        <h6 class="fw-semibold mb-3">Detail Severity Rating per Pertanyaan</h6>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="text-muted small text-uppercase">
                    <tr>
                        <th>Kode</th>
                        <th>Kategori</th>
                        <th>Pertanyaan</th>
                        <th>Ya</th>
                        <th>Tidak</th>
                        <th>Rata² F</th>
                        <th>Rata² D</th>
                        <th>SR</th>
                        <th>Klasifikasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($perPertanyaan->sortByDesc('sr') as $item)
                        <tr>
                            <td>{{ $item['question']->kode }}</td>
                            <td class="text-capitalize">{{ $item['question']->kategori }}</td>
                            <td class="small">{{ $item['question']->pertanyaan }}</td>
                            <td>{{ $item['jumlah_ya'] }}</td>
                            <td>{{ $item['jumlah_tidak'] }}</td>
                            <td>{{ $item['avg_frekuensi'] ?? '-' }}</td>
                            <td>{{ $item['avg_dampak'] ?? '-' }}</td>
                            <td class="fw-semibold">{{ $item['sr'] ?? '-' }}</td>
                            <td>
                                @if ($item['klasifikasi'])
                                    <span
                                        class="badge bg-{{ $item['klasifikasi']['warna'] }}-subtle text-{{ $item['klasifikasi']['warna'] }}">
                                        {{ $item['klasifikasi']['label'] }}
                                    </span>
                                @else
                                    <span class="badge bg-light text-muted">Belum ada data</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">Belum ada data responden.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection

@push('scripts')
    @php
        $pertanyaanChartData = $perPertanyaan
            ->map(fn($i) => [
                'kode' => $i['question']->kode,
                'sr' => $i['sr'],
                'warna' => $i['klasifikasi']['warna'] ?? 'secondary',
            ])
            ->values();
    @endphp
    <script>
        const pertanyaanData = @json($pertanyaanChartData);

        const warnaMap = {
            success: '#16a34a',
            info: '#0891b2',
            warning: '#f59e0b',
            danger: '#ef4444',
            secondary: '#9ca3af'
        };

        new Chart(document.getElementById('chartSrPertanyaan'), {
            type: 'bar',
            data: {
                labels: pertanyaanData.map(p => p.kode),
                datasets: [{
                    label: 'Severity Rating',
                    data: pertanyaanData.map(p => p.sr),
                    backgroundColor: pertanyaanData.map(p => warnaMap[p.warna] ?? '#9ca3af'),
                    borderRadius: 4,
                }],
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        max: 4
                    }
                },
            },
        });

        new Chart(document.getElementById('chartDistribusi'), {
            type: 'pie',
            data: {
                labels: ['Ya (Ada Kendala)', 'Tidak Ada Kendala'],
                datasets: [{
                    data: [{{ $totalJawabanYa }}, {{ $totalJawabanTidak }}],
                    backgroundColor: ['#ef4444', '#10b981'],
                    borderWidth: 0,
                }],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            },
        });
    </script>
@endpush
