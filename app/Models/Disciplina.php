<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Disciplina extends Model
{
    protected $fillable = [
        'nome',
        'classe',
        'descricao',

    ];

    public function cursos()
    {
        return $this->belongsToMany(Curso::class, 'curso_disciplina');
    }

    // Uma Disciplina pode ser lecionada em várias Turmas (através da tabela disciplina_turma)
    public function disciplinaTurmas(): HasMany
    {
        return $this->hasMany(DisciplinaTurma::class);
    }

    public function professores(): BelongsToMany
    {
        return $this->belongsToMany(Professor::class, 'disciplina_professor');
    }
}
