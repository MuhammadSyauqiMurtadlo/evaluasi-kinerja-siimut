<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluationSession extends Model
{
    protected $table = 'evaluation_sessions';

    protected $fillable = [
        'evaluation_id', 'tanggal', 'waktu', 'durasi', 'tujuan',
        'konteks', 'lingkungan', 'perangkat', 'kondisi_penggunaan', 'catatan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }
}
