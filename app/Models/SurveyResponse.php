<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyResponse extends Model
{
    // Nama kelas "SurveyResponse" (bukan "Response") untuk menghindari bentrok
    // dengan Illuminate\Http\Response, tapi nama tabelnya tetap sederhana: "responses".
    protected $table = 'responses';

    protected $fillable = ['nama', 'ruangan', 'lama_penggunaan'];

    public function answers()
    {
        return $this->hasMany(ResponseAnswer::class, 'response_id');
    }
}
