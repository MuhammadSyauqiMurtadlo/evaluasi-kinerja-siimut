<?php

namespace App\Http\Controllers;

use App\Models\SurveyResponse;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function index(): View
    {
        $totalResponden = SurveyResponse::count();

        return view('landing.index', compact('totalResponden'));
    }
}
