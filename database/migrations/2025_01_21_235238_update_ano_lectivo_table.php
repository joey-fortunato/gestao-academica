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
        Schema::table('ano_lectivo', function (Blueprint $table) {
            // Altera o campo "ano" para string (formato AAAA/AAAA)
            $table->string('ano', 9)->unique()->change();

            // Adiciona o campo "data_inicio"
            $table->date('data_inicio')->after('ano');

            // Adiciona o campo "data_fim"
            $table->date('data_fim')->after('data_inicio');

            // Adiciona o campo "ativo"
            $table->boolean('ativo')->default(true)->after('data_fim');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ano_letivos', function (Blueprint $table) {
            // Reverte as alterações
            $table->integer('ano')->change(); // Reverte o campo "ano" para inteiro
            $table->dropColumn(['data_inicio', 'data_fim', 'ativo']); // Remove os novos campos
        });
    }
};
