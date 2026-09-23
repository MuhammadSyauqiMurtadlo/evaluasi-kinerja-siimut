<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Insight extends Model
{
    protected $fillable = ['evaluation_id', 'catatan'];

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }
}
