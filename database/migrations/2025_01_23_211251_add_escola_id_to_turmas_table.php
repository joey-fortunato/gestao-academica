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
            // Adiciona a coluna escola_id como unsignedBigInteger
            $table->unsignedBigInteger('escola_id')->nullable();

            // Adiciona a chave estrangeira
            $table->foreign('escola_id')
                  ->references('id')
                  ->on('escolas')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('turmas', function (Blueprint $table) {
            // Remove a chave estrangeira
            $table->dropForeign(['escola_id']);

            // Remove a coluna escola_id
            $table->dropColumn('escola_id');
        });
    }
};
