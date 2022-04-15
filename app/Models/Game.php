<?php

namespace App\Models;

use App\Casts\Grid as GridCast;
use App\Game\Difficulty;
use App\Game\Direction;
use App\Game\Grid;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * App\Models\Game
 *
 * @property int $id
 * @property string $uuid
 * @property Difficulty $difficulty
 * @property Grid $grid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Word[] $words
 * @property-read int|null $words_count
 * @method static \Database\Factories\GameFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Game newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Game newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Game query()
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereDifficulty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereGrid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereUuid($value)
 * @mixin \Eloquent
 */
class Game extends Model
{
    use HasFactory;

    public $touches = ['words'];

    protected $fillable = [
        'difficulty',
    ];

    protected $casts = [
        'difficulty' => Difficulty::class,
        'grid'       => GridCast::class,
    ];

    /**
     * @param Difficulty $difficulty
     * @param Collection<Word> $words
     *
     * @return Game
     */
    public static function start(Difficulty $difficulty, Collection $words): Game
    {
        $game = new self();
        $game->difficulty = $difficulty;
        $game->uuid = Str::uuid();

        $grid = new Grid($difficulty->gridSize());

        $words->each(fn(Word $word) => $grid->insertWord($word->text, Direction::random()));

        $grid->finalize();

        $game->grid = $grid;

        $game->save();
        $game->words()->attach($words);

        return $game;
    }

    public function isCompleted(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->words
                    ->filter(fn(Word $word) => $word->session->found)->count() === $this->words->count()
        );
    }

    public function words(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Word::class)
            ->as('session')
            ->withPivot(['found', 'found_at',]);
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }
}
