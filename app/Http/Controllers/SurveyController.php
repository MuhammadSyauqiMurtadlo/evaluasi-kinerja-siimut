<?php

namespace App\Http\Controllers;

use App\Http\Requests\SurveyRequest;
use App\Models\Question;
use App\Models\ResponseAnswer;
use App\Models\SurveyResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SurveyController extends Controller
{
    public function create(): View
    {
        $questions = Question::orderBy('kategori')->orderBy('urutan')->get()->groupBy('kategori');

        return view('survey.create', compact('questions'));
    }

    public function store(SurveyRequest $request): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($data) {
            $response = SurveyResponse::create([
                'nama' => $data['nama'],
                'ruangan' => $data['ruangan'],
                'lama_penggunaan' => $data['lama_penggunaan'],
            ]);

            foreach ($data['answers'] as $questionId => $answer) {
                $adaKendala = $answer['jawaban'] === 'ya';

                ResponseAnswer::create([
                    'response_id' => $response->id,
                    'question_id' => $questionId,
                    'jawaban' => $answer['jawaban'],
                    'frekuensi' => $adaKendala ? $answer['frekuensi'] : null,
                    'dampak' => $adaKendala ? $answer['dampak'] : null,
                ]);
            }
        });

        return redirect()->route('survey.selesai')
            ->with('success', 'Terima kasih! Jawaban Anda berhasil disimpan.');
    }

    public function selesai(): View
    {
        return view('survey.selesai');
    }
}
