<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Matricula extends Model
{
    protected $fillable = [
        'aluno_id',
        'turma_id',
        'data_matricula',
        'escola_id',
    ];

    public function escola():belongsTo
    {
        return $this->belongsTo(Escola::class);
    }

    // Uma Matrícula pertence a um Aluno
    public function aluno(): BelongsTo
    {
        return $this->belongsTo(Aluno::class);
    }

    // Uma Matrícula pertence a uma Turma
    public function turma(): BelongsTo
    {
        return $this->belongsTo(Turma::class);
    }
    protected static function boot()
    {
        parent::boot();

        // Aplica o escopo global para filtrar os dados pela escola do usuário logado
        static::addGlobalScope('escola', function (Builder $builder) {
            if (auth()->check() && auth()->user()->escola_id) {
                $builder->where('escola_id', auth()->user()->escola_id);
            }
        });
    }
}
