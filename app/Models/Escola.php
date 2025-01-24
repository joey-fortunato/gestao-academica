<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

    class Escola extends Model
    {
    protected $fillable = [
        'nome',
        'tipo',
        'codigo_escola',
        'director',
        'subdirector',
        'endereco',
        'telefone',
        'observacoes'
    ];


    public function alunos(): HasMany
    {
        return $this->hasMany(Aluno::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
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
