@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    <div class="row align-items-center g-4 py-5">
        <div class="col-lg-7">
            <span class="badge bg-primary-subtle text-primary mb-3">SURVEY USABILITY</span>
            <h1 class="fw-bold mb-3">Bantu Kami Meningkatkan <span class="text-primary">Kualitas SI-IMUT</span></h1>
            <p class="text-muted mb-4">
                Berikan penilaian Anda terhadap pengalaman menggunakan Sistem Informasi Mutu (SI-IMUT).
                Pendapat Anda sangat berarti untuk membantu kami meningkatkan kualitas sistem.
            </p>
            <a href="{{ route('survey.create') }}" class="btn btn-primary btn-lg">
                Mulai Survei <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="col-lg-5">
            <div class="card-panel p-4">
                <i class="bi bi-clipboard2-check fs-1 text-primary"></i>
                <h5 class="fw-semibold mt-2">Survey Evaluasi Usability</h5>
                <p class="text-muted small">
                    Survei ini menilai pengalaman Anda pada 5 aspek utama: Navigation, Interaction, Content, Visual, dan
                    Functionality.
                </p>
                <ul class="list-unstyled small mb-0">
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-1"></i> 15 pertanyaan singkat</li>
                    <li class="mb-2"><i class="bi bi-clock text-primary me-1"></i> Hanya membutuhkan beberapa menit</li>
                    <li class="mb-0"><i class="bi bi-shield-lock text-warning me-1"></i> Data digunakan untuk evaluasi
                        sistem</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="text-center text-muted small mt-2">
        Total responden saat ini: <strong>{{ $totalResponden }}</strong>
    </div>
@endsection
