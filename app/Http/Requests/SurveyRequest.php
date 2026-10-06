<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SurveyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Tanpa auth — survei publik
    }

    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:150'],
            'ruangan' => ['required', 'string', 'max:100'],
            'lama_penggunaan' => ['required', 'string', 'max:50'],

            'answers' => ['required', 'array'],
            'answers.*.jawaban' => ['required', Rule::in(['ya', 'tidak'])],
            // required_if dengan wildcard: berlaku per-index yang sama (Laravel menangani ini otomatis)
            'answers.*.frekuensi' => ['nullable', 'required_if:answers.*.jawaban,ya', 'integer', 'between:1,4'],
            'answers.*.dampak' => ['nullable', 'required_if:answers.*.jawaban,ya', 'integer', 'between:1,4'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nama' => 'nama pengguna',
            'answers.*.frekuensi' => 'frekuensi kendala',
            'answers.*.dampak' => 'dampak kendala',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Kolom :attribute wajib diisi.',
            'required_if' => 'Kolom :attribute wajib diisi karena Anda menjawab "Ya".',
            'between' => 'Nilai :attribute harus antara 1 sampai 4.',
        ];
    }
}
