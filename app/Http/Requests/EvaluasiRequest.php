<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EvaluasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Tidak ada auth/role — aplikasi internal untuk peneliti.
        return true;
    }

    public function rules(): array
    {
        // Pakai ID evaluasi saat ini (kalau mode edit) supaya unique check tidak bentrok dengan dirinya sendiri.
        $evaluationId = $this->route('evaluasi')?->id;

        return [
            // --- Evaluasi ---
            'kode_evaluasi' => [
                'required', 'string', 'max:50',
                Rule::unique('evaluations', 'kode_evaluasi')->ignore($evaluationId),
            ],
            'status' => ['nullable', Rule::in(['draft', 'selesai'])],

            // --- Informan ---
            'informant.kode' => ['required', 'string', 'max:50'],
            'informant.nama' => ['required', 'string', 'max:150'],
            'informant.jabatan' => ['nullable', 'string', 'max:150'],
            'informant.unit' => ['nullable', 'string', 'max:150'],
            'informant.pengalaman_penggunaan' => ['nullable', 'string', 'max:100'],
            'informant.frekuensi_penggunaan' => ['nullable', 'string', 'max:100'],
            'informant.catatan' => ['nullable', 'string'],

            // --- Sesi ---
            'session.tanggal' => ['required', 'date'],
            'session.waktu' => ['nullable', 'date_format:H:i'],
            'session.durasi' => ['nullable', 'string', 'max:50'],
            'session.tujuan' => ['nullable', 'string'],
            'session.konteks' => ['nullable', 'string'],
            'session.lingkungan' => ['nullable', 'string', 'max:150'],
            'session.perangkat' => ['nullable', 'string', 'max:150'],
            'session.kondisi_penggunaan' => ['nullable', 'string'],
            'session.catatan' => ['nullable', 'string'],

            // --- Task (min 1) + Observasi per task ---
            'tasks' => ['required', 'array', 'min:1'],
            'tasks.*.tujuan' => ['required', 'string'],
            'tasks.*.instruksi' => ['nullable', 'string'],
            'tasks.*.status' => ['nullable', Rule::in(['berhasil', 'gagal'])],
            'tasks.*.waktu_penyelesaian' => ['nullable', 'string', 'max:50'],
            'tasks.*.catatan' => ['nullable', 'string'],
            'tasks.*.observation.tindakan' => ['nullable', 'string'],
            'tasks.*.observation.perilaku' => ['nullable', 'string'],
            'tasks.*.observation.reaksi' => ['nullable', 'string'],
            'tasks.*.observation.kesulitan' => ['nullable', 'string'],
            'tasks.*.observation.kebingungan' => ['nullable', 'string'],
            'tasks.*.observation.strategi_pengguna' => ['nullable', 'string'],
            'tasks.*.observation.catatan' => ['nullable', 'string'],

            // --- Interview & Insight ---
            'interview.catatan' => ['nullable', 'string'],
            'insight.catatan' => ['nullable', 'string'],

            // --- Pain Point (opsional, banyak) ---
            'pain_points' => ['nullable', 'array'],
            'pain_points.*.deskripsi' => ['required_with:pain_points.*', 'string'],
            'pain_points.*.catatan' => ['nullable', 'string'],

            // --- Usability Finding (opsional, banyak) ---
            'findings' => ['nullable', 'array'],
            'findings.*.judul' => ['required_with:findings.*', 'string', 'max:200'],
            'findings.*.deskripsi' => ['required_with:findings.*', 'string'],
            'findings.*.kategori' => [
                'required_with:findings.*',
                Rule::in(['navigation', 'interaction', 'information', 'content', 'visual_ui', 'functionality']),
            ],
            'findings.*.severity' => [
                'required_with:findings.*',
                Rule::in(['low', 'medium', 'high', 'critical']),
            ],
            'findings.*.frequency' => ['nullable', 'string', 'max:100'],
            'findings.*.impact' => ['nullable', 'string'],
            'findings.*.root_cause' => ['nullable', 'string'],
            'findings.*.catatan' => ['nullable', 'string'],
            // Referensi ke index array tasks / pain_points pada request yang sama (bukan ID database).
            'findings.*.task_index' => ['nullable', 'integer', 'min:0'],
            'findings.*.pain_point_index' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function attributes(): array
    {
        return [
            'kode_evaluasi' => 'kode evaluasi',
            'informant.nama' => 'nama informan',
            'informant.kode' => 'kode/inisial informan',
            'session.tanggal' => 'tanggal sesi',
            'tasks.*.tujuan' => 'tujuan task',
            'findings.*.judul' => 'judul finding',
            'findings.*.kategori' => 'kategori finding',
            'findings.*.severity' => 'severity finding',
            'pain_points.*.deskripsi' => 'deskripsi pain point',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Kolom :attribute wajib diisi.',
            'required_with' => 'Kolom :attribute wajib diisi.',
            'tasks.required' => 'Minimal harus ada 1 task dalam evaluasi ini.',
            'tasks.min' => 'Minimal harus ada 1 task dalam evaluasi ini.',
            'kode_evaluasi.unique' => 'Kode evaluasi ini sudah digunakan.',
            'in' => 'Pilihan :attribute tidak valid.',
            'date' => 'Format :attribute tidak valid.',
        ];
    }
}
