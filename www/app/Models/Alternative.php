<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Alternative
 * Representa uma alternativa de uma pergunta.
 *
 * @property int $id
 * @property int $question_id
 * @property string $letter
 * @property string $text
 * @property bool $is_correct
 */
class Alternative extends Model
{
    use HasFactory;

    protected $fillable = ['question_id', 'letter', 'text', 'is_correct'];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
