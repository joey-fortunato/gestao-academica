<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Curso extends Model
{
    protected $fillable = [
        'nome',
        'descricao',
    ];

    public function turmas(): HasMany
    {
        return $this->hasMany(Turma::class);
    }


    public function disciplinas()
    {
        return $this->belongsToMany(Disciplina::class, 'curso_disciplina');
    }
}
