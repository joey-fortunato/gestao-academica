<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Trimestre extends Model
{
    protected $fillable = [
        'nome',
        'data_inicio',
        'data_fim',
    ];

    public function nota()
    {
        return $this->belongsTo(Nota::class);
    }
}
