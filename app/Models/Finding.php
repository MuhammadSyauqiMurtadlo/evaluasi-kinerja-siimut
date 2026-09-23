<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Finding extends Model
{
    protected $fillable = [
        'evaluation_id', 'task_id', 'pain_point_id', 'judul', 'deskripsi',
        'kategori', 'severity', 'frequency', 'impact', 'root_cause', 'catatan',
    ];

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function painPoint()
    {
        return $this->belongsTo(PainPoint::class);
    }
}
