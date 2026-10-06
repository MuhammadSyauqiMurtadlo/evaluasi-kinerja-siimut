@extends('layouts.app')

@section('title', 'Terima Kasih')

@section('content')
    <div class="text-center py-5">
        <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
        <h3 class="fw-bold mt-3">Terima Kasih!</h3>
        <p class="text-muted">Jawaban Anda telah berhasil disimpan dan akan membantu kami meningkatkan kualitas SI-IMUT.</p>
        <a href="{{ route('landing') }}" class="btn btn-outline-primary">Kembali ke Beranda</a>
    </div>
@endsection
