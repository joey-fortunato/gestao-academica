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
        Schema::create('curso_disciplina', function (Blueprint $table) {
            $table->foreignId('curso_id')->constrained()->onDelete('cascade');
            $table->foreignId('disciplina_id')->constrained()->onDelete('cascade');
            $table->primary(['curso_id', 'disciplina_id']); // Chave primária composta
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curso_disciplina');
    }
};
