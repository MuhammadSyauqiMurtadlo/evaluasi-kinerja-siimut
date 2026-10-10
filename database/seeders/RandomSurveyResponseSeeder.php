<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\ResponseAnswer;
use App\Models\SurveyResponse;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RandomSurveyResponseSeeder extends Seeder
{
    public function run(): void
    {
        $questions = Question::query()->get();
        $ruangan = [
            'Ruang Rawat Inap',
            'Ruang Rawat Jalan / Poli',
            'IGD (Instalasi Gawat Darurat)',
            'Laboratorium',
            'Farmasi',
            'Rekam Medis',
            'Administrasi / Tata Usaha',
            'Keuangan',
            'Bagian Mutu / PMKP',
            'Lainnya',
        ];
        $lamaPenggunaan = ['< 6 bulan', '6 bulan - 1 tahun', '1 - 2 tahun', '> 2 tahun'];

        DB::transaction(function () use ($questions, $ruangan, $lamaPenggunaan): void {
            for ($index = 0; $index < 7; $index++) {
                $response = SurveyResponse::create([
                    'nama' => fake()->name(),
                    'ruangan' => fake()->randomElement($ruangan),
                    'lama_penggunaan' => fake()->randomElement($lamaPenggunaan),
                ]);

                foreach ($questions as $question) {
                    $adaKendala = fake()->boolean();

                    ResponseAnswer::create([
                        'response_id' => $response->id,
                        'question_id' => $question->id,
                        'jawaban' => $adaKendala ? 'ya' : 'tidak',
                        'frekuensi' => $adaKendala ? fake()->numberBetween(1, 4) : null,
                        'dampak' => $adaKendala ? fake()->numberBetween(1, 4) : null,
                    ]);
                }
            }
        });
    }
}
