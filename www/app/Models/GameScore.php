<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * GameScore - histórico e estatísticas de partidas.
 */
class GameScore extends Model
{
    use HasFactory;

    protected $table = 'game_scores';

    protected $fillable = [
        'player_name',
        'prize',
        'total_correct',
        'total_wrong',
        'questions_total',
        'lifelines_used',
        'avg_response_time',
        'duration_seconds',
    ];

    protected $casts = [
        'prize' => 'float',
        'total_correct' => 'integer',
        'total_wrong' => 'integer',
        'questions_total' => 'integer',
        'lifelines_used' => 'integer',
        'avg_response_time' => 'float',
        'duration_seconds' => 'integer',
    ];
}
