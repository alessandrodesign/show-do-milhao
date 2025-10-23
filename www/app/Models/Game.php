<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $player_name
 * @property int $score
 * @property \Carbon\Carbon|null $ended_at
 */
class Game extends Model
{
    protected $fillable = ['player_name', 'score', 'ended_at'];

    public $timestamps = true;

    public function scores(): HasMany
    {
        return $this->hasMany(GameScore::class);
    }
}
