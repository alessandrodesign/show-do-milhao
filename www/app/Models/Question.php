<?php

namespace App\Models;

use App\Enums\Difficulty;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = ['title', 'statement', 'difficulty', 'active'];
    protected $casts = ['difficulty' => Difficulty::class];

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    public function correctAnswer(): HasMany
    {
        return $this->hasOne(Answer::class)->where('is_correct', true);
    }
}

