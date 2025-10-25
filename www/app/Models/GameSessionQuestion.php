<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Pergunta associada a uma partida em andamento.
 */
class GameSessionQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'question_id',
        'round_number',
        'selected_answer_id',
        'is_correct',
        'prize_value',
    ];

    protected $casts = [
        'is_correct'  => 'boolean',
        'prize_value' => 'float',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(GameSession::class, 'session_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(GameQuestion::class, 'question_id');
    }
}
