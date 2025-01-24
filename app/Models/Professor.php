<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Professor extends Model
{
    protected $table = 'professores';

    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'escola_id',
    ];

    public function escola(): BelongsTo
    {
        return $this->belongsTo(Escola::class);
    }

    public function disciplinas(): BelongsToMany
    {
        return $this->belongsToMany(Disciplina::class, 'disciplina_professor');
    }

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
