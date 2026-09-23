<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Informant extends Model
{
    protected $fillable = [
        'evaluation_id', 'kode', 'nama', 'jabatan', 'unit',
        'pengalaman_penggunaan', 'frekuensi_penggunaan', 'catatan',
    ];

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }
}
