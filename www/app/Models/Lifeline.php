<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class Lifeline
 *
 * Representa um tipo de ajuda disponível no jogo (50/50, Universitários, Placas, Pulo).
 *
 * @property int $id
 * @property string $key
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Lifeline extends Model
{
    protected $fillable = [
        'key',
        'name',
    ];

    public function usages()
    {
        return $this->hasMany(LifelineUsed::class);
    }
}
