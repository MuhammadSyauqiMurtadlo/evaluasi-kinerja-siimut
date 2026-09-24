<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Finding;
use App\Models\Informant;
use App\Models\PainPoint;
use App\Models\Task;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_evaluasi' => Evaluation::count(),
            'total_informan' => Informant::count(),
            'total_task' => Task::count(),
            'total_pain_point' => PainPoint::count(),
            'total_finding' => Finding::count(),
        ];

        $findingPerKategori = Finding::selectRaw('kategori, COUNT(*) as total')
            ->groupBy('kategori')
            ->pluck('total', 'kategori');

        $findingPerSeverity = Finding::selectRaw('severity, COUNT(*) as total')
            ->groupBy('severity')
            ->pluck('total', 'severity');

        $evaluasiTerbaru = Evaluation::with('informant')
            ->withCount('findings')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'stats', 'findingPerKategori', 'findingPerSeverity', 'evaluasiTerbaru'
        ));
    }
}
