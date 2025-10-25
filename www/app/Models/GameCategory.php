<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class GameCategory
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 */
class GameCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Relacionamento com perguntas.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(GameQuestion::class, 'category_id');
    }
}
