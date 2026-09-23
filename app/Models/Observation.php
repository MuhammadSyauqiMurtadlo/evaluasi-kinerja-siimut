<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Observation extends Model
{
    protected $fillable = [
        'task_id', 'tindakan', 'perilaku', 'reaksi',
        'kesulitan', 'kebingungan', 'strategi_pengguna', 'catatan',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
