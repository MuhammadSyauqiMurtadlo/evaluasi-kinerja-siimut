<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\SurveyController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::get('/survey', [SurveyController::class, 'create'])->name('survey.create');
Route::post('/survey', [SurveyController::class, 'store'])->name('survey.store');
Route::get('/survey/selesai', [SurveyController::class, 'selesai'])->name('survey.selesai');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
