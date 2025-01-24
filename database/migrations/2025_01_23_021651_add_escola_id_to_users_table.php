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
        Schema::table('users', function (Blueprint $table) {
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
        Schema::table('users', function (Blueprint $table) {
            // Remove a chave estrangeira
            $table->dropForeign(['escola_id']);
        });
    }
};
