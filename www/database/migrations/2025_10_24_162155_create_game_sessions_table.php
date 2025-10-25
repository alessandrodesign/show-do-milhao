<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('game_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('player_name', 100);
            $table->json('selected_category_ids');
            $table->enum('mode', ['fixed', 'progressive'])->default('progressive');
            $table->foreignId('fixed_difficulty_id')->nullable()->constrained('game_difficulties');
            $table->unsignedInteger('current_round')->default(1);
            $table->decimal('current_prize', 12, 2)->default(0);
            $table->decimal('safe_prize', 12, 2)->default(0);
            $table->decimal('final_prize', 12, 2)->default(0);
            $table->enum('status', ['running', 'won', 'lost', 'stopped'])->default('running');
            $table->json('lifelines_state')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_sessions');
    }
};
