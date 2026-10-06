<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['kategori', 'kode', 'pertanyaan', 'urutan'];

    public function answers()
    {
        return $this->hasMany(ResponseAnswer::class);
    }
}
