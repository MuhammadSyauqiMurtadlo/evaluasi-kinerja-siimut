<?php

use App\Models\Evaluation;
use App\Models\Finding;
use App\Models\PainPoint;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('updates an evaluation when its time value includes seconds', function () {
    $evaluation = Evaluation::create([
        'kode_evaluasi' => 'EV-2026-001',
        'status' => 'draft',
    ]);

    $response = $this->put(route('evaluasi.update', $evaluation), [
        'kode_evaluasi' => 'EV-2026-001-EDIT',
        'status' => 'selesai',
        'informant' => [
            'kode' => 'INF-EDIT',
            'nama' => 'Nama Baru',
        ],
        'session' => [
            'tanggal' => '2026-09-27',
            'waktu' => '10:15:00',
        ],
        'tasks' => [
            [
                'tujuan' => 'Tujuan task baru',
                'status' => 'berhasil',
                'observation' => ['tindakan' => 'Tindakan baru'],
            ],
        ],
        'interview' => ['catatan' => 'Interview baru'],
        'insight' => ['catatan' => 'Insight baru'],
        'pain_points' => [
            ['deskripsi' => 'Pain point baru'],
        ],
        'findings' => [
            [
                'judul' => 'Finding baru',
                'deskripsi' => 'Deskripsi finding baru',
                'kategori' => 'interaction',
                'severity' => 'high',
                'task_index' => '0',
                'pain_point_index' => '0',
            ],
        ],
    ]);

    $response->assertRedirectToRoute('evaluasi.show', $evaluation);
    $response->assertSessionHasNoErrors();
    $this->assertDatabaseHas('evaluations', [
        'id' => $evaluation->id,
        'kode_evaluasi' => 'EV-2026-001-EDIT',
        'status' => 'selesai',
    ]);
    $this->assertDatabaseHas('informants', [
        'evaluation_id' => $evaluation->id,
        'kode' => 'INF-EDIT',
        'nama' => 'Nama Baru',
    ]);
    $this->assertDatabaseHas('evaluation_sessions', [
        'evaluation_id' => $evaluation->id,
        'waktu' => '10:15:00',
    ]);
    $this->assertDatabaseHas('tasks', [
        'evaluation_id' => $evaluation->id,
        'tujuan' => 'Tujuan task baru',
    ]);
    $this->assertDatabaseHas('observations', ['tindakan' => 'Tindakan baru']);
    $this->assertDatabaseHas('interviews', [
        'evaluation_id' => $evaluation->id,
        'catatan' => 'Interview baru',
    ]);
    $this->assertDatabaseHas('insights', [
        'evaluation_id' => $evaluation->id,
        'catatan' => 'Insight baru',
    ]);
    $this->assertDatabaseHas('pain_points', [
        'evaluation_id' => $evaluation->id,
        'deskripsi' => 'Pain point baru',
    ]);

    $task = Task::query()->where('evaluation_id', $evaluation->id)->firstOrFail();
    $painPoint = PainPoint::query()->where('evaluation_id', $evaluation->id)->firstOrFail();
    $finding = Finding::query()->where('evaluation_id', $evaluation->id)->firstOrFail();

    expect($finding->task_id)->toBe($task->id)
        ->and($finding->pain_point_id)->toBe($painPoint->id);
});

it('shows the stored session time in the edit form without seconds', function () {
    $evaluation = Evaluation::create([
        'kode_evaluasi' => 'EV-2026-002',
        'status' => 'draft',
    ]);
    $evaluation->session()->create([
        'tanggal' => '2026-09-27',
        'waktu' => '10:15:00',
    ]);

    $response = $this->get(route('evaluasi.edit', $evaluation));

    $response->assertOk();
    $response->assertSee('name="session[waktu]"', false);
    $response->assertSee('value="10:15"', false);
});
