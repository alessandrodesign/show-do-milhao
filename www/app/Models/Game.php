<?php

namespace App\Models;

use App\Enums\GameState;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    protected $fillable = [
        'user_id', 'current_step', 'current_prize', 'secured_prize', 'state',
        'lifeline_5050', 'lifeline_universitarios', 'lifeline_placas', 'lifeline_pulo', 'finished'
    ];
    protected $casts = ['state' => GameState::class];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function gameQuestions(): HasMany
    {
        return $this->hasMany(GameQuestion::class);
    }
}

