@php
    // $evaluasi ada saat mode edit (null saat create)
    $isEdit = isset($evaluasi);

    $initialData = $isEdit
        ? [
            'tasks' => $evaluasi->tasks
                ->map(
                    fn($t) => [
                        'uid_seed' => $t->id,
                        'tujuan' => $t->tujuan,
                        'instruksi' => $t->instruksi,
                        'status' => $t->status,
                        'waktu_penyelesaian' => $t->waktu_penyelesaian,
                        'catatan' => $t->catatan,
                        'observation' => $t->observations->first(),
                    ],
                )
                ->values(),
            'pain_points' => $evaluasi->painPoints
                ->map(
                    fn($p) => [
                        'uid_seed' => $p->id,
                        'deskripsi' => $p->deskripsi,
                        'catatan' => $p->catatan,
                    ],
                )
                ->values(),
            'findings' => $evaluasi->findings
                ->map(
                    fn($f) => [
                        'judul' => $f->judul,
                        'deskripsi' => $f->deskripsi,
                        'kategori' => $f->kategori,
                        'severity' => $f->severity,
                        'frequency' => $f->frequency,
                        'impact' => $f->impact,
                        'root_cause' => $f->root_cause,
                        'catatan' => $f->catatan,
                        'task_db_id' => $f->task_id,
                        'pain_point_db_id' => $f->pain_point_id,
                    ],
                )
                ->values(),
        ]
        : ['tasks' => [], 'pain_points' => [], 'findings' => []];
@endphp

<div class="accordion mb-3" id="mainAccordion">

    {{-- ============ INFORMAN ============ --}}
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseInforman">
                <i class="bi bi-person-badge me-2"></i> Data Informan
            </button>
        </h2>
        <div id="collapseInforman" class="accordion-collapse collapse show">
            <div class="accordion-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Kode/Inisial <span class="text-danger">*</span></label>
                        <input type="text" name="informant[kode]"
                            class="form-control @error('informant.kode') is-invalid @enderror"
                            value="{{ old('informant.kode', $evaluasi->informant->kode ?? '') }}">
                        @error('informant.kode')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Nama <span class="text-danger">*</span></label>
                        <input type="text" name="informant[nama]"
                            class="form-control @error('informant.nama') is-invalid @enderror"
                            value="{{ old('informant.nama', $evaluasi->informant->nama ?? '') }}">
                        @error('informant.nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Jabatan</label>
                        <input type="text" name="informant[jabatan]" class="form-control"
                            value="{{ old('informant.jabatan', $evaluasi->informant->jabatan ?? '') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Unit</label>
                        <input type="text" name="informant[unit]" class="form-control"
                            value="{{ old('informant.unit', $evaluasi->informant->unit ?? '') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Pengalaman Menggunakan SI-IMUT</label>
                        <input type="text" name="informant[pengalaman_penggunaan]" class="form-control"
                            placeholder="ex: 1 tahun"
                            value="{{ old('informant.pengalaman_penggunaan', $evaluasi->informant->pengalaman_penggunaan ?? '') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Frekuensi Penggunaan</label>
                        <input type="text" name="informant[frekuensi_penggunaan]" class="form-control"
                            placeholder="ex: Setiap hari"
                            value="{{ old('informant.frekuensi_penggunaan', $evaluasi->informant->frekuensi_penggunaan ?? '') }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Catatan</label>
                        <textarea name="informant[catatan]" class="form-control" rows="2">{{ old('informant.catatan', $evaluasi->informant->catatan ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============ SESI ============ --}}
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapseSesi">
                <i class="bi bi-calendar-event me-2"></i> Sesi Contextual Inquiry
            </button>
        </h2>
        <div id="collapseSesi" class="accordion-collapse collapse">
            <div class="accordion-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="session[tanggal]"
                            class="form-control @error('session.tanggal') is-invalid @enderror"
                            value="{{ old('session.tanggal', optional($evaluasi->session ?? null)->tanggal?->format('Y-m-d')) }}">
                        @error('session.tanggal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Waktu</label>
                        <input type="time" name="session[waktu]" class="form-control"
                            value="{{ old('session.waktu', $evaluasi->session->waktu ?? '') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Durasi</label>
                        <input type="text" name="session[durasi]" class="form-control" placeholder="ex: 45 menit"
                            value="{{ old('session.durasi', $evaluasi->session->durasi ?? '') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Perangkat</label>
                        <input type="text" name="session[perangkat]" class="form-control"
                            placeholder="ex: Laptop, Chrome"
                            value="{{ old('session.perangkat', $evaluasi->session->perangkat ?? '') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tujuan Sesi</label>
                        <textarea name="session[tujuan]" class="form-control" rows="2">{{ old('session.tujuan', $evaluasi->session->tujuan ?? '') }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Konteks</label>
                        <textarea name="session[konteks]" class="form-control" rows="2">{{ old('session.konteks', $evaluasi->session->konteks ?? '') }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Lingkungan</label>
                        <input type="text" name="session[lingkungan]" class="form-control"
                            value="{{ old('session.lingkungan', $evaluasi->session->lingkungan ?? '') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Kondisi Penggunaan</label>
                        <input type="text" name="session[kondisi_penggunaan]" class="form-control"
                            value="{{ old('session.kondisi_penggunaan', $evaluasi->session->kondisi_penggunaan ?? '') }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Catatan</label>
                        <textarea name="session[catatan]" class="form-control" rows="2">{{ old('session.catatan', $evaluasi->session->catatan ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============ TASK & OBSERVASI ============ --}}
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapseTask">
                <i class="bi bi-list-check me-2"></i> Task & Observasi
                <span class="badge bg-primary-subtle text-primary ms-2" id="taskCountBadge">0</span>
            </button>
        </h2>
        <div id="collapseTask" class="accordion-collapse collapse">
            <div class="accordion-body">
                @error('tasks')
                    <div class="alert alert-danger py-2">{{ $message }}</div>
                @enderror

                <div class="accordion" id="taskAccordion"></div>

                <button type="button" id="btnAddTask" class="btn btn-outline-primary btn-sm mt-3">
                    <i class="bi bi-plus-lg"></i> Tambah Task
                </button>
            </div>
        </div>
    </div>

    {{-- ============ INTERVIEW ============ --}}
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapseInterview">
                <i class="bi bi-chat-dots me-2"></i> Interview
            </button>
        </h2>
        <div id="collapseInterview" class="accordion-collapse collapse">
            <div class="accordion-body">
                <label class="form-label">Catatan Hasil Interview</label>
                <textarea name="interview[catatan]" class="form-control" rows="4">{{ old('interview.catatan', $evaluasi->interview->catatan ?? '') }}</textarea>
            </div>
        </div>
    </div>

    {{-- ============ INSIGHT ============ --}}
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapseInsight">
                <i class="bi bi-lightbulb me-2"></i> Insight Peneliti
            </button>
        </h2>
        <div id="collapseInsight" class="accordion-collapse collapse">
            <div class="accordion-body">
                <label class="form-label">Insight / Interpretasi</label>
                <textarea name="insight[catatan]" class="form-control" rows="4">{{ old('insight.catatan', $evaluasi->insight->catatan ?? '') }}</textarea>
            </div>
        </div>
    </div>

    {{-- ============ PAIN POINT ============ --}}
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapsePainPoint">
                <i class="bi bi-exclamation-triangle me-2"></i> Pain Point
                <span class="badge bg-warning-subtle text-warning ms-2" id="painPointCountBadge">0</span>
            </button>
        </h2>
        <div id="collapsePainPoint" class="accordion-collapse collapse">
            <div class="accordion-body">
                <div id="painPointList" class="d-flex flex-column gap-2"></div>
                <button type="button" id="btnAddPainPoint" class="btn btn-outline-warning btn-sm mt-3">
                    <i class="bi bi-plus-lg"></i> Tambah Pain Point
                </button>
            </div>
        </div>
    </div>

    {{-- ============ USABILITY FINDING ============ --}}
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapseFinding">
                <i class="bi bi-search me-2"></i> Usability Finding
                <span class="badge bg-danger-subtle text-danger ms-2" id="findingCountBadge">0</span>
            </button>
        </h2>
        <div id="collapseFinding" class="accordion-collapse collapse">
            <div class="accordion-body">
                <div id="findingList" class="d-flex flex-column gap-2"></div>
                <button type="button" id="btnAddFinding" class="btn btn-outline-danger btn-sm mt-3">
                    <i class="bi bi-plus-lg"></i> Tambah Finding
                </button>
            </div>
        </div>
    </div>

</div>

{{-- ================= TEMPLATES (tidak dirender, cuma sumber clone JS) ================= --}}

<template id="tpl-task">
    <div class="accordion-item task-item" data-uid="">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="">
                Task #<span class="pos-label">?</span>: <span class="task-preview text-muted ms-1 small"></span>
            </button>
        </h2>
        <div class="accordion-collapse collapse">
            <div class="accordion-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Tujuan Task <span class="text-danger">*</span></label>
                        <textarea class="form-control" rows="2" data-field="tujuan" data-name="tasks[__IDX__][tujuan]"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Instruksi ke Informan</label>
                        <textarea class="form-control" rows="2" data-field="instruksi" data-name="tasks[__IDX__][instruksi]"></textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select class="form-select" data-field="status" data-name="tasks[__IDX__][status]">
                            <option value="">- Pilih -</option>
                            <option value="berhasil">Berhasil</option>
                            <option value="gagal">Gagal</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Waktu Penyelesaian</label>
                        <input type="text" class="form-control" placeholder="ex: 2 menit 30 detik"
                            data-field="waktu_penyelesaian" data-name="tasks[__IDX__][waktu_penyelesaian]">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Catatan Task</label>
                        <input type="text" class="form-control" data-field="catatan"
                            data-name="tasks[__IDX__][catatan]">
                    </div>
                </div>

                <hr>
                <h6 class="fw-semibold"><i class="bi bi-eye me-1"></i> Observasi</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Tindakan</label>
                        <textarea class="form-control" rows="2" data-field="obs_tindakan"
                            data-name="tasks[__IDX__][observation][tindakan]"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Perilaku</label>
                        <textarea class="form-control" rows="2" data-field="obs_perilaku"
                            data-name="tasks[__IDX__][observation][perilaku]"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Reaksi</label>
                        <textarea class="form-control" rows="2" data-field="obs_reaksi"
                            data-name="tasks[__IDX__][observation][reaksi]"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Kesulitan</label>
                        <textarea class="form-control" rows="2" data-field="obs_kesulitan"
                            data-name="tasks[__IDX__][observation][kesulitan]"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Kebingungan</label>
                        <textarea class="form-control" rows="2" data-field="obs_kebingungan"
                            data-name="tasks[__IDX__][observation][kebingungan]"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Strategi Pengguna</label>
                        <textarea class="form-control" rows="2" data-field="obs_strategi"
                            data-name="tasks[__IDX__][observation][strategi_pengguna]"></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Catatan Observasi</label>
                        <textarea class="form-control" rows="2" data-field="obs_catatan"
                            data-name="tasks[__IDX__][observation][catatan]"></textarea>
                    </div>
                </div>

                <button type="button" class="btn btn-sm btn-outline-danger mt-3 btn-remove-task">
                    <i class="bi bi-trash"></i> Hapus Task Ini
                </button>
            </div>
        </div>
    </div>
</template>

<template id="tpl-painpoint">
    <div class="card painpoint-item" data-uid="">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="fw-semibold small text-muted">Pain Point #<span class="pos-label">?</span></span>
                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-painpoint">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
            <div class="mb-2">
                <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                <textarea class="form-control" rows="2" data-field="deskripsi" data-name="pain_points[__IDX__][deskripsi]"></textarea>
            </div>
            <div>
                <label class="form-label">Catatan</label>
                <input type="text" class="form-control" data-field="catatan"
                    data-name="pain_points[__IDX__][catatan]">
            </div>
        </div>
    </div>
</template>

<template id="tpl-finding">
    <div class="card finding-item" data-uid="">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="fw-semibold small text-muted">Finding #<span class="pos-label">?</span></span>
                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-finding">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Judul <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" data-field="judul"
                        data-name="findings[__IDX__][judul]">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Severity <span class="text-danger">*</span></label>
                    <select class="form-select" data-field="severity" data-name="findings[__IDX__][severity]">
                        <option value="">- Pilih -</option>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                        <option value="critical">Critical</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                    <textarea class="form-control" rows="2" data-field="deskripsi" data-name="findings[__IDX__][deskripsi]"></textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Kategori <span class="text-danger">*</span></label>
                    <select class="form-select" data-field="kategori" data-name="findings[__IDX__][kategori]">
                        <option value="">- Pilih -</option>
                        <option value="navigation">Navigation</option>
                        <option value="interaction">Interaction</option>
                        <option value="information">Information</option>
                        <option value="content">Content</option>
                        <option value="visual_ui">Visual/UI</option>
                        <option value="functionality">Functionality</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Task Terkait</label>
                    <select class="form-select task-ref-select"></select>
                    <input type="hidden" data-field="task_index" data-name="findings[__IDX__][task_index]">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Pain Point Terkait</label>
                    <select class="form-select painpoint-ref-select"></select>
                    <input type="hidden" data-field="pain_point_index"
                        data-name="findings[__IDX__][pain_point_index]">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Frequency</label>
                    <input type="text" class="form-control" placeholder="ex: 2 dari 3 informan"
                        data-field="frequency" data-name="findings[__IDX__][frequency]">
                </div>
                <div class="col-md-8">
                    <label class="form-label">Impact</label>
                    <input type="text" class="form-control" data-field="impact"
                        data-name="findings[__IDX__][impact]">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Root Cause</label>
                    <textarea class="form-control" rows="2" data-field="root_cause" data-name="findings[__IDX__][root_cause]"></textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Catatan</label>
                    <textarea class="form-control" rows="2" data-field="catatan" data-name="findings[__IDX__][catatan]"></textarea>
                </div>
            </div>
        </div>
    </div>
</template>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let uidCounter = 0;
            const nextUid = () => 'u' + (++uidCounter);

            const taskContainer = document.getElementById('taskAccordion');
            const ppContainer = document.getElementById('painPointList');
            const findingContainer = document.getElementById('findingList');

            const tplTask = document.getElementById('tpl-task');
            const tplPP = document.getElementById('tpl-painpoint');
            const tplFinding = document.getElementById('tpl-finding');

            // ---------- Helper: clone template ----------
            function cloneTemplate(tpl) {
                return tpl.content.firstElementChild.cloneNode(true);
            }

            // ---------- TASK ----------
            function addTask(prefill = null) {
                const el = cloneTemplate(tplTask);
                const uid = nextUid();
                el.dataset.uid = uid;

                const collapseId = 'task-collapse-' + uid;
                el.querySelector('.accordion-collapse').id = collapseId;
                el.querySelector('.accordion-button').setAttribute('data-bs-target', '#' + collapseId);

                if (prefill) {
                    setFieldValue(el, 'tujuan', prefill.tujuan);
                    setFieldValue(el, 'instruksi', prefill.instruksi);
                    setFieldValue(el, 'status', prefill.status);
                    setFieldValue(el, 'waktu_penyelesaian', prefill.waktu_penyelesaian);
                    setFieldValue(el, 'catatan', prefill.catatan);
                    const obs = prefill.observation || {};
                    setFieldValue(el, 'obs_tindakan', obs.tindakan);
                    setFieldValue(el, 'obs_perilaku', obs.perilaku);
                    setFieldValue(el, 'obs_reaksi', obs.reaksi);
                    setFieldValue(el, 'obs_kesulitan', obs.kesulitan);
                    setFieldValue(el, 'obs_kebingungan', obs.kebingungan);
                    setFieldValue(el, 'obs_strategi', obs.strategi_pengguna);
                    setFieldValue(el, 'obs_catatan', obs.catatan);
                }

                el.querySelector('.btn-remove-task').addEventListener('click', function() {
                    if (findingContainer.querySelector(`[data-ref-task-uid="${uid}"]`)) {
                        if (!confirm(
                                'Task ini dirujuk oleh salah satu Finding. Tetap hapus? Referensi finding akan dikosongkan.'
                            )) return;
                    }
                    el.remove();
                    reindexAll();
                });

                taskContainer.appendChild(el);
                return uid;
            }

            // ---------- PAIN POINT ----------
            function addPainPoint(prefill = null) {
                const el = cloneTemplate(tplPP);
                const uid = nextUid();
                el.dataset.uid = uid;

                if (prefill) {
                    setFieldValue(el, 'deskripsi', prefill.deskripsi);
                    setFieldValue(el, 'catatan', prefill.catatan);
                }

                el.querySelector('.btn-remove-painpoint').addEventListener('click', function() {
                    el.remove();
                    reindexAll();
                });

                ppContainer.appendChild(el);
                return uid;
            }

            // ---------- FINDING ----------
            function addFinding(prefill = null, taskUidRef = null, ppUidRef = null) {
                const el = cloneTemplate(tplFinding);
                const uid = nextUid();
                el.dataset.uid = uid;

                if (prefill) {
                    setFieldValue(el, 'judul', prefill.judul);
                    setFieldValue(el, 'deskripsi', prefill.deskripsi);
                    setFieldValue(el, 'kategori', prefill.kategori);
                    setFieldValue(el, 'severity', prefill.severity);
                    setFieldValue(el, 'frequency', prefill.frequency);
                    setFieldValue(el, 'impact', prefill.impact);
                    setFieldValue(el, 'root_cause', prefill.root_cause);
                    setFieldValue(el, 'catatan', prefill.catatan);
                }

                el.querySelector('.btn-remove-finding').addEventListener('click', function() {
                    el.remove();
                    reindexAll();
                });

                findingContainer.appendChild(el);
                refreshRefSelects();

                // set pilihan dropdown referensi (dilakukan setelah select terisi opsi)
                if (taskUidRef) el.querySelector('.task-ref-select').value = taskUidRef;
                if (ppUidRef) el.querySelector('.painpoint-ref-select').value = ppUidRef;

                return uid;
            }

            function setFieldValue(scope, fieldName, value) {
                const field = scope.querySelector(`[data-field="${fieldName}"]`);
                if (field && value !== undefined && value !== null) field.value = value;
            }

            // ---------- Rebuild dropdown referensi Task/Pain Point tiap finding ----------
            function refreshRefSelects() {
                const taskItems = [...taskContainer.querySelectorAll('.task-item')];
                const ppItems = [...ppContainer.querySelectorAll('.painpoint-item')];

                findingContainer.querySelectorAll('.finding-item').forEach(function(findingEl) {
                    const taskSelect = findingEl.querySelector('.task-ref-select');
                    const ppSelect = findingEl.querySelector('.painpoint-ref-select');
                    const prevTaskUid = taskSelect.value;
                    const prevPpUid = ppSelect.value;

                    taskSelect.innerHTML = '<option value="">- Tidak ada -</option>';
                    taskItems.forEach(function(t, idx) {
                        const preview = (t.querySelector('[data-field="tujuan"]').value ||
                            '(kosong)').substring(0, 40);
                        const opt = document.createElement('option');
                        opt.value = t.dataset.uid;
                        opt.textContent = `Task #${idx + 1}: ${preview}`;
                        taskSelect.appendChild(opt);
                    });
                    if ([...taskSelect.options].some(o => o.value === prevTaskUid)) taskSelect.value =
                        prevTaskUid;

                    ppSelect.innerHTML = '<option value="">- Tidak ada -</option>';
                    ppItems.forEach(function(p, idx) {
                        const preview = (p.querySelector('[data-field="deskripsi"]').value ||
                            '(kosong)').substring(0, 40);
                        const opt = document.createElement('option');
                        opt.value = p.dataset.uid;
                        opt.textContent = `Pain Point #${idx + 1}: ${preview}`;
                        ppSelect.appendChild(opt);
                    });
                    if ([...ppSelect.options].some(o => o.value === prevPpUid)) ppSelect.value = prevPpUid;
                });
            }

            // ---------- Reindex semua data-name sesuai posisi terkini ----------
            function reindexAll() {
                const taskItems = [...taskContainer.querySelectorAll('.task-item')];
                taskItems.forEach(function(item, idx) {
                    item.querySelector('.pos-label').textContent = idx + 1;
                    const preview = item.querySelector('[data-field="tujuan"]').value || '';
                    item.querySelector('.task-preview').textContent = preview.substring(0, 50);
                    item.querySelectorAll('[data-name]').forEach(function(field) {
                        field.name = field.dataset.name.replace('__IDX__', idx);
                    });
                });
                document.getElementById('taskCountBadge').textContent = taskItems.length;

                const ppItems = [...ppContainer.querySelectorAll('.painpoint-item')];
                ppItems.forEach(function(item, idx) {
                    item.querySelector('.pos-label').textContent = idx + 1;
                    item.querySelectorAll('[data-name]').forEach(function(field) {
                        field.name = field.dataset.name.replace('__IDX__', idx);
                    });
                });
                document.getElementById('painPointCountBadge').textContent = ppItems.length;

                const findingItems = [...findingContainer.querySelectorAll('.finding-item')];
                findingItems.forEach(function(item, idx) {
                    item.querySelector('.pos-label').textContent = idx + 1;
                    item.querySelectorAll('[data-name]').forEach(function(field) {
                        field.name = field.dataset.name.replace('__IDX__', idx);
                    });
                });
                document.getElementById('findingCountBadge').textContent = findingItems.length;

                refreshRefSelects();
            }

            // ---------- Sebelum submit: konversi uid referensi -> index posisi ----------
            document.getElementById('formEvaluasi').addEventListener('submit', function() {
                const taskItems = [...taskContainer.querySelectorAll('.task-item')];
                const ppItems = [...ppContainer.querySelectorAll('.painpoint-item')];

                findingContainer.querySelectorAll('.finding-item').forEach(function(findingEl) {
                    const taskUid = findingEl.querySelector('.task-ref-select').value;
                    const ppUid = findingEl.querySelector('.painpoint-ref-select').value;

                    const taskIdx = taskItems.findIndex(t => t.dataset.uid === taskUid);
                    const ppIdx = ppItems.findIndex(p => p.dataset.uid === ppUid);

                    findingEl.querySelector('[data-field="task_index"]').value = taskIdx >= 0 ?
                        taskIdx : '';
                    findingEl.querySelector('[data-field="pain_point_index"]').value = ppIdx >= 0 ?
                        ppIdx : '';
                });
            });

            // ---------- Tombol tambah ----------
            document.getElementById('btnAddTask').addEventListener('click', () => {
                addTask();
                reindexAll();
            });
            document.getElementById('btnAddPainPoint').addEventListener('click', () => {
                addPainPoint();
                reindexAll();
            });
            document.getElementById('btnAddFinding').addEventListener('click', () => {
                addFinding();
                reindexAll();
            });

            // ---------- Live update preview & dropdown saat user mengetik ----------
            document.addEventListener('input', function(e) {
                if (e.target.closest('.task-item') || e.target.closest('.painpoint-item')) {
                    refreshRefSelects();
                    if (e.target.dataset.field === 'tujuan') {
                        const item = e.target.closest('.task-item');
                        item.querySelector('.task-preview').textContent = e.target.value.substring(0, 50);
                    }
                }
            });

            // ================= INISIALISASI DATA (mode EDIT) / DATA KOSONG (mode CREATE) =================
            const initialData = @json($initialData);

            const taskDbIdToUid = {};
            const ppDbIdToUid = {};

            if (initialData.tasks.length) {
                initialData.tasks.forEach(function(t) {
                    const uid = addTask(t);
                    taskDbIdToUid[t.uid_seed] = uid;
                });
            }
            if (initialData.pain_points.length) {
                initialData.pain_points.forEach(function(p) {
                    const uid = addPainPoint(p);
                    ppDbIdToUid[p.uid_seed] = uid;
                });
            }
            reindexAll(); // supaya refreshRefSelects sudah punya daftar task/pain point sebelum finding dibuat

            if (initialData.findings.length) {
                initialData.findings.forEach(function(f) {
                    const taskUid = f.task_db_id ? taskDbIdToUid[f.task_db_id] : null;
                    const ppUid = f.pain_point_db_id ? ppDbIdToUid[f.pain_point_db_id] : null;
                    addFinding(f, taskUid, ppUid);
                });
            }

            // Mode create: minimal 1 task supaya user tidak lupa
            if (!@json($isEdit) && initialData.tasks.length === 0) {
                addTask();
            }

            reindexAll();
        });
    </script>
@endpush
