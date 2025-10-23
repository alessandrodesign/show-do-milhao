<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Registro de cada resposta dada durante o jogo.
 */
class GameScore extends Model
{
    protected $fillable = ['game_id', 'question_id', 'correct'];

    public $timestamps = true;

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }
}
