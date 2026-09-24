<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class AnalisisController extends Controller
{
    public function index(): View
    {
        // Agregasi kategori/severity/informan/task/frequency + scoring
        // akan diimplementasikan lengkap di Tahap 7.
        return view('analisis.index');
    }
}
