<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Finding;
use App\Models\Informant;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function index(Request $request): View
    {
        $findings = $this->buildFilteredQuery($request)->get();

        $ringkasan = [
            'total_evaluasi' => $findings->pluck('evaluation_id')->unique()->count(),
            'total_finding' => $findings->count(),
            'per_severity' => $findings->groupBy('severity')->map->count(),
            'per_kategori' => $findings->groupBy('kategori')->map->count(),
        ];

        $evaluasiOptions = Evaluation::orderBy('kode_evaluasi')->get(['id', 'kode_evaluasi']);
        $informanOptions = Informant::orderBy('nama')->get(['id', 'nama']);

        return view('laporan.index', [
            'findings' => $findings,
            'ringkasan' => $ringkasan,
            'evaluasiOptions' => $evaluasiOptions,
            'informanOptions' => $informanOptions,
            'filter' => array_merge(
                ['evaluation_id' => null, 'informant_id' => null, 'kategori' => null, 'severity' => null, 'tanggal_mulai' => null, 'tanggal_selesai' => null],
                $request->only(['evaluation_id', 'informant_id', 'kategori', 'severity', 'tanggal_mulai', 'tanggal_selesai'])
            ),
        ]);
    }

    public function exportPdf(Request $request)
    {
        $findings = $this->buildFilteredQuery($request)->get();

        $ringkasan = [
            'total_evaluasi' => $findings->pluck('evaluation_id')->unique()->count(),
            'total_finding' => $findings->count(),
            'per_severity' => $findings->groupBy('severity')->map->count(),
            'per_kategori' => $findings->groupBy('kategori')->map->count(),
        ];

        $pdf = Pdf::loadView('laporan.pdf', [
            'findings' => $findings,
            'ringkasan' => $ringkasan,
            'filter' => array_merge(
                ['evaluation_id' => null, 'informant_id' => null, 'kategori' => null, 'severity' => null, 'tanggal_mulai' => null, 'tanggal_selesai' => null],
                $request->only(['evaluation_id', 'informant_id', 'kategori', 'severity', 'tanggal_mulai', 'tanggal_selesai'])
            ),
            'dicetakPada' => now(),
        ])->setPaper('a4', 'portrait');

        return $pdf->download('laporan-evaluasi-si-imut-' . now()->format('Ymd-His') . '.pdf');
    }

    /**
     * Query dasar findings + join yang dipakai bareng oleh view (index) dan PDF (exportPdf),
     * supaya logic filter tidak duplikat.
     */
    private function buildFilteredQuery(Request $request)
    {
        return Finding::query()
            ->with(['evaluation.informant', 'evaluation.session', 'task', 'painPoint'])
            ->when($request->filled('evaluation_id'), fn ($q) => $q->where('evaluation_id', $request->evaluation_id))
            ->when($request->filled('informant_id'), function ($q) use ($request) {
                $q->whereHas('evaluation.informant', fn ($sub) => $sub->where('id', $request->informant_id));
            })
            ->when($request->filled('kategori'), fn ($q) => $q->where('kategori', $request->kategori))
            ->when($request->filled('severity'), fn ($q) => $q->where('severity', $request->severity))
            ->when($request->filled('tanggal_mulai'), function ($q) use ($request) {
                $q->whereHas('evaluation.session', fn ($sub) => $sub->whereDate('tanggal', '>=', $request->tanggal_mulai));
            })
            ->when($request->filled('tanggal_selesai'), function ($q) use ($request) {
                $q->whereHas('evaluation.session', fn ($sub) => $sub->whereDate('tanggal', '<=', $request->tanggal_selesai));
            })
            ->orderByRaw("FIELD(severity, 'critical','high','medium','low')")
            ->orderBy('evaluation_id');
    }
}
