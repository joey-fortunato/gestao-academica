<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
class Aluno extends Model
{
    protected $fillable = [
        'numero_processo',
        'nome',
        'sexo',
        'bi',
        'email',
        'telefone',
        'data_nascimento',
        'residencia',
        'escola_id',
        'foto',
        'naturalidade',
        'provincia',
        'municipio',
        'data_emissao',
        'data_expiracao',
        'estado_civil',
        'nome_pai',
        'nome_mae',
        'nome_encarregado',
        'telefone_encarregado',
    ];

    public function escola(): BelongsTo
    {
        return $this->belongsTo(Escola::class);
    }

    public function matriculas(): belongsTo
    {
        return $this->belongsTo(Matricula::class);
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
