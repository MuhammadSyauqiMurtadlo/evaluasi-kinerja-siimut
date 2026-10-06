<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\ResponseAnswer;
use App\Models\SurveyResponse;
use Illuminate\Database\Seeder;

class SurveyResponseSeeder extends Seeder
{
    public function run(): void
    {
        $questions = Question::all();

        $respondenDemo = [
            ['nama' => 'Dewi Lestari', 'ruangan' => 'Ruang Rawat Inap', 'lama_penggunaan' => '1 - 2 tahun'],
            ['nama' => 'Hendra Saputra', 'ruangan' => 'IGD (Instalasi Gawat Darurat)', 'lama_penggunaan' => '> 2 tahun'],
            ['nama' => 'Nurul Aini', 'ruangan' => 'Laboratorium', 'lama_penggunaan' => '6 bulan - 1 tahun'],
            ['nama' => 'Agus Pranoto', 'ruangan' => 'Farmasi', 'lama_penggunaan' => '< 6 bulan'],
            ['nama' => 'Rina Wulandari', 'ruangan' => 'Bagian Mutu / PMKP', 'lama_penggunaan' => '> 2 tahun'],
        ];

        foreach ($respondenDemo as $data) {
            $response = SurveyResponse::create($data);

            foreach ($questions as $q) {
                // Simulasi demo: ~50% responden menemukan kendala di tiap pertanyaan
                $adaKendala = rand(0, 1) === 1;

                ResponseAnswer::create([
                    'response_id' => $response->id,
                    'question_id' => $q->id,
                    'jawaban' => $adaKendala ? 'ya' : 'tidak',
                    'frekuensi' => $adaKendala ? rand(1, 4) : null,
                    'dampak' => $adaKendala ? rand(1, 4) : null,
                ]);
            }
        }
    }
}
