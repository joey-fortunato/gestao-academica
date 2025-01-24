<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('alunos', function (Blueprint $table) {
            $table->string('foto')->nullable(); // Foto do aluno
            $table->string('naturalidade')->nullable(); // Naturalidade
            $table->string('provincia')->nullable(); // Província
            $table->string('municipio')->nullable(); // Município
            $table->date('data_emissao')->nullable(); // Data de emissão
            $table->date('data_expiracao')->nullable(); // Data de expiração
            $table->string('estado_civil')->nullable(); // Estado civil
            $table->string('nome_pai')->nullable(); // Nome do pai
            $table->string('nome_mae')->nullable(); // Nome da mãe
            $table->string('nome_encarregado')->nullable(); // Nome do encarregado de educação
            $table->string('telefone_encarregado')->nullable(); // Telefone do encarregado de educação
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alunos', function (Blueprint $table) {
            $table->dropColumn([
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
            ]);
        });
    }
};
