<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AnoLectivo extends Model

{
    protected $table = 'ano_lectivo';
    protected $fillable = [
        'ano', // Ano no formato AAAA/AAAA
        'data_inicio', // Data de início
        'data_fim', // Data de término
        'ativo', // Indica se o ano letivo está ativo
    ];

    // Um Ano Letivo pode ter várias Turmas
    public function turmas(): HasMany
    {
        return $this->hasMany(Turma::class);
    }

    // Método para obter o ano letivo ativo
    public static function getAnoLetivoAtivo()
    {
        return self::where('ativo', true)->first();
    }

}
