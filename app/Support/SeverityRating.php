<?php

namespace App\Support;

/**
 * Logika klasifikasi Severity Rating mengacu teori Jakob Nielsen,
 * sesuai rumus & tabel klasifikasi pada dokumen referensi penelitian.
 */
class SeverityRating
{
    public static function classify(float $score): array
    {
        return match (true) {
            $score >= 3.50 => [
                'label' => 'Usability Catastrophe',
                'priority' => 'Prioritas Utama (Kritis)',
                'warna' => 'danger',
            ],
            $score >= 2.50 => [
                'label' => 'Major Usability Problem',
                'priority' => 'Prioritas Tinggi (Wajib Diperbaiki)',
                'warna' => 'warning',
            ],
            $score >= 1.50 => [
                'label' => 'Minor Usability Problem',
                'priority' => 'Prioritas Rendah',
                'warna' => 'info',
            ],
            default => [
                'label' => 'Cosmetic Problem Only',
                'priority' => 'Prioritas Sangat Rendah',
                'warna' => 'secondary',
            ],
        };
    }
}
