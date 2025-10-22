<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameQuestion extends Model
{
    protected $fillable = ['game_id', 'question_id', 'step', 'selected_answer_id', 'is_correct'];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function selectedAnswer(): BelongsTo
    {
        return $this->belongsTo(Answer::class, 'selected_answer_id');
    }
}

