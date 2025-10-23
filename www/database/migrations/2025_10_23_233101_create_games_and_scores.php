<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('games', function (Blueprint $t) {
            $t->id();
            $t->string('player_name');
            $t->integer('score')->default(0);
            $t->timestamp('ended_at')->nullable();
            $t->timestamps();
        });
        Schema::create('game_scores', function (Blueprint $t) {
            $t->id();
            $t->foreignId('game_id')->constrained('games')->cascadeOnDelete();
            $t->foreignId('question_id')->constrained('questions')->cascadeOnDelete();
            $t->boolean('correct');
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_scores');
        Schema::dropIfExists('games');
    }
};
