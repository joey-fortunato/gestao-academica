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
        Schema::table('turmas', function (Blueprint $table) {

            $table->string('classe', 10)->after('abreviacao');
            $table->string('turno', 50)->after('classe');
            $table->integer('sala')->after('turno');
            $table->foreignId('director_turma_id')->after('sala')->constrained('professores')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('turmas', function (Blueprint $table) {
            $table->dropForeign(['director_turma_id']);
            $table->dropForeign(['escola_id']);
            $table->dropColumn(['classe', 'turno', 'sala', 'director_turma_id']);
        });
    }
};
