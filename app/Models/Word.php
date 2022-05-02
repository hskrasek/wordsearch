<?php

namespace App\Models;

use App\Game\Difficulty;
use App\Models\Scopes\WithoutDiphthongs;
use App\Models\Scopes\WithoutWebsites;
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
 * @property int|null $frequency
 * @method static \Illuminate\Database\Eloquent\Builder|Word excludeWords(\App\Models\Word ...$word)
 * @method static \Database\Factories\WordFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Word forDifficulty(\App\Game\Difficulty $difficulty)
 * @method static \Illuminate\Database\Eloquent\Builder|Word leastFrequentByDifficulty(\App\Game\Difficulty $difficulty)
 * @method static \Illuminate\Database\Eloquent\Builder|Word mostFrequentByDifficulty(\App\Game\Difficulty $difficulty)
 * @method static \Illuminate\Database\Eloquent\Builder|Word newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Word newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Word query()
 * @method static \Illuminate\Database\Eloquent\Builder|Word whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Word whereFrequency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Word whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Word whereLength($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Word whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Word whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Word extends Model
{
    use HasFactory;
    use QueriesExpressions;

    protected static function booted()
    {
        static::addGlobalScope(new WithoutWebsites());
        static::addGlobalScope(new WithoutDiphthongs());
    }

    public function scopeForDifficulty(Builder $query, Difficulty $difficulty): Builder
    {
        return $query->where('length', '>=', $difficulty->minimumWordLength())
            ->where('length', '<=', $difficulty->gridSize())
            ->when($difficulty === Difficulty::Easy, fn(Builder $builder) => $builder->orderByDesc('frequency'))
            ->when(
                ($difficulty === Difficulty::Medium || $difficulty === Difficulty::Hard),
                fn(Builder|CTEBuilder|Word $builder) => $builder->leastFrequentByDifficulty($difficulty)->unionAll(
                    self::mostFrequentByDifficulty($difficulty)
                )
            )
            ->when($difficulty === Difficulty::Insane, function (Builder $builder) {
                return $builder->whereNot('text', '~*', '[a-z]*[aeiouy]{2,}[a-z]*')
                    ->whereNot('text', '~*', '[b-df-jh-np-tv-z]{2,}')
                    ->whereNot('text', '~*', '[a-z]o[a-z]+')
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
        return $query->from('least_frequent_words')
            ->withExpression(
                'least_frequent_words',
                self::from($this->getTable())->where('length', '>=', $difficulty->minimumWordLength())
                    ->where('length', '<=', $difficulty->gridSize())
                    ->whereNot('text', '~*', '[b-df-jh-np-tv-z]{2,}')
                    ->whereNot('text', '~*', '[a-z]o[a-z]+')
                    ->inRandomOrder()
                    ->orderBy('frequency')
                    ->limit(
                        match ($difficulty) {
                            Difficulty::Hard => $difficulty->wordCount() * 0.75,
                            default => $difficulty->wordCount() / 2
                        }
                    )
            );
    }

    public function scopeMostFrequentByDifficulty(Builder|CTEBuilder $query, Difficulty $difficulty): Builder
    {
        return $query->newQuery()->from('most_frequent_words')
            ->withExpression(
                'most_frequent_words',
                self::from($this->getTable())->where('length', '>=', $difficulty->minimumWordLength())
                    ->where('length', '<=', $difficulty->gridSize())
                    ->whereNot('text', '~*', '[b-df-jh-np-tv-z]{2,}')
                    ->whereNot('text', '~*', '[a-z]o[a-z]+')
                    ->inRandomOrder()
                    ->orderByDesc('frequency')
                    ->limit(
                        match ($difficulty) {
                            Difficulty::Hard => $difficulty->wordCount() * 0.25,
                            default => $difficulty->wordCount() / 2
                        }
                    )
            );
    }
}
