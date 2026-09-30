<?php

use Database\Seeders\DemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

it('seeds eighteen complete evaluation packages', function () {
    app(DemoSeeder::class)->run();
    app(DemoSeeder::class)->run();

    expect(DB::table('evaluations')->count())->toBe(18)
        ->and(DB::table('informants')->count())->toBe(18)
        ->and(DB::table('evaluation_sessions')->count())->toBe(18)
        ->and(DB::table('tasks')->count())->toBe(20)
        ->and(DB::table('observations')->count())->toBe(20)
        ->and(DB::table('interviews')->count())->toBe(18)
        ->and(DB::table('insights')->count())->toBe(18)
        ->and(DB::table('pain_points')->count())->toBe(19)
        ->and(DB::table('findings')->count())->toBe(19);

    expect(DB::table('informants')
        ->where('kode', 'INF-18')
        ->where('nama', 'Safira Ramadhani')
        ->exists())->toBeTrue();
});
