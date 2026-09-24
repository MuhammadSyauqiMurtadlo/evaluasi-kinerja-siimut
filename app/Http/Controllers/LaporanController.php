<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function index(Request $request): View
    {
        // Filter evaluasi/informan/kategori/severity/rentang tanggal
        // akan diimplementasikan lengkap di Tahap 8.
        return view('laporan.index');
    }

    public function exportPdf(Request $request)
    {
        // Export PDF via barryvdh/laravel-dompdf, di Tahap 8.
    }
}
