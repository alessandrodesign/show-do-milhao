<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class GameDifficulty
 *
 * @property int $id
 * @property string $name
 * @property float $prize
 * @property int $order
 */
class GameDifficulty extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'prize',
        'order',
    ];

    protected $casts = [
        'prize' => 'float',
        'order' => 'integer',
    ];

    /**
     * Relacionamento com perguntas.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(GameQuestion::class, 'difficulty_id');
    }
}
