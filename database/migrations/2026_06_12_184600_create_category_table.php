<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('category', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(User::class)
            ->constrained('users')
            ->onDelete('cascade');

            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('color');

            $table->timestamps();

            $table->unique(['user_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category');
    }
};
