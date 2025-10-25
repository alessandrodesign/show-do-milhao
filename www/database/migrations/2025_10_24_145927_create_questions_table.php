<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('game_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('game_categories')->cascadeOnDelete();
            $table->foreignId('difficulty_id')->constrained('game_difficulties')->cascadeOnDelete();
            $table->text('question');
            $table->string('hint', 300)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_questions');
    }
};
