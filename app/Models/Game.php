<?php

namespace App\Models;

use App\Casts\Grid as GridCast;
use App\Exceptions\Game\GridException;
use App\Game\Difficulty;
use App\Game\Direction;
use App\Game\Grid;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
 * @property int $user_id
 * @property-read \App\Models\User|null $player
 * @property-read EloquentCollection|\App\Models\Word[] $words
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
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereUuid($value)
 * @mixin \Eloquent
 */
class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'difficulty',
    ];

    protected $casts = [
        'difficulty' => Difficulty::class,
        'grid'       => GridCast::class,
    ];

    /**
     * @param Difficulty $difficulty
     * @param Collection<Word>|EloquentCollection<Word> $words
     *
     * @return Game
     */
    public static function start(User $player, Difficulty $difficulty, EloquentCollection|Collection $words): Game
    {
        $game             = new self();
        $game->difficulty = $difficulty;
        $game->uuid       = Str::uuid();

        $game->grid = new Grid($difficulty->gridSize());

        $successfulWords = EloquentCollection::make();

        /** @var Word $word */
        while (!($word = $words->pop()) instanceof Collection && $word !== null) {
            try {
                $game->grid->insertWord($word->text, Direction::random());
                $successfulWords->push($word);
            } catch (GridException $exception) {
                /** @noinspection CallableParameterUseCaseInTypeContextInspection */
                $words = Word::excludeWords(
                    ...[
                           ...$words,
                           ...$successfulWords,
                           $word,
                       ]
                )
                    ->forDifficulty($difficulty)
                    ->limit($difficulty->wordCount() - $successfulWords->count())
                    ->get();
            }
        }

        $game->grid->finalize();

        $player->games()->save($game);

        $game->words()->sync($successfulWords);

        return $game;
    }

    public function isCompleted(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->words
                    ->filter(fn(Word $word) => $word->session->found)->count() === $this->words->count()
        );
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
