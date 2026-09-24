@extends('layouts.app')

@section('title', 'Tambah Evaluasi')
@section('page-title', 'Tambah Evaluasi')

@section('content')
    <form id="formEvaluasi" method="POST" action="{{ route('evaluasi.store') }}">
        @csrf

        <div class="card-panel p-3 mb-3">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Kode Evaluasi <span class="text-danger">*</span></label>
                    <input type="text" name="kode_evaluasi"
                        class="form-control @error('kode_evaluasi') is-invalid @enderror"
                        value="{{ old('kode_evaluasi', $kodeBerikutnya) }}">
                    @error('kode_evaluasi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="draft" selected>Draft</option>
                        <option value="selesai">Selesai</option>
                    </select>
                </div>
            </div>
        </div>

        @include('evaluasi._form')

        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('evaluasi.index') }}" class="btn btn-outline-secondary">Batal</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Evaluasi</button>
        </div>
    </form>
@endsection
