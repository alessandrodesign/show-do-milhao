<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class GameAnswer
 *
 * @property int $id
 * @property int $question_id
 * @property string $text
 * @property bool $is_correct
 */
class GameAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
       'question_id',
       'text',
       'is_correct',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    /**
     * Pergunta associada.
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(GameQuestion::class, 'question_id');
    }
}
