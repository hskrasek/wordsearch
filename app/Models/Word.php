<?php

namespace App\Models;

use App\Game\Difficulty;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Word
 *
 * @property int $id
 * @property string $text
 * @property int $length
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\WordFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Word newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Word newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Word query()
 * @method static \Illuminate\Database\Eloquent\Builder|Word whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Word whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Word whereLength($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Word whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Word whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null $frequency
 * @method static \Illuminate\Database\Eloquent\Builder|Word whereFrequency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Word difficulty(\App\Game\Difficulty $difficulty)
 * @method static \Illuminate\Database\Eloquent\Builder|Word excludeWords(\App\Models\Word ...$word)
 */
class Word extends Model
{
    use HasFactory;

    public function scopeDifficulty(Builder $query, Difficulty $difficulty): Builder
    {
        return $query->where('length', '>=', $difficulty->minimumWordLength())
            ->where('length', '<=', $difficulty->gridSize())
            ->limit($difficulty->wordCount());
    }

    public function scopeExcludeWords(Builder $query, Word ...$word): Builder
    {
        return $query->whereNotIn('text', array_map(static fn(Word $word) => $word->text, $word));
    }
}
