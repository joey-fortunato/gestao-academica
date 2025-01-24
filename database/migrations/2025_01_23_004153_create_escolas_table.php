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
        Schema::create('escolas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('tipo')->default('filial'); // 'principal' ou 'filial'
            $table->string('codigo_escola')->unique()->nullable(); // Código único da escola
            $table->string('diretor')->nullable(); // Nome do diretor
            $table->string('subdiretor')->nullable(); // Nome do subdiretor
            $table->string('endereco')->nullable(); // Endereço completo
            $table->string('telefone')->nullable(); // Telefone de contato
            $table->text('observacoes')->nullable(); // Observações ou notas adicionais
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('escolas');
    }
};
