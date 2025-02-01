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
        Schema::table('matriculas', function (Blueprint $table) {

            $table->foreignId('curso_id')->constrained('cursos')->onDelete('cascade');
            $table->string('turno');
            $table->integer('sala');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $table->dropForeign(['curso_id']);
        $table->dropColumn(['turno', 'sala']);
    }
};
