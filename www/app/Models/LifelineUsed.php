<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class LifelineUsed
 *
 * Registro de uso de uma ajuda (Lifeline) em determinado jogo.
 *
 * @property int $id
 * @property int $game_id
 * @property int $lifeline_id
 * @property int $used_on_step
 * @property array|null $payload
 */
class LifelineUsed extends Model
{
    protected $fillable = [
        'game_id',
        'lifeline_id',
        'used_on_step',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    public function lifeline(): BelongsTo
    {
        return $this->belongsTo(Lifeline::class);
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }
}
