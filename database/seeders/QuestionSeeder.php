<?php

namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'navigation' => [
                'Apakah Anda kesulitan menemukan menu atau fitur yang Anda butuhkan di SI-IMUT?',
                'Apakah Anda merasa alur perpindahan antar halaman di SI-IMUT membingungkan?',
                'Apakah Anda kesulitan untuk kembali ke halaman sebelumnya saat menggunakan SI-IMUT?',
            ],
            'interaction' => [
                'Apakah Anda mengalami kendala saat mengisi form/input data di SI-IMUT?',
                'Apakah tombol atau aksi (simpan, kirim, hapus, dll) di SI-IMUT tidak merespons sesuai harapan Anda?',
                'Apakah Anda kesulitan memahami pesan error atau notifikasi yang muncul di SI-IMUT?',
            ],
            'content' => [
                'Apakah informasi atau istilah yang ditampilkan di SI-IMUT sulit Anda pahami?',
                'Apakah Anda pernah menemukan informasi yang tidak lengkap atau tidak sesuai di SI-IMUT?',
                'Apakah urutan/susunan konten pada halaman SI-IMUT membingungkan bagi Anda?',
            ],
            'visual' => [
                'Apakah tampilan SI-IMUT (warna, ukuran teks, tata letak) membuat Anda tidak nyaman?',
                'Apakah Anda kesulitan membaca teks atau melihat elemen tertentu pada SI-IMUT?',
                'Apakah tampilan SI-IMUT terlihat tidak konsisten antar halaman?',
            ],
            'functionality' => [
                'Apakah ada fitur SI-IMUT yang tidak berjalan/berfungsi sebagaimana mestinya?',
                'Apakah SI-IMUT terasa lambat atau lama merespons saat Anda gunakan?',
                'Apakah Anda pernah mengalami error atau sistem macet saat menggunakan SI-IMUT?',
            ],
        ];

        $kodePrefix = [
            'navigation' => 'NAV', 'interaction' => 'INT', 'content' => 'CON',
            'visual' => 'VIS', 'functionality' => 'FUN',
        ];

        foreach ($data as $kategori => $daftarPertanyaan) {
            foreach ($daftarPertanyaan as $index => $teks) {
                Question::create([
                    'kategori' => $kategori,
                    'kode' => $kodePrefix[$kategori].'-'.($index + 1),
                    'pertanyaan' => $teks,
                    'urutan' => $index + 1,
                ]);
            }
        }
    }
}
