<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = ['kode_evaluasi', 'status'];

    public function informant()
    {
        return $this->hasOne(Informant::class);
    }

    public function session()
    {
        return $this->hasOne(EvaluationSession::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function interview()
    {
        return $this->hasOne(Interview::class);
    }

    public function insight()
    {
        return $this->hasOne(Insight::class);
    }

    public function painPoints()
    {
        return $this->hasMany(PainPoint::class);
    }

    public function findings()
    {
        return $this->hasMany(Finding::class);
    }
}
