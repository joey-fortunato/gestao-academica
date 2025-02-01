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
        Schema::create('notas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aluno_id')->constrained('alunos')->onDelete('cascade');
            $table->foreignId('turma_id')->constrained('turmas')->onDelete('cascade');
            $table->foreignId('trimestre_id')->constrained('trimestres')->onDelete('cascade');
            $table->decimal('mac', 5, 2); // Nota MAC (com 2 casas decimais)
            $table->decimal('npp', 5, 2); // Nota NPP (com 2 casas decimais)
            $table->decimal('npt', 5, 2); // Nota NPT (com 2 casas decimais)
            $table->decimal('media', 5, 2); // Média (calculada automaticamente)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notas');
    }
};
