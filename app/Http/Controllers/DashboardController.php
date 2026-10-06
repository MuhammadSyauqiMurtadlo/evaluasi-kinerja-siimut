<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\ResponseAnswer;
use App\Models\SurveyResponse;
use App\Support\SeverityRating;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalResponden = SurveyResponse::count();
        $questions = Question::orderBy('kategori')->orderBy('urutan')->get();

        // Satu query agregat untuk semua pertanyaan sekaligus — menghindari N+1.
        $agregat = ResponseAnswer::selectRaw(
            'question_id, jawaban, COUNT(*) as jumlah, AVG(frekuensi) as avg_frekuensi, AVG(dampak) as avg_dampak'
        )
            ->groupBy('question_id', 'jawaban')
            ->get()
            ->groupBy('question_id');

        $perPertanyaan = $questions->map(function (Question $q) use ($agregat) {
            $rows = $agregat->get($q->id, collect());
            $barisYa = $rows->firstWhere('jawaban', 'ya');
            $barisTidak = $rows->firstWhere('jawaban', 'tidak');

            $avgF = $barisYa->avg_frekuensi ?? null;
            $avgD = $barisYa->avg_dampak ?? null;
            $sr = ($avgF !== null && $avgD !== null) ? round(($avgF + $avgD) / 2, 2) : null;

            return [
                'question' => $q,
                'jumlah_ya' => $barisYa->jumlah ?? 0,
                'jumlah_tidak' => $barisTidak->jumlah ?? 0,
                'avg_frekuensi' => $avgF !== null ? round($avgF, 2) : null,
                'avg_dampak' => $avgD !== null ? round($avgD, 2) : null,
                'sr' => $sr,
                'klasifikasi' => $sr !== null ? SeverityRating::classify($sr) : null,
            ];
        });

        $perKategori = $perPertanyaan
            ->groupBy(fn ($item) => $item['question']->kategori)
            ->map(function ($items, $kategori) {
                $validSr = $items->pluck('sr')->filter(fn ($v) => $v !== null)->values();
                $avgSr = $validSr->count() > 0 ? round($validSr->avg(), 2) : null;

                return [
                    'kategori' => $kategori,
                    'avg_sr' => $avgSr,
                    'klasifikasi' => $avgSr !== null ? SeverityRating::classify($avgSr) : null,
                ];
            });

        $totalJawabanYa = $perPertanyaan->sum('jumlah_ya');
        $totalJawabanTidak = $perPertanyaan->sum('jumlah_tidak');

        return view('dashboard.index', compact(
            'totalResponden', 'perPertanyaan', 'perKategori', 'totalJawabanYa', 'totalJawabanTidak'
        ));
    }
}
