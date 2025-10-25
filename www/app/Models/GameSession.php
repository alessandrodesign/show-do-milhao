<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Representa uma partida em andamento ou finalizada.
 */
class GameSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_name',
        'selected_category_ids',
        'mode',
        'fixed_difficulty_id',
        'current_round',
        'current_prize',
        'safe_prize',
        'final_prize',
        'status',
        'lifelines_state',
    ];

    protected $casts = [
        'selected_category_ids' => 'array',
        'lifelines_state'       => 'array',
        'current_prize'         => 'float',
        'safe_prize'            => 'float',
        'final_prize'           => 'float',
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(GameSessionQuestion::class, 'session_id');
    }
}
