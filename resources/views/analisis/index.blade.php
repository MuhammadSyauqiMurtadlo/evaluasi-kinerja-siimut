@extends('layouts.app')

@section('title', 'Analisis')
@section('page-title', 'Analisis Hasil Evaluasi')

@section('content')

    {{-- ================= SCORING UX (dengan disclaimer) ================= --}}
    <div class="card-panel p-4 mb-4">
        <div class="row align-items-center g-4">
            <div class="col-md-3 text-center border-end">
                <div class="text-muted small mb-1">Skor UX Internal</div>
                <div class="display-4 fw-bold text-{{ $skorLabel['warna'] }}">{{ $skorUx }}</div>
                <span
                    class="badge bg-{{ $skorLabel['warna'] }}-subtle text-{{ $skorLabel['warna'] }}">{{ $skorLabel['label'] }}</span>
            </div>
            <div class="col-md-9">
                <div class="alert alert-warning d-flex gap-2 mb-2 py-2">
                    <i class="bi bi-info-circle-fill mt-1"></i>
                    <div class="small">
                        <strong>Catatan penting:</strong> Skor ini adalah <strong>indikator internal aplikasi</strong> hasil
                        kombinasi sederhana antara tingkat keberhasilan task dan tingkat keparahan (severity) finding.
                        Skor ini <strong>bukan hasil pengukuran metodologis baku</strong> dari metode Contextual Inquiry,
                        dan tidak menggantikan interpretasi kualitatif peneliti terhadap data observasi.
                    </div>
                </div>
                <div class="row small">
                    <div class="col-md-6">
                        <strong>Task Success Rate:</strong> {{ $successRate ?? '-' }}% ({{ $taskBerhasil }} berhasil /
                        {{ $totalTask }} total)
                    </div>
                    <div class="col-md-6">
                        <strong>Indeks Severity rata-rata:</strong> {{ $indeksSeverity }} / 100 (makin tinggi = makin berat)
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= GRAFIK ================= --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-6">
            <div class="card-panel p-3 h-100">
                <h6 class="fw-semibold mb-3">Finding per Kategori</h6>
                <canvas id="chartKategori" height="220"></canvas>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card-panel p-3 h-100">
                <h6 class="fw-semibold mb-3">Finding per Severity</h6>
                <canvas id="chartSeverity" height="220"></canvas>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card-panel p-3 h-100">
                <h6 class="fw-semibold mb-3">Task: Berhasil vs Gagal</h6>
                <canvas id="chartTask" height="220"></canvas>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card-panel p-3 h-100">
                <h6 class="fw-semibold mb-3">Finding per Informan</h6>
                <canvas id="chartInforman" height="220"></canvas>
            </div>
        </div>
    </div>

    {{-- ================= ISU BERULANG LINTAS INFORMAN ================= --}}
    <div class="card-panel p-3 mb-4">
        <h6 class="fw-semibold mb-3"><i class="bi bi-people-fill text-primary"></i> Masalah yang Muncul pada Beberapa
            Informan</h6>
        @forelse ($isuBerulang as $isu)
            <div class="d-flex justify-content-between align-items-center border rounded p-2 mb-2">
                <div>
                    <span class="fw-semibold">{{ ucfirst(str_replace('_', '/', $isu->kategori)) }}</span>
                    <span class="text-muted small ms-2">{{ $isu->total_finding }} finding tercatat</span>
                </div>
                <span class="badge bg-primary-subtle text-primary">Dialami {{ $isu->jumlah_informan }} informan
                    berbeda</span>
            </div>
        @empty
            <p class="text-muted small mb-0">Belum ada kategori masalah yang muncul pada lebih dari satu informan.</p>
        @endforelse
    </div>

    {{-- ================= HIGH PRIORITY FINDINGS ================= --}}
    <div class="card-panel p-3 mb-4">
        <h6 class="fw-semibold mb-3"><i class="bi bi-exclamation-octagon-fill text-danger"></i> High-Priority Findings (High
            & Critical)</h6>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="text-muted small text-uppercase">
                    <tr>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Severity</th>
                        <th>Informan</th>
                        <th>Task Terkait</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($highPriorityFindings as $f)
                        <tr>
                            <td>{{ $f->judul }}</td>
                            <td>{{ ucfirst(str_replace('_', '/', $f->kategori)) }}</td>
                            <td><span class="badge badge-severity-{{ $f->severity }}">{{ ucfirst($f->severity) }}</span>
                            </td>
                            <td>{{ $f->evaluation->informant->nama ?? '-' }}</td>
                            <td class="small text-muted">
                                {{ $f->task ? \Illuminate\Support\Str::limit($f->task->tujuan, 30) : '-' }}</td>
                            <td class="text-end">
                                <a href="{{ route('evaluasi.show', $f->evaluation_id) }}"
                                    class="btn btn-sm btn-outline-primary">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">Tidak ada finding high/critical.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ================= FREQUENCY (data mentah, dicatat manual oleh peneliti) ================= --}}
    <div class="card-panel p-3">
        <h6 class="fw-semibold mb-3"><i class="bi bi-graph-up text-secondary"></i> Frequency Finding (catatan peneliti)</h6>
        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
                <thead class="text-muted small text-uppercase">
                    <tr>
                        <th>Judul Finding</th>
                        <th>Severity</th>
                        <th>Frequency</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($frequencyList as $f)
                        <tr>
                            <td class="small">{{ $f->judul }}</td>
                            <td><span class="badge badge-severity-{{ $f->severity }}">{{ ucfirst($f->severity) }}</span>
                            </td>
                            <td class="small">{{ $f->frequency }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-3">Belum ada data frequency.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        const kategoriLabelMap = {
            navigation: 'Navigation',
            interaction: 'Interaction',
            information: 'Information',
            content: 'Content',
            visual_ui: 'Visual/UI',
            functionality: 'Functionality',
        };
        const severityColor = {
            low: '#10b981',
            medium: '#f59e0b',
            high: '#f97316',
            critical: '#ef4444'
        };
        const severityLabelMap = {
            low: 'Low',
            medium: 'Medium',
            high: 'High',
            critical: 'Critical'
        };

        // --- Kategori ---
        const kategoriRaw = @json($perKategori);
        new Chart(document.getElementById('chartKategori'), {
            type: 'bar',
            data: {
                labels: kategoriRaw.map(k => kategoriLabelMap[k.kategori] ?? k.kategori),
                datasets: [{
                    label: 'Jumlah Finding',
                    data: kategoriRaw.map(k => k.total_finding),
                    backgroundColor: '#4f46e5',
                    borderRadius: 6,
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
                }
            },
        });

        // --- Severity ---
        const severityRaw = @json($perSeverity);
        new Chart(document.getElementById('chartSeverity'), {
            type: 'doughnut',
            data: {
                labels: Object.keys(severityRaw).map(k => severityLabelMap[k] ?? k),
                datasets: [{
                    data: Object.values(severityRaw),
                    backgroundColor: Object.keys(severityRaw).map(k => severityColor[k] ?? '#9ca3af'),
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

        // --- Task ---
        new Chart(document.getElementById('chartTask'), {
            type: 'bar',
            data: {
                labels: ['Berhasil', 'Gagal'],
                datasets: [{
                    data: [{{ $taskBerhasil }}, {{ $taskGagal }}],
                    backgroundColor: ['#16a34a', '#dc2626'],
                    borderRadius: 6,
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
                }
            },
        });

        // --- Per Informan ---
        const informanRaw = @json($perInforman);
        new Chart(document.getElementById('chartInforman'), {
            type: 'bar',
            data: {
                labels: informanRaw.map(i => i.informan),
                datasets: [{
                    label: 'Jumlah Finding',
                    data: informanRaw.map(i => i.total_finding),
                    backgroundColor: '#0891b2',
                    borderRadius: 6,
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
                        ticks: {
                            precision: 0
                        }
                    }
                },
            },
        });
    </script>
@endpush
