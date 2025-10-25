<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('game_session_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('game_sessions')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('game_questions')->cascadeOnDelete();
            $table->unsignedInteger('round_number');
            $table->foreignId('selected_answer_id')->nullable()->constrained('game_answers');
            $table->boolean('is_correct')->nullable();
            $table->decimal('prize_value', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_session_questions');
    }
};
