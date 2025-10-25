<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class GameQuestion
 *
 * @property int $id
 * @property int $category_id
 * @property int $difficulty_id
 * @property string $question
 * @property string|null $hint
 */
class GameQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'difficulty_id',
        'question',
        'hint',
    ];

    /**
     * Categoria da pergunta.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(GameCategory::class, 'category_id');
    }

    /**
     * Dificuldade da pergunta.
     */
    public function difficulty(): BelongsTo
    {
        return $this->belongsTo(GameDifficulty::class, 'difficulty_id');
    }

    /**
     * Respostas da pergunta.
     */
    public function answers(): HasMany
    {
        return $this->hasMany(GameAnswer::class, 'question_id');
    }
}
