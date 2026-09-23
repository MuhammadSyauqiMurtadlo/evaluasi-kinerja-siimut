<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PainPoint extends Model
{
    protected $fillable = ['evaluation_id', 'deskripsi', 'catatan'];

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function findings()
    {
        return $this->hasMany(Finding::class);
    }
}
