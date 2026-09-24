<?php

use App\Http\Controllers\AnalisisController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EvaluasiController;
use App\Http\Controllers\LaporanController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('evaluasi', EvaluasiController::class)
    ->parameters(['evaluasi' => 'evaluasi'])
    ->except(['create']); // create digabung ke halaman yang sama dengan index-form (lihat Tahap 6)

// create tetap disediakan terpisah untuk generate kode_evaluasi otomatis di form
Route::get('/evaluasi/create', [EvaluasiController::class, 'create'])->name('evaluasi.create');

Route::get('/analisis', [AnalisisController::class, 'index'])->name('analisis.index');

Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
Route::get('/laporan/export-pdf', [LaporanController::class, 'exportPdf'])->name('laporan.export-pdf');
