<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DisciplinaTurma extends Model
{
    protected $fillable = [
        'disciplina_id',
        'turma_id',
        'professor_id',
    ];

    // Uma DisciplinaTurma pertence a uma Disciplina
    public function disciplina(): BelongsTo
    {
        return $this->belongsTo(Disciplina::class);
    }

    // Uma DisciplinaTurma pertence a uma Turma
    public function turma(): BelongsTo
    {
        return $this->belongsTo(Turma::class);
    }

    // Uma DisciplinaTurma pertence a um Professor
    public function professor(): BelongsTo
    {
        return $this->belongsTo(Professor::class);
    }
}
