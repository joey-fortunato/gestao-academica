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

    }
}
