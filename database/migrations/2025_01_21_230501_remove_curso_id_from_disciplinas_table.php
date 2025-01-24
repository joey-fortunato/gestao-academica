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
        Schema::table('disciplinas', function (Blueprint $table) {
            $table->dropForeign(['curso_id']); // Remove a chave estrangeira
            $table->dropColumn('curso_id'); // Remove a coluna
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('disciplinas', function (Blueprint $table) {
            $table->foreignId('curso_id')->constrained()->onDelete('cascade'); // Recria a coluna e a chave estrangeira
        });
    }
};
