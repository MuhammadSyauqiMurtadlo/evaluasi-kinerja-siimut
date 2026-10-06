@extends('layouts.app')

@section('title', 'Survei Evaluasi SI-IMUT')

@section('content')

    <form method="POST" action="{{ route('survey.store') }}" id="formSurvey">
        @csrf

        <div class="card-panel p-4 mb-3">
            <h5 class="fw-semibold mb-3"><i class="bi bi-person-badge"></i> Data Responden</h5>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Nama Pengguna <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                        value="{{ old('nama') }}">
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Ruangan <span class="text-danger">*</span></label>
                    <select name="ruangan" class="form-select @error('ruangan') is-invalid @enderror">
                        <option value="">- Pilih Ruangan -</option>
                        @foreach (['Ruang Rawat Inap', 'Ruang Rawat Jalan / Poli', 'IGD (Instalasi Gawat Darurat)', 'Laboratorium', 'Farmasi', 'Rekam Medis', 'Administrasi / Tata Usaha', 'Keuangan', 'Bagian Mutu / PMKP', 'Lainnya'] as $opt)
                            <option value="{{ $opt }}" @selected(old('ruangan') === $opt)>{{ $opt }}</option>
                        @endforeach
                    </select>
                    @error('ruangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Lama Menggunakan SI-IMUT <span class="text-danger">*</span></label>
                    <select name="lama_penggunaan" class="form-select @error('lama_penggunaan') is-invalid @enderror">
                        <option value="">- Pilih -</option>
                        @foreach (['< 6 bulan', '6 bulan - 1 tahun', '1 - 2 tahun', '> 2 tahun'] as $opt)
                            <option value="{{ $opt }}" @selected(old('lama_penggunaan') === $opt)>{{ $opt }}</option>
                        @endforeach
                    </select>
                    @error('lama_penggunaan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        @php
            $labelKategori = [
                'navigation' => ['Navigation', 'bi-signpost-2'],
                'interaction' => ['Interaction', 'bi-cursor'],
                'content' => ['Content', 'bi-file-text'],
                'visual' => ['Visual', 'bi-palette'],
                'functionality' => ['Functionality', 'bi-gear'],
            ];
            $frekuensiOpsi = [1 => 'Sangat Jarang', 2 => 'Jarang', 3 => 'Sering', 4 => 'Sangat Sering'];
            $dampakOpsi = [
                1 => 'Rendah / Tidak Mengganggu',
                2 => 'Sedang',
                3 => 'Tinggi / Berat',
                4 => 'Sangat Tinggi / Kritis',
            ];
        @endphp

        @error('answers')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <div class="accordion mb-3" id="questionAccordion">
            @foreach ($questions as $kategori => $items)
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button"
                            data-bs-toggle="collapse" data-bs-target="#cat-{{ $kategori }}">
                            <i class="bi {{ $labelKategori[$kategori][1] }} me-2"></i> {{ $labelKategori[$kategori][0] }}
                        </button>
                    </h2>
                    <div id="cat-{{ $kategori }}"
                        class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}">
                        <div class="accordion-body">
                            @foreach ($items as $q)
                                <div class="border rounded p-3 mb-3 question-block">
                                    <p class="fw-semibold mb-2">{{ $loop->parent->iteration }}.{{ $loop->iteration }}.
                                        {{ $q->pertanyaan }}</p>

                                    <div class="btn-group mb-3" role="group">
                                        <input type="radio" class="btn-check jawaban-radio"
                                            name="answers[{{ $q->id }}][jawaban]" id="ya-{{ $q->id }}"
                                            value="ya" autocomplete="off" @checked(old("answers.$q->id.jawaban") === 'ya')>
                                        <label class="btn btn-outline-success btn-sm"
                                            for="ya-{{ $q->id }}">Ya</label>

                                        <input type="radio" class="btn-check jawaban-radio"
                                            name="answers[{{ $q->id }}][jawaban]" id="tidak-{{ $q->id }}"
                                            value="tidak" autocomplete="off" @checked(old("answers.$q->id.jawaban") !== 'ya')>
                                        <label class="btn btn-outline-secondary btn-sm"
                                            for="tidak-{{ $q->id }}">Tidak</label>
                                    </div>
                                    @error("answers.$q->id.jawaban")
                                        <div class="text-danger small mb-2">{{ $message }}</div>
                                    @enderror

                                    <div
                                        class="followup-block row g-3 {{ old("answers.$q->id.jawaban") === 'ya' ? '' : 'd-none' }}">
                                        <div class="col-md-6">
                                            <label class="form-label small">Frekuensi Kendala</label>
                                            <select name="answers[{{ $q->id }}][frekuensi]"
                                                class="form-select form-select-sm">
                                                <option value="">- Pilih -</option>
                                                @foreach ($frekuensiOpsi as $val => $label)
                                                    <option value="{{ $val }}" @selected((string) old("answers.$q->id.frekuensi") === (string) $val)>
                                                        {{ $val }} - {{ $label }}</option>
                                                @endforeach
                                            </select>
                                            @error("answers.$q->id.frekuensi")
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small">Dampak Kendala</label>
                                            <select name="answers[{{ $q->id }}][dampak]"
                                                class="form-select form-select-sm">
                                                <option value="">- Pilih -</option>
                                                @foreach ($dampakOpsi as $val => $label)
                                                    <option value="{{ $val }}" @selected((string) old("answers.$q->id.dampak") === (string) $val)>
                                                        {{ $val }} - {{ $label }}</option>
                                                @endforeach
                                            </select>
                                            @error("answers.$q->id.dampak")
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-send"></i> Kirim Jawaban</button>
        </div>
    </form>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.jawaban-radio').forEach(function(radio) {
                radio.addEventListener('change', function() {
                    const block = this.closest('.question-block').querySelector('.followup-block');
                    if (this.value === 'ya' && this.checked) {
                        block.classList.remove('d-none');
                    } else if (this.value === 'tidak' && this.checked) {
                        block.classList.add('d-none');
                        block.querySelectorAll('select').forEach(s => s.value = '');
                    }
                });
            });
        });
    </script>
@endpush
