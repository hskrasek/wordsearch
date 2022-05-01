<?php

namespace App\Models;

use App\Game\Difficulty;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Staudenmeir\LaravelCte\Eloquent\QueriesExpressions;
use Staudenmeir\LaravelCte\Query\Builder as CTEBuilder;

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
 * @method static \Illuminate\Database\Eloquent\Builder|Word leastFrequentByDifficulty(\App\Game\Difficulty $difficulty)
 * @method static \Illuminate\Database\Eloquent\Builder|Word mostFrequentByDifficulty(\App\Game\Difficulty $difficulty)
 */
class Word extends Model
{
    use HasFactory;
    use QueriesExpressions;

    public function scopeDifficulty(Builder $query, Difficulty $difficulty): Builder
    {
        return $query->where('length', '>=', $difficulty->minimumWordLength())
            ->where('length', '<=', $difficulty->gridSize())
            ->whereNot($query->raw('LOWER("text")'), '~', 'w{3}.*')
            ->when($difficulty === Difficulty::Easy, fn(Builder $builder) => $builder->orderByDesc('frequency'))
            ->when(
                ($difficulty === Difficulty::Medium || $difficulty === Difficulty::Hard),
                fn(Builder|CTEBuilder|Word $builder) => $builder->leastFrequentByDifficulty($difficulty)->unionAll(
                    $builder->mostFrequentByDifficulty($difficulty)
                )
            )
            ->when($difficulty === Difficulty::Insane, function (Builder $builder) {
                return $builder->whereNot($builder->raw('LOWER("text")'), '~', '[a-z]*[aeiouy]{2,}[a-z]*')
                    ->whereNot($builder->raw('LOWER("text")'), '~', '[b-df-jh-np-tv-z]{2,}')
                    ->whereNot($builder->raw('LOWER("text")'), '~', '[a-z]o[a-z]+')
                    ->orderBy('frequency');
            })
            ->limit($difficulty->wordCount());
    }

    public function scopeExcludeWords(Builder $query, Word ...$word): Builder
    {
        return $query->whereNotIn('text', array_map(static fn(Word $word) => $word->text, $word));
    }

    public function scopeLeastFrequentByDifficulty(Builder|CTEBuilder $query, Difficulty $difficulty): Builder
    {
        return $query->newQuery()->from('least_frequent_words')
            ->withExpression(
                'least_frequent_words',
                $query->from($this->getTable())->where('length', '>=', $difficulty->minimumWordLength())
                    ->where('length', '<=', $difficulty->gridSize())
                    ->whereNot($query->raw('LOWER("text")'), '~', '[a-z]*[aeiouy]{2,}[a-z]*')
                    ->whereNot($query->raw('LOWER("text")'), '~', 'w{3}.*')
                    ->whereNot($query->raw('LOWER("text")'), '~', '[b-df-jh-np-tv-z]{2,}')
                    ->whereNot($query->raw('LOWER("text")'), '~', '[a-z]o[a-z]+')
                    ->inRandomOrder()
                    ->orderBy('frequency')
                    ->limit($difficulty->wordCount() / 2)
            );
    }

    public function scopeMostFrequentByDifficulty(Builder|CTEBuilder $query, Difficulty $difficulty): Builder
    {
        return $query->newQuery()->from('most_frequent_words')
            ->withExpression(
                'most_frequent_words',
                $query->from($this->getTable())->where('length', '>=', $difficulty->minimumWordLength())
                    ->where('length', '<=', $difficulty->gridSize())
                    ->whereNot($query->raw('LOWER("text")'), '~', '[a-z]*[aeiouy]{2,}[a-z]*')
                    ->whereNot($query->raw('LOWER("text")'), '~', 'w{3}.*')
                    ->whereNot($query->raw('LOWER("text")'), '~', '[b-df-jh-np-tv-z]{2,}')
                    ->whereNot($query->raw('LOWER("text")'), '~', '[a-z]o[a-z]+')
                    ->inRandomOrder()
                    ->orderByDesc('frequency')
                    ->limit($difficulty->wordCount() / 2)
            );
    }
}
