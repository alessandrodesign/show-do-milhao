<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Category
 * Representa o tema de uma pergunta.
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 */
class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
}
