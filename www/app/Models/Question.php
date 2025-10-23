<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Question
 * Representa uma pergunta com suas alternativas.
 *
 * @property int $id
 * @property string $statement
 * @property int $category_id
 * @property int $difficulty_id
 */
class Question extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'difficulty_id', 'statement'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function difficulty(): BelongsTo
    {
        return $this->belongsTo(Difficulty::class);
    }

    public function alternatives(): HasMany
    {
        return $this->hasMany(Alternative::class);
    }
}
