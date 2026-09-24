<?php

namespace App\Http\Controllers;

use App\Http\Requests\EvaluasiRequest;
use App\Models\Evaluation;
use App\Models\Finding;
use App\Models\Informant;
use App\Models\Insight;
use App\Models\Interview;
use App\Models\Observation;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class EvaluasiController extends Controller
{
    public function index(): View
    {
        $evaluasi = Evaluation::with(['informant', 'session'])
            ->withCount(['tasks', 'painPoints', 'findings'])
            ->latest()
            ->paginate(10);

        return view('evaluasi.index', compact('evaluasi'));
    }

    public function create(): View
    {
        $kodeBerikutnya = $this->generateKodeEvaluasi();

        return view('evaluasi.create', compact('kodeBerikutnya'));
    }

    public function store(EvaluasiRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $evaluation = DB::transaction(function () use ($data) {
            return $this->simpanEvaluasiLengkap($data);
        });

        return redirect()
            ->route('evaluasi.show', $evaluation)
            ->with('success', 'Evaluasi berhasil disimpan.');
    }

    public function show(Evaluation $evaluasi): View
    {
        $evaluasi->load([
            'informant',
            'session',
            'tasks.observations',
            'interview',
            'insight',
            'painPoints',
            'findings.task',
            'findings.painPoint',
        ]);

        return view('evaluasi.show', ['evaluasi' => $evaluasi]);
    }

    public function edit(Evaluation $evaluasi): View
    {
        $evaluasi->load([
            'informant',
            'session',
            'tasks.observations',
            'interview',
            'insight',
            'painPoints',
            'findings',
        ]);

        return view('evaluasi.edit', ['evaluasi' => $evaluasi]);
    }

    public function update(EvaluasiRequest $request, Evaluation $evaluasi): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($data, $evaluasi) {
            $evaluasi->update([
                'kode_evaluasi' => $data['kode_evaluasi'],
                'status' => $data['status'] ?? $evaluasi->status,
            ]);

            // hasOne: update-or-create langsung
            Informant::updateOrCreate(['evaluation_id' => $evaluasi->id], $data['informant']);
            $evaluasi->session()->updateOrCreate(['evaluation_id' => $evaluasi->id], $data['session']);
            Interview::updateOrCreate(['evaluation_id' => $evaluasi->id], $data['interview'] ?? ['catatan' => null]);
            Insight::updateOrCreate(['evaluation_id' => $evaluasi->id], $data['insight'] ?? ['catatan' => null]);

            // hasMany dengan struktur kompleks (task->observasi, pain point, finding):
            // hapus semua lalu buat ulang dari payload — pendekatan paling sederhana untuk full-form submit.
            $evaluasi->tasks()->each(fn (Task $task) => $task->observations()->delete());
            $evaluasi->tasks()->delete();
            $evaluasi->painPoints()->delete();
            $evaluasi->findings()->delete();

            $this->simpanTaskPainPointFinding($evaluasi, $data);
        });

        return redirect()
            ->route('evaluasi.show', $evaluasi)
            ->with('success', 'Evaluasi berhasil diperbarui.');
    }

    public function destroy(Evaluation $evaluasi): RedirectResponse
    {
        // Semua child table pakai cascadeOnDelete di migration, jadi cukup hapus evaluasi induk.
        $evaluasi->delete();

        return redirect()
            ->route('evaluasi.index')
            ->with('success', 'Evaluasi berhasil dihapus.');
    }

    /**
     * Simpan evaluasi baru beserta seluruh child record dalam satu alur.
     */
    private function simpanEvaluasiLengkap(array $data): Evaluation
    {
        $evaluation = Evaluation::create([
            'kode_evaluasi' => $data['kode_evaluasi'],
            'status' => $data['status'] ?? 'draft',
        ]);

        Informant::create(['evaluation_id' => $evaluation->id, ...$data['informant']]);
        $evaluation->session()->create($data['session']);
        Interview::create(['evaluation_id' => $evaluation->id, ...($data['interview'] ?? ['catatan' => null])]);
        Insight::create(['evaluation_id' => $evaluation->id, ...($data['insight'] ?? ['catatan' => null])]);

        $this->simpanTaskPainPointFinding($evaluation, $data);

        return $evaluation;
    }

    /**
     * Simpan tasks+observasi, pain points, lalu findings — dengan pemetaan
     * task_index / pain_point_index (posisi array di form) ke ID asli hasil insert.
     */
    private function simpanTaskPainPointFinding(Evaluation $evaluation, array $data): void
    {
        $taskIdByIndex = [];
        foreach ($data['tasks'] as $index => $taskData) {
            $observationData = $taskData['observation'] ?? [];
            unset($taskData['observation']);

            $task = $evaluation->tasks()->create($taskData);

            Observation::create(['task_id' => $task->id, ...$observationData]);

            $taskIdByIndex[$index] = $task->id;
        }

        $painPointIdByIndex = [];
        foreach (($data['pain_points'] ?? []) as $index => $ppData) {
            $painPoint = $evaluation->painPoints()->create($ppData);
            $painPointIdByIndex[$index] = $painPoint->id;
        }

        foreach (($data['findings'] ?? []) as $findingData) {
            $taskIndex = $findingData['task_index'] ?? null;
            $ppIndex = $findingData['pain_point_index'] ?? null;
            unset($findingData['task_index'], $findingData['pain_point_index']);

            Finding::create([
                'evaluation_id' => $evaluation->id,
                'task_id' => $taskIndex !== null ? ($taskIdByIndex[$taskIndex] ?? null) : null,
                'pain_point_id' => $ppIndex !== null ? ($painPointIdByIndex[$ppIndex] ?? null) : null,
                ...$findingData,
            ]);
        }
    }

    private function generateKodeEvaluasi(): string
    {
        $tahun = now()->year;
        $jumlahTahunIni = Evaluation::whereYear('created_at', $tahun)->count();

        return sprintf('EV-%d-%03d', $tahun, $jumlahTahunIni + 1);
    }
}
