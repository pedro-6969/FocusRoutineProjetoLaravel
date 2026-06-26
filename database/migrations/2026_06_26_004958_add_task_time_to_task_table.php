<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Adiciona o horário opcional da tarefa.
     */
    public function up(): void
    {
        Schema::table('task', function (Blueprint $table) {
            $table->time('task_time')
                ->nullable()
                ->after('task_date');
        });
    }

    /**
     * Remove a coluna caso a migration seja revertida.
     */
    public function down(): void
    {
        Schema::table('task', function (Blueprint $table) {
            $table->dropColumn('task_time');
        });
    }
};