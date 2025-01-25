<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Turma extends Model
{
    protected $fillable = [
        'nome',
        'abreviacao',
        'curso_id',
        'ano_lectivo_id',
        'escola_id',
        'classe',
        'turno',
        'sala',
        'director_turma_id',
    ];

    public function escola(): BelongsTo
    {
        return $this->belongsTo(Escola::class);
    }

    // Uma Turma pertence a um Curso
    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    // Uma Turma pertence a um Ano Letivo
    public function anoLectivo(): BelongsTo
    {
        return $this->belongsTo(AnoLectivo::class, 'ano_lectivo_id');
    }

    public function directorTurma()
    {
        return $this->belongsTo(Professor::class, 'director_turma_id');
    }

    // Uma Turma pode ter várias Matrículas
    public function matriculas(): HasMany
    {
        return $this->hasMany(Matricula::class);
    }

    // Uma Turma pode ter várias Disciplinas (através da tabela disciplina_turma)
    public function disciplinaTurmas(): HasMany
    {
        return $this->hasMany(DisciplinaTurma::class);
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
