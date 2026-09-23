<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'evaluation_id', 'tujuan', 'instruksi', 'status',
        'waktu_penyelesaian', 'catatan',
    ];

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function observations()
    {
        return $this->hasMany(Observation::class);
    }

    public function findings()
    {
        return $this->hasMany(Finding::class);
    }
}
