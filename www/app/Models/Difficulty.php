<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Difficulty
 * Representa o nível de dificuldade de uma pergunta.
 *
 * @property int $id
 * @property string $name
 * @property int $level
 */
class Difficulty extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'level'];

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
}
