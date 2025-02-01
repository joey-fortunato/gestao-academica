<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Nota extends Model
{
    protected $fillable = [
        'aluno_id',
        'turma_id',
        'trimestre_id',
        'mac',
        'npp',
        'npt',
        'media',
    ];

    protected static function boot()
    {
        parent::boot();

        // Validações ao salvar uma nota
        static::saving(function ($nota) {
            $nota->calcularMedia();
        });
    }

    // Relacionamentos
    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
    }

    public function turma()
    {
        return $this->belongsTo(Turma::class);
    }

    public function trimestre()
    {
        return $this->belongsTo(Trimestre::class);
    }

    public function calcularMedia()
    {
        $media = ($this->mac + $this->npp + $this->npt) / 3;
        $this->media = round($media); // Arredonda a média
    }
}
