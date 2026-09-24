<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Finding;
use App\Models\Task;
use Illuminate\View\View;

class AnalisisController extends Controller
{
    // Bobot severity untuk keperluan scoring internal — bukan standar baku manapun.
    private const BOBOT_SEVERITY = [
        'low' => 1, 'medium' => 2, 'high' => 3, 'critical' => 4,
    ];

    public function index(): View
    {
        $totalEvaluasi = Evaluation::count();

        // --- Distribusi per Kategori (+ jumlah informan/evaluasi berbeda yang mengalaminya) ---
        $perKategori = Finding::select('kategori')
            ->selectRaw('COUNT(*) as total_finding')
            ->selectRaw('COUNT(DISTINCT evaluation_id) as jumlah_informan')
            ->groupBy('kategori')
            ->get();

        // --- Distribusi per Severity ---
        $perSeverity = Finding::selectRaw('severity, COUNT(*) as total')
            ->groupBy('severity')
            ->pluck('total', 'severity');

        // --- Task: tingkat keberhasilan ---
        $totalTask = Task::count();
        $taskBerhasil = Task::where('status', 'berhasil')->count();
        $taskGagal = Task::where('status', 'gagal')->count();
        $successRate = $totalTask > 0 ? round($taskBerhasil / $totalTask * 100, 1) : null;

        // --- Finding per Informan (via relasi evaluation->informant) ---
        $perInforman = Finding::join('evaluations', 'findings.evaluation_id', '=', 'evaluations.id')
            ->join('informants', 'informants.evaluation_id', '=', 'evaluations.id')
            ->select('informants.nama as informan')
            ->selectRaw('COUNT(findings.id) as total_finding')
            ->groupBy('informants.nama')
            ->orderByDesc('total_finding')
            ->get();

        // --- High-priority findings (severity high & critical) ---
        $highPriorityFindings = Finding::with(['evaluation.informant', 'task'])
            ->whereIn('severity', ['high', 'critical'])
            ->orderByRaw("FIELD(severity, 'critical','high')")
            ->get();

        // --- Masalah yang muncul pada beberapa informan (kategori yang sama dilaporkan >= 2 informan berbeda) ---
        $isuBerulang = $perKategori->where('jumlah_informan', '>=', 2)->sortByDesc('jumlah_informan');

        // --- Frequency mentah (field bebas teks, ditampilkan apa adanya, diurutkan berdasarkan severity) ---
        $frequencyList = Finding::with('evaluation.informant')
            ->whereNotNull('frequency')
            ->orderByRaw("FIELD(severity, 'critical','high','medium','low')")
            ->get(['id', 'judul', 'severity', 'frequency', 'evaluation_id']);

        // --- Scoring UX sederhana (indikator internal, BUKAN hasil metodologis resmi) ---
        $totalFinding = Finding::count();
        $bobotTotal = Finding::get()->sum(fn ($f) => self::BOBOT_SEVERITY[$f->severity] ?? 0);
        $indeksSeverity = $totalFinding > 0
            ? round($bobotTotal / ($totalFinding * 4) * 100, 1) // 0 (ringan) - 100 (berat)
            : 0;

        $skorUx = round(
            (100 - $indeksSeverity) * 0.5 + ($successRate ?? 100) * 0.5,
            1
        );

        $skorLabel = match (true) {
            $skorUx >= 80 => ['label' => 'Baik', 'warna' => 'success'],
            $skorUx >= 60 => ['label' => 'Cukup', 'warna' => 'warning'],
            default => ['label' => 'Perlu Perbaikan', 'warna' => 'danger'],
        };

        return view('analisis.index', compact(
            'totalEvaluasi', 'perKategori', 'perSeverity',
            'totalTask', 'taskBerhasil', 'taskGagal', 'successRate',
            'perInforman', 'highPriorityFindings', 'isuBerulang', 'frequencyList',
            'indeksSeverity', 'skorUx', 'skorLabel'
        ));
    }
}
