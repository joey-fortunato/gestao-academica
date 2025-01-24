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
        Schema::table('disciplina_professor', function (Blueprint $table) {

            $table->foreign('disciplina_id')
            ->references('id')
            ->on('disciplinas')
            ->onDelete('cascade');

            $table->foreign('professor_id')
            ->references('id')
            ->on('professores')
            ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disciplina_professor');
    }
};
